<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Kelompok;
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

    /**
     * Show kelompok requests where students requested this dosen as pembimbing.
     */
    public function kelompokRequests()
    {
        $user = Auth::user();

        $kelompoks = Kelompok::with(['ketua', 'anggota'])
            ->where('dosen_pembimbing_id', $user->id)
            ->where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.dosen.kelompok_requests.index', compact('kelompoks'));
    }

    /**
     * Show single kelompok request detail.
     */
    public function kelompokShow(Kelompok $kelompok)
    {
        if ($kelompok->dosen_pembimbing_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $kelompok->load(['ketua', 'anggota', 'dosenPembimbing']);
        
        // Use helper to get unified anggota list
        $kelompok->anggota = $kelompok->getAllAnggota();

        return view('dashboard.dosen.kelompok_requests.show', compact('kelompok'));
    }

    /**
     * Accept a kelompok request (set status to approved).
     */
    public function acceptKelompok(Kelompok $kelompok)
    {
        if ($kelompok->dosen_pembimbing_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $kelompok->update([
            'status' => 'approved'
        ]);

        return redirect()->route('dosen.bimbingan_mahasiswa.index')->with('success', 'Kelompok berhasil diterima! Sekarang Anda dapat membimbing kelompok tersebut.');
    }

    /**
     * Reject a kelompok request (set status to rejected and clear dosen_pembimbing_id).
     */
    public function rejectKelompok(Request $request, Kelompok $kelompok)
    {
        if ($kelompok->dosen_pembimbing_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $note = $request->input('note');

        $kelompok->update([
            'status' => 'rejected',
            'dosen_pembimbing_id' => null
        ]);

        return redirect()->route('dosen.kelompok_requests.show', $kelompok->id)->with('success', 'Kelompok ditolak.');
    }

    public function show(Proposal $proposal)
    {
        // Make sure the proposal is assigned to this dosen
        if ($proposal->dosen_pembimbing_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $proposal->load(['ketua', 'anggota']);

        // Fetch associated Kelompok to get consistent member list
        $kelompok = \App\Models\Kelompok::where('ketua_id', $proposal->ketua_id)->latest()->first();
        $allAnggota = $kelompok ? $kelompok->getAllAnggota() : collect([]);

        return view('dashboard.dosen.proposals.show', compact('proposal', 'allAnggota'));
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

        return redirect()->route('dosen.pengajuan_kelompok_pkm.show', $proposal->id)
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

        return redirect()->route('dosen.pengajuan_kelompok_pkm.show', $proposal->id)
            ->with('success', 'Proposal telah ditolak. Mahasiswa dapat mengajukan proposal baru dengan dosen pembimbing yang lain.');
    }

    /**
     * Show all kelompoks that this dosen is mentoring (approved to be their pembimbing).
     */
    public function bimbinganMahasiswa()
    {
        $user = Auth::user();

        // Get all kelompoks where this dosen is the pembimbing and status is approved
        $kelompoks = Kelompok::with(['ketua', 'anggota'])
            ->where('dosen_pembimbing_id', $user->id)
            ->where('status', 'approved')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('dashboard.dosen.bimbingan_mahasiswa.index', compact('kelompoks'));
    }

    /**
     * Show detail of a kelompok that this dosen is mentoring.
     */
    public function showBimbingan(Kelompok $kelompok)
    {
        // Ensure this dosen is the pembimbing
        if ($kelompok->dosen_pembimbing_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $kelompok->load(['ketua', 'anggota', 'kelompokAnggota', 'dosenPembimbing']);
        $allAnggota = $kelompok->getAllAnggota();

        return view('dashboard.dosen.bimbingan_mahasiswa.show', compact('kelompok', 'allAnggota'));
    }
}
