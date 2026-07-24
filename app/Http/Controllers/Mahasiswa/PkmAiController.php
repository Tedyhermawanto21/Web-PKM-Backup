<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PkmRecommenderService;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\RateLimiter;

class PkmAiController extends Controller
{
    protected PkmRecommenderService $aiService;

    public function __construct(PkmRecommenderService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function generate(Request $request)
    {
        @set_time_limit(240);

        $request->validate([
            'ide_proposal' => 'required|string|min:10',
            'has_partner' => 'sometimes|boolean',
            'partner_is_profit' => 'sometimes|boolean',
            'method' => 'sometimes|in:hybrid,llm',
        ]);

        $ide = $request->ide_proposal;
        $hasPartner = $request->boolean('has_partner');
        $partnerIsProfit = $request->boolean('partner_is_profit');
        $method = $request->input('method', 'hybrid');

        // Rate limit per pengguna untuk cegah spam (generous untuk pemakaian normal).
        $rateKey = 'pkm-ai:' . (optional($request->user())->id ?? $request->ip());
        if (RateLimiter::tooManyAttempts($rateKey, 20)) {
            $seconds = RateLimiter::availableIn($rateKey);
            return response()->json([
                'status' => 'error',
                'message' => "Terlalu banyak permintaan. Coba lagi dalam {$seconds} detik.",
            ], 429);
        }
        RateLimiter::hit($rateKey, 60);

        // Pilih pendekatan sesuai toggle: Full LLM (B) atau Hybrid (A).
        $recommendation = $method === 'llm'
            ? $this->aiService->generateRecommendationLlm($ide, 3, $hasPartner, $partnerIsProfit)
            : $this->aiService->generateRecommendation($ide, 3, $hasPartner, $partnerIsProfit);

        if ($recommendation) {
            $recommendation = $this->sanitizePredictionResult($recommendation);
            $recommendation['dosen_recommendations'] = $this->attachDbUserIds(
                $recommendation['dosen_recommendations'] ?? []
            );
            $recommendation['packages'] = !empty($recommendation['schema_title_groups'])
                ? $this->buildPackagesFromGroups(
                    $recommendation['schema_title_groups'],
                    $recommendation['dosen_recommendations'] ?? []
                )
                : $this->buildPackages(
                    $recommendation['recommended_titles'] ?? [],
                    $recommendation['dosen_recommendations'] ?? [],
                    $recommendation['schema']['predicted_schema'] ?? null
                );

            $this->writePredictionLog($ide, $hasPartner, $partnerIsProfit, $recommendation, $method === 'llm' ? 'llm-pipeline' : 'hybrid-pipeline');

            return response()->json([
                'status' => 'success',
                'data' => $recommendation,
            ]);
        }

        // 1. Dapatkan Judul
        $titles = $this->aiService->generateTitles($ide, 3);
        
        // 2. Dapatkan Prediksi Skema
        $schemaData = $this->aiService->predictSchema($ide, false, false);
        $predictedSchema = $schemaData ? $schemaData['predicted_schema'] : null;

        // 3. Dapatkan Rekomendasi Dosen Pembimbing
        $rawDosens = $this->aiService->recommendDosen($ide, 3);
        $recommendedDosens = [];

        foreach ($rawDosens as $rd) {
            $dosenData   = $rd['dosen'] ?? [];
            $matchScore  = round(($rd['similarity_score'] ?? 0) * 100, 1);
            $dosenName   = $dosenData['name'] ?? null;
            $dosenDept   = $dosenData['dept'] ?? ($dosenData['program_studi'] ?? null);
            $dosenExpert = $dosenData['keywords'] ?? ($dosenData['expertise'] ?? 'Umum');

            if (!$dosenName) continue;

            // Cocokkan ke user DB berdasarkan nama (case-insensitive)
            $dbUserId   = null;
            $dbUserDept = $dosenDept;
            $dbUser = User::whereRaw('LOWER(name) = ?', [strtolower($dosenName)])->first();
            if ($dbUser) {
                $dbUserId   = $dbUser->id;
                $dosenName  = $dbUser->name;
                $dbUserDept = $dbUser->program_studi ?? $dosenDept;
            }

            $recommendedDosens[] = [
                'id'            => $dbUserId,
                'name'          => $dosenName,
                'program_studi' => $dbUserDept,
                'match_score'   => $matchScore,
                'expertise'     => is_string($dosenExpert) ? substr($dosenExpert, 0, 120) : 'Umum',
            ];
        }

        $packages = $this->buildPackages($titles, $recommendedDosens, $predictedSchema);

        $responseData = [
            'schema' => $schemaData,
            'dosen_recommendations' => $recommendedDosens,
            'recommended_titles' => $titles,
            'packages' => $packages,
            'llm' => [
                'provider' => 'local',
                'model' => null,
                'used_live_search' => false,
                'notes' => 'Pipeline terpadu tidak tersedia, memakai endpoint lokal lama.',
            ],
        ];

        $this->writePredictionLog($ide, $hasPartner, $partnerIsProfit, $responseData, 'fallback');

        return response()->json([
            'status' => 'success',
            'data' => $responseData,
        ]);
    }

    /**
     * Endpoint eksperimen komparatif: mode=retrieval_based (Pendekatan A) atau
     * mode=full_llm (Pendekatan B). Skema input & output sama untuk kedua mode.
     */
    public function experiment(Request $request)
    {
        @set_time_limit(240);

        $request->validate([
            'idea' => 'required_without:abstract|nullable|string',
            'abstract' => 'nullable|string',
            'pkm_category' => 'nullable|string',
            'top_k_lecturers' => 'sometimes|integer|min:1|max:10',
            'total_titles' => 'sometimes|integer|min:1|max:5',
            'mode' => 'required|in:retrieval_based,full_llm',
            'proposal_id' => 'nullable',
        ]);

        // Rate limit per pengguna (cegah spam). Generous untuk pemakaian normal.
        $rateKey = 'pkm-exp:' . (optional($request->user())->id ?? $request->ip());
        if (RateLimiter::tooManyAttempts($rateKey, 30)) {
            $seconds = RateLimiter::availableIn($rateKey);
            return response()->json([
                'status' => 'error',
                'message' => "Terlalu banyak permintaan. Coba lagi dalam {$seconds} detik.",
            ], 429);
        }
        RateLimiter::hit($rateKey, 60);

        $payload = [
            'proposal_id' => $request->input('proposal_id'),
            'idea' => (string) $request->input('idea', ''),
            'abstract' => (string) $request->input('abstract', ''),
            'pkm_category' => (string) $request->input('pkm_category', ''),
            'top_k_lecturers' => (int) $request->input('top_k_lecturers', 3),
            'total_titles' => (int) $request->input('total_titles', 3),
            'mode' => $request->input('mode'),
        ];

        $result = $this->aiService->experimentRecommend($payload);

        if (isset($result['__error'])) {
            return response()->json([
                'status' => 'error',
                'message' => $result['__error'],
            ], 200);
        }

        // Tautkan dosen ke tabel users berdasarkan nama (untuk id/prodi tampilan),
        // tanpa mengubah lecturer_id dari dataset eksperimen.
        $result['lecturer_recommendations'] = array_map(function ($lec) {
            $name = $lec['lecturer_name'] ?? null;
            if ($name) {
                $dbUser = User::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();
                if ($dbUser) {
                    $lec['user_id'] = $dbUser->id;
                    $lec['program_studi'] = $dbUser->program_studi ?? ($lec['program_studi'] ?? null);
                }
            }
            return $lec;
        }, $result['lecturer_recommendations'] ?? []);

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ]);
    }

    private function attachDbUserIds(array $dosens): array
    {
        return array_map(function ($dosen) {
            $name = $dosen['name'] ?? null;
            if (!$name) {
                return $dosen;
            }

            $dbUser = User::whereRaw('LOWER(name) = ?', [strtolower($name)])->first();
            if ($dbUser) {
                $dosen['id'] = $dbUser->id;
                $dosen['name'] = $dbUser->name;
                $dosen['program_studi'] = $dbUser->program_studi ?? ($dosen['program_studi'] ?? null);
            }

            return $dosen;
        }, $dosens);
    }

    private function buildPackages(array $titles, array $dosens, ?string $schema): array
    {
        $packages = [];
        $defaultDosen = !empty($dosens) ? $dosens[0] : null;

        foreach ($titles as $index => $title) {
            $packages[] = [
                'judul' => $title,
                'skema' => $schema,
                'dosen' => $dosens[$index] ?? $defaultDosen,
            ];
        }

        return $packages;
    }

    private function buildPackagesFromGroups(array $groups, array $dosens): array
    {
        $packages = [];
        $defaultDosen = !empty($dosens) ? $dosens[0] : null;
        $dosenCount = count($dosens);
        $index = 0;

        foreach ($groups as $group) {
            $schema = $group['schema'] ?? null;
            $titles = $group['titles'] ?? [];

            foreach ($titles as $title) {
                $packages[] = [
                    'judul' => $title,
                    'skema' => $schema,
                    'dosen' => $dosenCount > 0 ? ($dosens[$index % $dosenCount] ?? $defaultDosen) : $defaultDosen,
                ];
                $index++;
            }
        }

        return $packages;
    }

    private function sanitizePredictionResult(array $result): array
    {
        $notes = $result['llm']['notes'] ?? null;
        if (!is_string($notes) || !str_contains($notes, 'Detail:')) {
            return $result;
        }

        [$message, $detail] = array_pad(explode('Detail:', $notes, 2), 2, null);

        $result['llm']['notes'] = trim($message) ?: 'Groq tidak tersedia, memakai fallback lokal.';
        $result['llm']['error_detail'] = trim((string) $detail) ?: null;

        return $result;
    }

    private function writePredictionLog(
        string $ide,
        bool $hasPartner,
        bool $partnerIsProfit,
        array $result,
        string $source
    ): void {
        $payload = [
            'generated_at' => now()->toDateTimeString(),
            'source' => $source,
            'input' => [
                'ide_proposal' => $ide,
                'has_partner' => $hasPartner,
                'partner_is_profit' => $partnerIsProfit,
            ],
            'result' => $result,
        ];

        $latestPath = storage_path('logs/pkm-ai-latest.json');
        $historyPath = storage_path('logs/pkm-ai.log');

        File::ensureDirectoryExists(dirname($latestPath));
        File::put($latestPath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        File::append($historyPath, json_encode($payload, JSON_UNESCAPED_UNICODE) . PHP_EOL);
    }
}
