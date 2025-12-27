<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function index()
    {
        // Get proposals that have been uploaded (have file_proposal)
        $uploadedProposals = Proposal::where('ketua_id', Auth::id())
            ->whereNotNull('file_proposal')
            ->with(['dosenPembimbing', 'anggota'])
            ->latest()
            ->get();

        // Check if upload schedule is active
        $uploadSchedule = Schedule::ofType(Schedule::TYPE_UPLOAD_PROPOSAL)
            ->active()
            ->ongoing()
            ->first();

        return view('dashboard.mahasiswa.upload.index', compact('uploadedProposals', 'uploadSchedule'));
    }

    public function create()
    {
        // Check if upload schedule is active
        $uploadSchedule = Schedule::ofType(Schedule::TYPE_UPLOAD_PROPOSAL)
            ->active()
            ->ongoing()
            ->first();

        if (!$uploadSchedule) {
            return redirect()->route('mahasiswa.upload.index')
                ->with('error', 'Jadwal upload proposal belum dibuka atau sudah ditutup.');
        }

        // Get proposals ready for upload (approved by dosen and kaprodi, no file yet)
        $proposals = Proposal::where('ketua_id', Auth::id())
            ->where('status_dosen', 'disetujui')
            ->where('status_kaprodi', 'disetujui')
            ->where(function($query) {
                $query->whereNull('file_proposal')
                      ->orWhere('file_proposal', '');
            })
            ->with(['dosenPembimbing'])
            ->get();

        return view('dashboard.mahasiswa.upload.create', compact('proposals', 'uploadSchedule'));
    }

    public function store(Request $request)
    {
        // Check if upload schedule is active
        $uploadSchedule = Schedule::ofType(Schedule::TYPE_UPLOAD_PROPOSAL)
            ->active()
            ->ongoing()
            ->first();

        if (!$uploadSchedule) {
            return redirect()->back()->with('error', 'Jadwal upload proposal belum dibuka atau sudah ditutup.');
        }

        $request->validate([
            'proposal_id' => 'required|exists:proposals,id',
            'file_proposal' => 'required|file|mimes:pdf,doc,docx|max:5120'
        ]);

        $proposal = Proposal::findOrFail($request->proposal_id);

        // Verify ownership
        if ($proposal->ketua_id !== Auth::id()) {
            abort(403);
        }

        // Verify approval status
        if ($proposal->status_dosen !== 'disetujui' || $proposal->status_kaprodi !== 'disetujui') {
            return redirect()->back()->with('error', 'Proposal belum disetujui oleh Dosen dan Kaprodi.');
        }

        // Verify no file uploaded yet
        if ($proposal->file_proposal) {
            return redirect()->back()->with('error', 'Proposal sudah diupload sebelumnya.');
        }

        // Handle file upload
        if ($request->hasFile('file_proposal')) {
            $file = $request->file('file_proposal');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('proposals', $fileName, 'public');

            $proposal->update([
                'file_proposal' => $filePath,
                'status_admin' => 'menunggu'
            ]);

            return redirect()->route('mahasiswa.upload.index')
                ->with('success', 'File proposal berhasil diupload dan akan direview oleh admin.');
        }

        return redirect()->back()->with('error', 'Gagal mengupload file.');
    }

    public function show(Proposal $upload)
    {
        // Verify ownership
        if ($upload->ketua_id !== Auth::id()) {
            abort(403);
        }

        $proposal = $upload->load(['dosenPembimbing', 'ketua', 'anggota']);

        return view('dashboard.mahasiswa.upload.show', compact('proposal'));
    }

    public function edit(Proposal $upload)
    {
        // Verify ownership
        if ($upload->ketua_id !== Auth::id()) {
            abort(403);
        }

        // Only allow edit if rejected by admin or in revision stage
        if ($upload->status_admin !== 'ditolak' && $upload->revision_stage == 0) {
            return redirect()->route('mahasiswa.upload.index')
                ->with('error', 'Proposal ini tidak dapat diedit.');
        }

        // Check if revision schedule is active (if in revision stage)
        if ($upload->revision_stage > 0) {
            $revisionType = 'revisi_' . $upload->revision_stage;
            $revisionSchedule = Schedule::ofType($revisionType)
                ->active()
                ->ongoing()
                ->first();

            if (!$revisionSchedule) {
                return redirect()->route('mahasiswa.upload.index')
                    ->with('error', 'Jadwal revisi tahap ' . $upload->revision_stage . ' belum dibuka atau sudah ditutup.');
            }
        }

        $proposal = $upload->load(['dosenPembimbing']);

        return view('dashboard.mahasiswa.upload.edit', compact('proposal'));
    }

    public function update(Request $request, Proposal $upload)
    {
        // Verify ownership
        if ($upload->ketua_id !== Auth::id()) {
            abort(403);
        }

        // Only allow update if rejected by admin or in revision stage
        if ($upload->status_admin !== 'ditolak' && $upload->revision_stage == 0) {
            return redirect()->route('mahasiswa.upload.index')
                ->with('error', 'Proposal ini tidak dapat diupdate.');
        }

        // Check if revision schedule is active (if in revision stage)
        if ($upload->revision_stage > 0) {
            $revisionType = 'revisi_' . $upload->revision_stage;
            $revisionSchedule = Schedule::ofType($revisionType)
                ->active()
                ->ongoing()
                ->first();

            if (!$revisionSchedule) {
                return redirect()->back()
                    ->with('error', 'Jadwal revisi tahap ' . $upload->revision_stage . ' belum dibuka atau sudah ditutup.');
            }
        }

        $request->validate([
            'file_proposal' => 'required|file|mimes:pdf,doc,docx|max:5120'
        ]);

        // Handle file upload
        if ($request->hasFile('file_proposal')) {
            // Delete old file
            if ($upload->file_proposal) {
                Storage::disk('public')->delete($upload->file_proposal);
            }

            $file = $request->file('file_proposal');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('proposals', $fileName, 'public');

            $upload->update([
                'file_proposal' => $filePath,
                'status_admin' => 'menunggu',
                'catatan_admin' => null
            ]);

            return redirect()->route('mahasiswa.upload.index')
                ->with('success', 'File proposal berhasil diupload ulang dan akan direview oleh admin.');
        }

        return redirect()->back()->with('error', 'Gagal mengupload file.');
    }
}
