<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProposalController extends Controller
{
    public function index()
    {
        // Ambil proposal yang dosen sudah setuju
        $proposals = Proposal::with(['ketua', 'dosenPembimbing', 'anggota'])
            ->where('status_dosen', 'disetujui')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.kaprodi.pengajuan-kelompok.index', compact('proposals'));
    }

    public function show($id)
    {
        $proposal = Proposal::with(['ketua', 'dosenPembimbing', 'anggota'])->findOrFail($id);
        return view('dashboard.kaprodi.pengajuan-kelompok.show', compact('proposal'));
    }

    public function approve(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        
        $proposal->update([
            'status_kaprodi' => 'disetujui',
            'catatan_kaprodi' => $request->catatan_kaprodi,
            'status' => 'disetujui'
        ]);

        return redirect()->route('kaprodi.pengajuan_kelompok_pkm.index')
            ->with('success', 'Proposal berhasil disetujui');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_kaprodi' => 'required|string'
        ]);

        $proposal = Proposal::findOrFail($id);
        
        $proposal->update([
            'status_kaprodi' => 'ditolak',
            'catatan_kaprodi' => $request->catatan_kaprodi,
            'status' => 'ditolak'
        ]);

        return redirect()->route('kaprodi.pengajuan_kelompok_pkm.index')
            ->with('success', 'Proposal berhasil ditolak');
    }

    /**
     * List kelompok requests for kaprodi verification.
     */
    public function kelompokRequests()
    {
        $kelompoks = Kelompok::with(['ketua', 'anggota', 'dosenPembimbing'])
            ->whereIn('status', ['submitted', 'approved'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.kaprodi.kelompok_requests.index', compact('kelompoks'));
    }

    /**
     * Show kelompok detail for kaprodi.
     */
    public function kelompokShow(Kelompok $kelompok)
    {
        $kelompok->load(['ketua', 'anggota', 'dosenPembimbing']);
        return view('dashboard.kaprodi.kelompok_requests.show', compact('kelompok'));
    }

    /**
     * Kaprodi accepts kelompok (final verification).
     */
    public function acceptKelompok(\Illuminate\Http\Request $request, Kelompok $kelompok)
    {
        // Mark Kaprodi-specific approval only; do not alter `status` which represents dosen acceptance.
        $kelompok->update([
            'status_kaprodi' => 'disetujui',
            'catatan_kaprodi' => $request->input('catatan_kaprodi') ?? null,
        ]);

        // Also update any related Proposal records (match by ketua_id and dosen-approved)
        $relatedProposals = Proposal::where('ketua_id', $kelompok->ketua_id)
            ->where('status_dosen', 'disetujui')
            ->get();

        foreach ($relatedProposals as $proposal) {
            $proposal->update([
                'status_kaprodi' => 'disetujui',
            ]);
        }

        return redirect()->route('kaprodi.kelompok_requests.show', $kelompok->id)->with('success', 'Kelompok diverifikasi dan disetujui.');
    }

    /**
     * Kaprodi rejects kelompok.
     */
    public function rejectKelompok(Request $request, Kelompok $kelompok)
    {
        // Only set Kaprodi-specific rejection; keep `status` (dosen) unchanged.
        $kelompok->update([
            'status_kaprodi' => 'ditolak',
            'catatan_kaprodi' => $request->input('catatan_kaprodi') ?? null,
        ]);

        // Also update related Proposal records to mark Kaprodi rejection (match by ketua_id)
        $relatedProposals = Proposal::where('ketua_id', $kelompok->ketua_id)
            ->where('status_dosen', 'disetujui')
            ->get();

        foreach ($relatedProposals as $proposal) {
            $proposal->update([
                'status_kaprodi' => 'ditolak',
            ]);
        }

        return redirect()->route('kaprodi.kelompok_requests.show', $kelompok->id)->with('success', 'Kelompok ditolak.');
    }
}

