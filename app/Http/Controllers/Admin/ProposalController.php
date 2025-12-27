<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

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

        return view('dashboard.admin.proposals.show', compact('proposal'));
    }

    public function approve(Request $request, Proposal $proposal)
    {
        $proposal->update([
            'status_admin' => 'disetujui',
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->route('admin.proposals.index')
            ->with('success', 'Proposal berhasil disetujui!');
    }

    public function reject(Request $request, Proposal $proposal)
    {
        $request->validate([
            'catatan_admin' => 'required|string|min:10'
        ], [
            'catatan_admin.required' => 'Catatan wajib diisi untuk penolakan',
            'catatan_admin.min' => 'Catatan minimal 10 karakter'
        ]);

        $proposal->update([
            'status_admin' => 'ditolak',
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->route('admin.proposals.index')
            ->with('success', 'Proposal ditolak. Mahasiswa dapat mengupload ulang.');
    }
}
