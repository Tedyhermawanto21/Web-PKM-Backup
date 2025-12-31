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

        // Load kelompoks where user is member or ketua
        $kelompoks = $user->kelompoks()->with(['dosenPembimbing'])->get()
            ->merge($user->kelompokAsKetua()->with(['dosenPembimbing'])->get())
            ->unique('id')
            ->values();

        // If user has at least one kelompok, redirect to its detail page
        if ($kelompoks->count() > 0) {
            return redirect()->route('mahasiswa.kelompoks.show', $kelompoks->first()->id);
        }

        return view('dashboard.mahasiswa.kelompoks.index', compact('kelompoks'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $kelompok = Kelompok::with(['dosenPembimbing'])->findOrFail($id);

        // authorize: must be ketua or anggota
        $isMember = $kelompok->ketua_id === $user->id || $kelompok->anggota()->where('user_id', $user->id)->exists();
        if (! $isMember) abort(403);

        return view('dashboard.mahasiswa.kelompoks.show', compact('kelompok'));
    }
}
