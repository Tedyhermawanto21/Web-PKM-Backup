<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProposalReviewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewerController extends Controller
{
    public function index()
    {
        // Get all proposals assigned to this dosen as reviewer
        $proposals = Proposal::whereHas('reviewers', function ($q) {
            $q->where('reviewer_id', Auth::id());
        })
        ->with(['ketua', 'dosenPembimbing', 'reviewers' => function($q) {
            $q->where('reviewer_id', Auth::id());
        }])
        ->latest()
        ->get();

        return view('dashboard.dosen.reviewer.index', compact('proposals'));
    }

    public function show(Proposal $proposal)
    {
        // Check if current user is assigned as reviewer for this proposal
        $reviewerAssignment = ProposalReviewer::where('proposal_id', $proposal->id)
            ->where('reviewer_id', Auth::id())
            ->first();

        if (!$reviewerAssignment) {
            abort(403, 'Anda tidak ditugaskan sebagai reviewer untuk proposal ini.');
        }

        $proposal->load(['ketua', 'dosenPembimbing', 'anggota']);

        // Load anggota data from kelompok if not exists in proposal
        if ($proposal->anggota->isEmpty()) {
            $kelompok = \App\Models\Kelompok::with(['anggota'])->where('ketua_id', $proposal->ketua_id)
                ->where('nama_kelompok', $proposal->nama_kelompok)
                ->first();

            if ($kelompok) {
                $pivotRows = \App\Models\KelompokUser::where('kelompok_id', $kelompok->id)->get();
                $userIds = $pivotRows->pluck('user_id')->filter()->unique()->values()->all();
                $users = count($userIds) ? \App\Models\User::whereIn('id', $userIds)->get()->keyBy('id') : collect();

                $anggotaRegistered = $pivotRows->map(function ($row) use ($users) {
                    if ($row->user_id && isset($users[$row->user_id])) {
                        $u = $users[$row->user_id];
                        return (object) [
                            'nama' => $u->name,
                            'nim' => $u->nim ?? null,
                            'program_studi' => $u->program_studi ?? null,
                            'posisi' => $row->posisi ?? 'anggota'
                        ];
                    }
                    return null;
                })->filter()->values();

                $freeRows = \App\Models\KelompokAnggota::where('kelompok_id', $kelompok->id)->get();
                $anggotaFree = $freeRows->map(function ($row) {
                    return (object) [
                        'nama' => $row->nama,
                        'nim' => $row->nim,
                        'program_studi' => $row->program_studi,
                        'posisi' => $row->posisi ?? 'anggota'
                    ];
                });

                $proposal->anggota = $anggotaRegistered->merge($anggotaFree);
            }
        }

        return view('dashboard.dosen.reviewer.show', compact('proposal', 'reviewerAssignment'));
    }

    public function submitReview(Request $request, Proposal $proposal)
    {
        // Check if current user is assigned as reviewer for this proposal
        $reviewerAssignment = ProposalReviewer::where('proposal_id', $proposal->id)
            ->where('reviewer_id', Auth::id())
            ->first();

        if (!$reviewerAssignment) {
            return back()->with('error', 'Anda tidak ditugaskan sebagai reviewer untuk proposal ini.');
        }

        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'comments' => 'required|string|min:20'
        ], [
            'score.required' => 'Skor wajib diisi',
            'score.min' => 'Skor minimal 0',
            'score.max' => 'Skor maksimal 100',
            'comments.required' => 'Komentar review wajib diisi',
            'comments.min' => 'Komentar minimal 20 karakter'
        ]);

        try {
            $reviewerAssignment->update([
                'score' => $request->score,
                'comments' => $request->comments,
                'status' => 'reviewed',
                'reviewed_at' => now()
            ]);

            return redirect()->route('dosen.reviewer.index')
                ->with('success', 'Review berhasil dikirim!');
        } catch (\Throwable $e) {
            \Log::error('Error submitting review: ' . $e->getMessage(), ['exception' => $e]);
            return back()->with('error', 'Terjadi kesalahan saat mengirim review. Silakan coba lagi.');
        }
    }
}
