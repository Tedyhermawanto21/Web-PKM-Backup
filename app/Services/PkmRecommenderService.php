<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PkmRecommenderService
{
    protected string $baseUrl;

    public function __init__()
    {
        $this->baseUrl = env('PKM_RECOMMENDER_URL', 'http://127.0.0.1:8001/api');
    }

    public function __construct()
    {
        $this->baseUrl = env('PKM_RECOMMENDER_URL', 'http://127.0.0.1:8001/api');
    }

    /**
     * Run the complete PKM recommendation pipeline.
     */
    public function generateRecommendation(string $ideProposal, int $topK = 3, bool $hasPartner = false, bool $isProfit = false): ?array
    {
        try {
            $response = Http::timeout(120)->post("{$this->baseUrl}/recommendation", [
                'ide_proposal' => $ideProposal,
                'top_k' => $topK,
                'has_partner' => $hasPartner,
                'partner_is_profit' => $isProfit,
            ]);

            if ($response->successful() && ($response->json('status') === 'success')) {
                return $response->json('data') ?? null;
            }

            Log::error("API Error recommendation: " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("Connection Error recommendation: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Jalankan endpoint eksperimen komparatif (mode: retrieval_based | full_llm).
     * Mengembalikan array data hasil, atau ['__error' => pesan] bila gagal.
     */
    public function experimentRecommend(array $payload): array
    {
        try {
            $response = Http::timeout(180)->post("{$this->baseUrl}/experiment/recommend", $payload);

            if ($response->successful() && ($response->json('status') === 'success')) {
                return $response->json('data') ?? ['__error' => 'Respons kosong dari layanan rekomendasi.'];
            }

            Log::error("API Error experiment/recommend: " . $response->body());
            return ['__error' => $response->json('message') ?? 'Permintaan rekomendasi gagal diproses.'];
        } catch (\Exception $e) {
            Log::error("Connection Error experiment/recommend: " . $e->getMessage());
            return ['__error' => 'Tidak dapat terhubung ke layanan rekomendasi. Pastikan service AI berjalan.'];
        }
    }

    /**
     * Run the Full LLM recommendation pipeline (Pendekatan B).
     */
    public function generateRecommendationLlm(string $ideProposal, int $topK = 3, bool $hasPartner = false, bool $isProfit = false): ?array
    {
        try {
            $response = Http::timeout(150)->post("{$this->baseUrl}/recommendation-llm", [
                'ide_proposal' => $ideProposal,
                'top_k' => $topK,
                'has_partner' => $hasPartner,
                'partner_is_profit' => $isProfit,
            ]);

            if ($response->successful() && ($response->json('status') === 'success')) {
                return $response->json('data') ?? null;
            }

            Log::error("API Error recommendation-llm: " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("Connection Error recommendation-llm: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get lecturer recommendations based on an idea description.
     */
    public function recommendDosen(string $ideProposal, int $topK = 3): array
    {
        try {
            $response = Http::timeout(90)->post("{$this->baseUrl}/recommend-dosen", [
                'ide_proposal' => $ideProposal,
                'top_k' => $topK,
            ]);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }
            
            Log::error("API Error recommend_dosen: " . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error("Connection Error recommend_dosen: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Generate diverse PKM titles.
     */
    public function generateTitles(string $ideProposal, int $topK = 3): array
    {
        try {
            $response = Http::timeout(90)->post("{$this->baseUrl}/generate-titles", [
                'ide_proposal' => $ideProposal,
                'top_k' => $topK,
            ]);

            if ($response->successful()) {
                return $response->json()['data'] ?? [];
            }
            
            Log::error("API Error generate_titles: " . $response->body());
            return [];
        } catch (\Exception $e) {
            Log::error("Connection Error generate_titles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Predict PKM schema.
     */
    public function predictSchema(string $ideProposal, bool $hasPartner = false, bool $isProfit = false): ?array
    {
        try {
            $response = Http::timeout(90)->post("{$this->baseUrl}/predict-schema", [
                'ide_proposal' => $ideProposal,
                'has_partner' => $hasPartner,
                'partner_is_profit' => $isProfit,
            ]);

            if ($response->successful()) {
                return $response->json()['data'] ?? null;
            }
            
            Log::error("API Error predict_schema: " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("Connection Error predict_schema: " . $e->getMessage());
            return null;
        }
    }
}
