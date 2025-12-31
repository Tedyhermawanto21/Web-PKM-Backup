@extends('layouts.app-modern')

@section('title', 'Detail Kelompok PKM')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">Detail Kelompok PKM</h1>
            <p class="text-slate-500">Informasi lengkap tentang kelompok PKM Anda.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h6 class="font-bold mb-4">Informasi Kelompok</h6>
            <div class="space-y-3">
                <div>
                    <span class="text-xs text-slate-400 uppercase">Nama Kelompok</span>
                    <div class="font-semibold">{{ $kelompok->nama_kelompok }}</div>
                </div>
                <div>
                    <span class="text-xs text-slate-400 uppercase">Judul PKM</span>
                    <div class="font-semibold">{{ $kelompok->judul_pkm }}</div>
                </div>
                <div>
                    <span class="text-xs text-slate-400 uppercase">Skema</span>
                    <div class="inline-block px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">
                        {{ $kelompok->jenis_pkm }}</div>
                </div>
                <div>
                    <span class="text-xs text-slate-400 uppercase">Dosen Pembimbing</span>
                    <div class="font-semibold">{{ $kelompok->dosenPembimbing->name ?? 'Belum dipilih' }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h6 class="font-bold mb-4">Anggota Kelompok</h6>
            <div class="space-y-3">
                @php
                    // load anggota via existing relations and kelompok_anggota table
                    $pivot = $kelompok->anggota()->get();
                    $free = \App\Models\KelompokAnggota::where('kelompok_id', $kelompok->id)->get();
                @endphp

                @foreach ($pivot as $i => $m)
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center">
                            {{ $i + 1 }}</div>
                        <div>
                            <div class="font-bold">{{ $m->name ?? ($m->pivot->nama ?? '—') }}</div>
                            <div class="text-xs text-slate-500">{{ $m->nim ?? ($m->pivot->nim ?? '') }} •
                                {{ $m->program_studi ?? ($m->pivot->program_studi ?? '') }}</div>
                        </div>
                    </div>
                @endforeach

                @foreach ($free as $f)
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center">—</div>
                        <div>
                            <div class="font-bold">{{ $f->nama }}</div>
                            <div class="text-xs text-slate-500">{{ $f->nim }} • {{ $f->program_studi }}</div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

@endsection
