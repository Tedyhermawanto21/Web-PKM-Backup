<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProposalApprovalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $proposals = Proposal::where('dosen_pembimbing_id', $user->id)
            ->with(['ketua', 'anggota'])
            ->latest()
            ->get();

        return view('dashboard.dosen.proposals.index', compact('proposals'));
    }

    public function show(Proposal $proposal)
    {
        // Make sure the proposal is assigned to this dosen
        if ($proposal->dosen_pembimbing_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $proposal->load(['ketua', 'anggota']);

        return view('dashboard.dosen.proposals.show', compact('proposal'));
    }

    public function approve(Proposal $proposal)
    {
        if ($proposal->dosen_pembimbing_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($proposal->status !== 'menunggu_approval') {
            return redirect()->back()
                ->with('error', 'Proposal tidak dapat disetujui karena statusnya bukan menunggu approval.');
        }

        $proposal->update([
            'status' => 'disetujui',
            'catatan_penolakan' => null
        ]);

        return redirect()->route('dosen.proposals.show', $proposal->id)
            ->with('success', 'Proposal berhasil disetujui! Anda sekarang menjadi dosen pembimbing kelompok ini.');
    }

    public function reject(Request $request, Proposal $proposal)
    {
        if ($proposal->dosen_pembimbing_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($proposal->status !== 'menunggu_approval') {
            return redirect()->back()
                ->with('error', 'Proposal tidak dapat ditolak karena statusnya bukan menunggu approval.');
        }

        $validated = $request->validate([
            'catatan_penolakan' => 'required|string|max:1000'
        ]);

        $proposal->update([
            'status' => 'ditolak',
            'catatan_penolakan' => $validated['catatan_penolakan']
        ]);

        return redirect()->route('dosen.proposals.show', $proposal->id)
            ->with('success', 'Proposal telah ditolak. Mahasiswa dapat mengajukan proposal baru dengan dosen pembimbing yang lain.');
    }
}
