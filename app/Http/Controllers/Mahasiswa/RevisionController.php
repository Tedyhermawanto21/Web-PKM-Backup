<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevisionController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Proposals that require revision or were rejected by admin
        $proposals = Proposal::where('ketua_id', $userId)
            ->where(function ($q) {
                $q->where('status_admin', 'ditolak')
                  ->orWhere('revision_stage', '>', 0);
            })
            ->with(['dosenPembimbing', 'anggota'])
            ->latest()
            ->get();

        // Find if any revision schedule is currently ongoing
        $revisionSchedule = Schedule::whereIn('type', [Schedule::TYPE_REVISI_1, Schedule::TYPE_REVISI_2, Schedule::TYPE_REVISI_3])
            ->active()
            ->ongoing()
            ->get();

        return view('dashboard.mahasiswa.revisi.index', compact('proposals', 'revisionSchedule'));
    }
}
