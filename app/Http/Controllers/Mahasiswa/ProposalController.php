<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProposalAnggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $proposals = Proposal::where('ketua_id', $user->id)
            ->with(['dosenPembimbing', 'anggota'])
            ->latest()
            ->get();

        return view('dashboard.mahasiswa.proposals.index', compact('proposals'));
    }

    public function create()
    {
        // Get all dosen (lecturers) for selection
        $dosens = User::whereHas('role', function($query) {
            $query->where('name', 'dosen');
        })->get();

        return view('dashboard.mahasiswa.proposals.create', compact('dosens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_kelompok' => 'required|string|max:255',
            'nama_kelompok' => 'required|string|max:255',
            'skema' => 'required|string|in:PKM-KC,PKM-RE,PKM-GT,PKM-AI,PKM-PM,PKM-K,PKM-VGK',
            'dosen_pembimbing_id' => 'required|exists:users,id',
            'anggota.*.nama' => 'required|string|max:255',
            'anggota.*.nim' => 'required|string|max:50',
            'anggota.*.program_studi' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Create proposal
            $proposal = Proposal::create([
                'judul_kelompok' => $validated['judul_kelompok'],
                'nama_kelompok' => $validated['nama_kelompok'],
                'skema' => $validated['skema'],
                'ketua_id' => Auth::id(),
                'dosen_pembimbing_id' => $validated['dosen_pembimbing_id'],
                'status' => 'menunggu_approval'
            ]);

            // Add ketua as first member
            $user = Auth::user();
            ProposalAnggota::create([
                'proposal_id' => $proposal->id,
                'nama' => $user->name,
                'nim' => $user->nim,
                'program_studi' => $user->program_studi,
                'posisi' => 'ketua'
            ]);

            // Add other members (maximum 4)
            if ($request->has('anggota')) {
                $anggotaData = array_slice($request->anggota, 0, 4); // Limit to 4 members
                foreach ($anggotaData as $anggota) {
                    ProposalAnggota::create([
                        'proposal_id' => $proposal->id,
                        'nama' => $anggota['nama'],
                        'nim' => $anggota['nim'],
                        'program_studi' => $anggota['program_studi'],
                        'posisi' => 'anggota'
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('mahasiswa.proposals.show', $proposal->id)
                ->with('success', 'Proposal berhasil diajukan! Menunggu persetujuan dosen pembimbing.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan proposal: ' . $e->getMessage());
        }
    }

    public function show(Proposal $proposal)
    {
        // Make sure the proposal belongs to the logged in user
        if ($proposal->ketua_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $proposal->load(['dosenPembimbing', 'anggota']);

        return view('dashboard.mahasiswa.proposals.show', compact('proposal'));
    }

    public function edit(Proposal $proposal)
    {
        // Only allow editing if status is draft or ditolak
        if (!in_array($proposal->status, ['draft', 'ditolak'])) {
            return redirect()->route('mahasiswa.proposals.show', $proposal->id)
                ->with('error', 'Proposal tidak dapat diedit karena sudah diajukan atau disetujui.');
        }

        if ($proposal->ketua_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $dosens = User::whereHas('role', function($query) {
            $query->where('name', 'dosen');
        })->get();

        $proposal->load('anggota');

        return view('dashboard.mahasiswa.proposals.edit', compact('proposal', 'dosens'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        if ($proposal->ketua_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($proposal->status, ['draft', 'ditolak'])) {
            return redirect()->route('mahasiswa.proposals.show', $proposal->id)
                ->with('error', 'Proposal tidak dapat diedit karena sudah diajukan atau disetujui.');
        }

        $validated = $request->validate([
            'judul_kelompok' => 'required|string|max:255',
            'nama_kelompok' => 'required|string|max:255',
            'skema' => 'required|string|in:PKM-KC,PKM-RE,PKM-GT,PKM-AI,PKM-PM,PKM-K,PKM-VGK',
            'dosen_pembimbing_id' => 'required|exists:users,id',
            'anggota.*.nama' => 'required|string|max:255',
            'anggota.*.nim' => 'required|string|max:50',
            'anggota.*.program_studi' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $proposal->update([
                'judul_kelompok' => $validated['judul_kelompok'],
                'nama_kelompok' => $validated['nama_kelompok'],
                'skema' => $validated['skema'],
                'dosen_pembimbing_id' => $validated['dosen_pembimbing_id'],
                'status' => 'menunggu_approval',
                'catatan_penolakan' => null // Clear rejection notes
            ]);

            // Delete old anggota (except ketua) and recreate
            ProposalAnggota::where('proposal_id', $proposal->id)
                ->where('posisi', 'anggota')
                ->delete();

            // Add new members
            if ($request->has('anggota')) {
                $anggotaData = array_slice($request->anggota, 0, 4);
                foreach ($anggotaData as $anggota) {
                    ProposalAnggota::create([
                        'proposal_id' => $proposal->id,
                        'nama' => $anggota['nama'],
                        'nim' => $anggota['nim'],
                        'program_studi' => $anggota['program_studi'],
                        'posisi' => 'anggota'
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('mahasiswa.proposals.show', $proposal->id)
                ->with('success', 'Proposal berhasil diperbarui dan diajukan kembali!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui proposal: ' . $e->getMessage());
        }
    }

    public function destroy(Proposal $proposal)
    {
        if ($proposal->ketua_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow deletion if status is draft or ditolak
        if (!in_array($proposal->status, ['draft', 'ditolak'])) {
            return redirect()->route('mahasiswa.proposals.index')
                ->with('error', 'Proposal tidak dapat dihapus karena sudah diajukan atau disetujui.');
        }

        $proposal->delete();

        return redirect()->route('mahasiswa.proposals.index')
            ->with('success', 'Proposal berhasil dihapus.');
    }
}
