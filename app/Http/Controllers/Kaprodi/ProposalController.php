<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index()
    {
        // Ambil proposal yang dosen sudah setuju
        $proposals = Proposal::with(['ketua', 'dosenPembimbing', 'anggota'])
            ->where('status_dosen', 'disetujui')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.kaprodi.proposals.index', compact('proposals'));
    }

    public function show($id)
    {
        $proposal = Proposal::with(['ketua', 'dosenPembimbing', 'anggota'])->findOrFail($id);
        return view('dashboard.kaprodi.proposals.show', compact('proposal'));
    }

    public function approve(Request $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        
        $proposal->update([
            'status_kaprodi' => 'disetujui',
            'catatan_kaprodi' => $request->catatan_kaprodi,
            'status' => 'disetujui'
        ]);

        return redirect()->route('kaprodi.proposals.index')
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

        return redirect()->route('kaprodi.proposals.index')
            ->with('success', 'Proposal berhasil ditolak');
    }
}

