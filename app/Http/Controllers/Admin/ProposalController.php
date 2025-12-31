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
    public function index()
    {
        $proposals = Proposal::whereNotNull('file_proposal')
            ->with(['ketua', 'dosenPembimbing'])
            ->latest()
            ->get();

        return view('dashboard.admin.proposals.index', compact('proposals'));
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
}
