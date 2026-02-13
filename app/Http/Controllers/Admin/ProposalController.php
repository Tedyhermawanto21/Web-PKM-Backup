<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Kelompok;
use App\Models\KelompokUser;
use App\Models\KelompokAnggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
        // Fetch stats for Skemas with proposal counts
        $skemas = \App\Models\Skema::withCount(['proposals' => function($query) {
            $query->whereNotNull('file_proposal');
        }])->get();

        // Base query
        $query = Proposal::whereNotNull('file_proposal')
            ->with(['ketua', 'dosenPembimbing'])
            ->latest();

        // Filter by Skema if selected
        if ($request->has('skema') && $request->skema != '') {
            $query->where('skema', $request->skema);
            $selectedSkema = \App\Models\Skema::where('nama', $request->skema)->first();
        } else {
            $selectedSkema = null;
        }

        $proposals = $query->get();

        return view('dashboard.admin.proposals.index', compact('proposals', 'skemas', 'selectedSkema'));
    }

    public function show(Proposal $proposal)
    {
        $proposal->load(['ketua', 'dosenPembimbing', 'anggota']);

        // If the Proposal has no anggota rows (it may have been created from a Kelompok),
        // try to load anggota data from `kelompoks` (pivot `kelompok_user` and `kelompok_anggota`).
        if ($proposal->anggota->isEmpty()) {
            $kelompok = Kelompok::with(['anggota'])->where('ketua_id', $proposal->ketua_id)
                ->where('nama_kelompok', $proposal->nama_kelompok)
                ->first();

            if ($kelompok) {
                $pivotRows = KelompokUser::where('kelompok_id', $kelompok->id)->get();
                $userIds = $pivotRows->pluck('user_id')->filter()->unique()->values()->all();
                $users = count($userIds) ? User::whereIn('id', $userIds)->get()->keyBy('id') : collect();

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

                $freeRows = KelompokAnggota::where('kelompok_id', $kelompok->id)->get();
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

        return view('dashboard.admin.proposals.show', compact('proposal'));
    }

    public function approve(Request $request, Proposal $proposal)
    {
        // Log incoming approve attempts to help diagnose why approval may not reach this method
        Log::info('Admin approve called', [
            'proposal_id' => $proposal->id ?? null,
            'payload' => $request->all(),
            'user_id' => auth()->id() ?? null,
        ]);

        // Ensure proposal has assigned reviewers and all reviewers have completed review
        $assigned = $proposal->reviewers()->withPivot('status')->get();
        if ($assigned->isEmpty()) {
            return redirect()->route('admin.pengajuan_kelompok_pkm.show', $proposal->id)
                ->with('error', 'Tidak ada reviewer yang ditugaskan. Admin harus menugaskan reviewer terlebih dahulu.');
        }

        $notReviewed = $assigned->filter(function ($r) {
            return ($r->pivot->status ?? null) !== 'reviewed';
        });

        if ($notReviewed->count() > 0) {
            return redirect()->route('admin.pengajuan_kelompok_pkm.show', $proposal->id)
                ->with('error', 'Belum semua reviewer menyelesaikan review. Tunggu sampai reviewer menyelesaikan review sebelum menyetujui.');
        }

        try {
            $proposal->update([
                'status_admin' => 'disetujui',
                'catatan_admin' => $request->catatan_admin,
                'revision_stage' => 0, // Reset revision stage on approval
                'revision_notes' => null
            ]);

            return redirect()->route('admin.pengajuan_kelompok_pkm.show', $proposal->id)
                ->with('success', 'Proposal berhasil disetujui!');
        } catch (\Throwable $e) {
            Log::error('Admin approve error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('admin.pengajuan_kelompok_pkm.show', $proposal->id)
                ->with('error', 'Terjadi kesalahan saat menyetujui proposal. Silakan coba lagi.');
        }
    }

    public function reject(Request $request, Proposal $proposal)
    {
        $request->validate([
            'catatan_admin' => 'required|string|min:10',
            'revision_stage' => 'nullable|integer|min:0|max:3'
        ], [
            'catatan_admin.required' => 'Catatan wajib diisi untuk penolakan',
            'catatan_admin.min' => 'Catatan minimal 10 karakter'
        ]);

        $updateData = [
            'status_admin' => 'ditolak',
            'catatan_admin' => $request->catatan_admin
        ];

        // Set revision stage if provided
        if ($request->has('revision_stage')) {
            $updateData['revision_stage'] = $request->revision_stage;
            $updateData['revision_notes'] = $request->catatan_admin;
        }

        try {
            $proposal->update($updateData);

            $message = 'Proposal ditolak. Mahasiswa dapat mengupload ulang.';
            if ($request->revision_stage > 0) {
                $message = 'Proposal perlu revisi tahap ' . $request->revision_stage . '. Mahasiswa dapat melakukan revisi sesuai jadwal.';
            }

            return redirect()->route('admin.pengajuan_kelompok_pkm.show', $proposal->id)
                ->with('success', $message);
        } catch (\Throwable $e) {
            Log::error('Admin reject error: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('admin.pengajuan_kelompok_pkm.show', $proposal->id)
                ->with('error', 'Terjadi kesalahan saat menolak proposal. Silakan coba lagi.');
        }
    }

    public function assignReviewer(Request $request, Proposal $proposal)
    {
        $request->validate([
            'reviewer_id' => 'required|integer|exists:users,id'
        ]);

        $reviewer = \App\Models\User::find($request->reviewer_id);
        if (!$reviewer) {
            return back()->with('error', 'Reviewer tidak ditemukan.');
        }

        // Allow both reviewer and dosen roles to be assigned as reviewers
        if (!$reviewer->role || !in_array($reviewer->role->name, ['reviewer', 'dosen'])) {
            return back()->with('error', 'Pengguna yang dipilih bukan reviewer atau dosen.');
        }

        // Check if already assigned
        $existingAssignment = \App\Models\ProposalReviewer::where([
            'proposal_id' => $proposal->id,
            'reviewer_id' => $reviewer->id,
        ])->first();

        if ($existingAssignment) {
            return back()->with('error', 'Dosen/Reviewer ini sudah ditugaskan pada proposal ini.');
        }

        // Ensure pivot row exists and set initial status using the ProposalReviewer model
        \App\Models\ProposalReviewer::create([
            'proposal_id' => $proposal->id,
            'reviewer_id' => $reviewer->id,
            'status' => 'pending',
            'score' => null,
            'comments' => null,
        ]);

        // Update proposal status to indicate it's been assigned to reviewer(s)
        try {
            $proposal->update(['status_admin' => 'ditugaskan']);
        } catch (\Throwable $e) {
            // don't fail assignment if status update fails; just log
            \Log::warning('Failed to update proposal status_admin after assigning reviewer: ' . $e->getMessage());
        }

        return back()->with('success', 'Reviewer berhasil ditugaskan ke proposal.');
    }

    public function unassignReviewer(Request $request, Proposal $proposal)
    {
        $request->validate([
            'reviewer_id' => 'required|integer|exists:users,id'
        ]);

        $proposal->reviewers()->detach($request->reviewer_id);

        return back()->with('success', 'Reviewer dihapus dari penugasan.');
    }

    public function searchDosen(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $dosens = User::whereHas('role', function ($q) {
                $q->where('name', 'dosen');
            })
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%')
                  ->orWhere('program_studi', 'LIKE', '%' . $query . '%')
                  ->orWhereHas('nomorInduk', function ($sq) use ($query) {
                      $sq->where('value', 'LIKE', '%' . $query . '%')
                         ->where('type', 'nidn');
                  });
            })
            ->with('nomorInduk') // Eager load for performance
            ->limit(10)
            ->get();

        // Transform to include accessor data explicitly
        $results = $dosens->map(function ($dosen) {
            return [
                'id' => $dosen->id,
                'name' => $dosen->name,
                'nidn' => $dosen->nidn, // Uses accessor
                'program_studi' => $dosen->program_studi,
                'email' => $dosen->email,
            ];
        });

        return response()->json($results);
    }
}
