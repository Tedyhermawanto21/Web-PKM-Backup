<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\ProposalAnggota;
use App\Models\User;
use App\Models\Kelompok;
use App\Models\KelompokUser;
use App\Models\KelompokAnggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProposalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Load Kelompok entries for the user and adapt fields so the existing
        // proposals index view can render them without changes.
        $kelompoks = Kelompok::where('ketua_id', $user->id)
            ->with(['dosenPembimbing', 'anggota'])
            ->latest()
            ->get();

        // Map Kelompok fields to the properties expected by the proposals view
        $proposals = $kelompoks->map(function ($k) {
            // attach compatible attribute names
            $k->nama_kelompok = $k->nama_kelompok;
            $k->judul_kelompok = $k->judul_pkm;
            $k->skema = $k->jenis_pkm;
            // Map Kelompok.status to the proposal status values used by the views
            // so that 'submitted'/'review' appear as pending (menunggu_approval, yellow)
            switch ($k->status) {
                case 'submitted':
                case 'review':
                    $k->status = 'menunggu_approval';
                    break;
                case 'approved':
                    $k->status = 'disetujui';
                    break;
                case 'rejected':
                    $k->status = 'ditolak';
                    break;
                default:
                    $k->status = $k->status; // keep 'draft' or other
            }
            $k->status_dosen = $k->status === 'disetujui' ? 'disetujui' : ($k->status === 'ditolak' ? 'ditolak' : 'menunggu');
            // Preserve kaprodi status from Kelompok (if present) so mahasiswa sees Kaprodi decision
            $k->status_kaprodi = $k->status_kaprodi ?? 'menunggu';
            return $k;
        });

        return view('dashboard.mahasiswa.pengajuan-kelompok.index', compact('proposals'));
    }

    public function create()
    {
        // Get all dosen (lecturers) for selection
        $dosens = User::whereHas('role', function($query) {
            $query->where('name', 'dosen');
        })->get();

        return view('dashboard.mahasiswa.pengajuan-kelompok.create', compact('dosens'));
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
            // Create a Kelompok entry instead of a Proposal
            $kelompok = Kelompok::create([
                'nama_kelompok' => $validated['nama_kelompok'],
                'judul_pkm' => $validated['judul_kelompok'],
                'jenis_pkm' => $validated['skema'],
                'deskripsi' => null,
                'ketua_id' => Auth::id(),
                'dosen_pembimbing_id' => $validated['dosen_pembimbing_id'],
                'status' => 'submitted'
            ]);

            // Attach ketua (current user) to kelompok_user pivot, include basic info
            $user = Auth::user();
            try {
                $kelompok->anggota()->attach($user->id, [
                    'posisi' => 'ketua',
                ]);
            } catch (\Exception $e) {
                // ignore duplicate attach
            }

            // Attach other anggota if they exist and correspond to existing users (lookup by NIM)
            $skipped = 0;
            if ($request->has('anggota')) {
                $anggotaData = array_slice($request->anggota, 0, 4); // Limit to 4 members
                foreach ($anggotaData as $anggota) {
                    // find user by nim
                    $memberUser = User::where('nim', $anggota['nim'])->first();
                    if ($memberUser && $memberUser->id !== $user->id) {
                        // attach registered user with pivot info
                        try {
                            $kelompok->anggota()->attach($memberUser->id, [
                                'posisi' => 'anggota',
                            ]);
                        } catch (\Exception $e) {
                            // ignore duplicate attach
                        }
                    } else {
                        // store free-form anggota into kelompok_anggota table when user not found
                        \App\Models\KelompokAnggota::create([
                            'kelompok_id' => $kelompok->id,
                            'user_id' => null,
                            'posisi' => 'anggota',
                            'nama' => $anggota['nama'] ?? null,
                            'nim' => $anggota['nim'] ?? null,
                            'program_studi' => $anggota['program_studi'] ?? null,
                        ]);
                        $skipped++;
                    }
                }
            }

            DB::commit();

            $message = 'Kelompok PKM berhasil diajukan.';
            if ($skipped > 0) {
                $message .= " Beberapa anggota tidak ditemukan di sistem dan di-skip: {$skipped} anggota.";
            }

            return redirect()->route('mahasiswa.pengajuan_kelompok_pkm.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan proposal: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = Auth::user();

        // Try to find a Proposal first
        $proposal = Proposal::with(['dosenPembimbing', 'anggota'])->find($id);
        if ($proposal) {
            if ($proposal->ketua_id !== $user->id) {
                abort(403, 'Unauthorized action.');
            }

            return view('dashboard.mahasiswa.pengajuan-kelompok.show', compact('proposal'));
        }

        // If not a Proposal, try Kelompok
        $kelompok = Kelompok::with(['dosenPembimbing', 'anggota'])->find($id);
        if (! $kelompok) {
            abort(404);
        }

        if ($kelompok->ketua_id !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // Load anggota from pivot (registered users)
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

        // Load free-form anggota from kelompok_anggota
        $freeRows = \App\Models\KelompokAnggota::where('kelompok_id', $kelompok->id)->get();
        $anggotaFree = $freeRows->map(function ($row) {
            return (object) [
                'nama' => $row->nama,
                'nim' => $row->nim,
                'program_studi' => $row->program_studi,
                'posisi' => $row->posisi ?? 'anggota'
            ];
        });

        // Merge registered and free-form anggota
        $anggota = $anggotaRegistered->merge($anggotaFree);

        // Add compatible attributes expected by the view
        $kelompok->nama_kelompok = $kelompok->nama_kelompok;
        $kelompok->judul_kelompok = $kelompok->judul_pkm;
        $kelompok->skema = $kelompok->jenis_pkm;
        $kelompok->anggota = $anggota;

        // Map kelompok status to proposal status so that view shows pending in yellow
        switch ($kelompok->status) {
            case 'submitted':
            case 'review':
                $kelompok->status = 'menunggu_approval';
                break;
            case 'approved':
                $kelompok->status = 'disetujui';
                break;
            case 'rejected':
                $kelompok->status = 'ditolak';
                break;
            default:
                // keep existing (e.g., 'draft')
        }

        // Preserve Kaprodi status for the mahasiswa view
        $kelompok->status_kaprodi = $kelompok->status_kaprodi ?? 'menunggu';

        $proposal = $kelompok; // alias so view can use $proposal

        return view('dashboard.mahasiswa.pengajuan-kelompok.show', compact('proposal'));
    }

    public function edit(Proposal $proposal)
    {
        // Only allow editing if status is draft or ditolak
        if (!in_array($proposal->status, ['draft', 'ditolak'])) {
            return redirect()->route('mahasiswa.pengajuan_kelompok_pkm.show', $proposal->id)
                ->with('error', 'Proposal tidak dapat diedit karena sudah diajukan atau disetujui.');
        }

        if ($proposal->ketua_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $dosens = User::whereHas('role', function($query) {
            $query->where('name', 'dosen');
        })->get();

        $proposal->load('anggota');

        return view('dashboard.mahasiswa.pengajuan-kelompok.edit', compact('proposal', 'dosens'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        if ($proposal->ketua_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($proposal->status, ['draft', 'ditolak'])) {
            return redirect()->route('mahasiswa.pengajuan_kelompok_pkm.show', $proposal->id)
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

            return redirect()->route('mahasiswa.pengajuan_kelompok_pkm.show', $proposal->id)
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
            return redirect()->route('mahasiswa.pengajuan_kelompok_pkm.index')
                ->with('error', 'Proposal tidak dapat dihapus karena sudah diajukan atau disetujui.');
        }

        $proposal->delete();

        return redirect()->route('mahasiswa.pengajuan_kelompok_pkm.index')
            ->with('success', 'Proposal berhasil dihapus.');
    }

    public function uploadForm()
    {
        $proposals = Proposal::where('ketua_id', Auth::id())
            ->where('status_dosen', 'disetujui')
            ->where('status_kaprodi', 'disetujui')
            ->whereNull('file_proposal')
            ->with(['dosenPembimbing'])
            ->get();

        return redirect()->route('mahasiswa.upload.create');
    }

    public function uploadStore(Request $request)
    {
        $request->validate([
            'proposal_id' => 'required|exists:proposals,id',
            'file_proposal' => 'required|file|mimes:pdf,doc,docx|max:5120'
        ]);

        $proposal = Proposal::findOrFail($request->proposal_id);

        // Verify ownership
        if ($proposal->ketua_id !== Auth::id()) {
            abort(403);
        }

        // Handle file upload
        if ($request->hasFile('file_proposal')) {
            $file = $request->file('file_proposal');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('proposals', $fileName, 'public');

            $proposal->update([
                'file_proposal' => $filePath,
                'status_admin' => 'menunggu_alokasi'
            ]);

            return redirect()->route('mahasiswa.upload.index')
                ->with('success', 'File proposal berhasil diupload dan akan direview oleh admin.');
        }

        return redirect()->back()->with('error', 'Gagal mengupload file.');
    }
}
