<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelompokController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Load kelompoks where user is member or ketua AND approved by kaprodi
        $kelompoksAsMember = $user->kelompoks()
            ->with(['dosenPembimbing'])
            ->where('status', 'approved')
            ->where('status_kaprodi', 'disetujui')
            ->get();
            
        $kelompoksAsKetua = $user->kelompokAsKetua()
            ->with(['dosenPembimbing'])
            ->where('status', 'approved') 
            ->where('status_kaprodi', 'disetujui')
            ->get();

        $kelompoks = $kelompoksAsMember->merge($kelompoksAsKetua)
            ->unique('id')
            ->values();

        return view('dashboard.mahasiswa.kelompoks.index', compact('kelompoks'));
    }

    public function show(Kelompok $kelompok)
    {
        $user = Auth::user();
        
        // Load relationships
        $kelompok->load(['dosenPembimbing', 'anggota', 'ketua']);

        // authorize: must be ketua or anggota
        $isMember = $kelompok->ketua_id === $user->id || $kelompok->anggota()->where('user_id', $user->id)->exists();
        if (! $isMember) abort(403);

        return view('dashboard.mahasiswa.kelompoks.show', compact('kelompok'));
    }
}
