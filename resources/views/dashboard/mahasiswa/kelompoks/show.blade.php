@extends('layouts.app-modern')

@section('title', 'Detail Kelompok PKM')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Kelompok PKM</h1>
            <p class="text-slate-500">Informasi lengkap tentang kelompok PKM Anda.</p>
        </div>
        <a href="{{ route('mahasiswa.kelompoks.index') }}"
            class="inline-flex items-center px-4 py-2 bg-white text-slate-700 text-sm font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm hover:shadow-md">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Status Badge Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center mb-8">
        <h4 class="text-lg font-bold text-slate-900 mb-6">Status Kelompok</h4>

        <div class="inline-flex flex-col items-center">
            <span
                class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold bg-green-100 text-green-700 mb-4">
                <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Aktif
            </span>
            <p class="text-green-600 font-bold">Kelompok PKM Anda aktif dan siap untuk proses pembimbingan</p>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Informasi Kelompok -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-full">
            <div class="bg-uhamka-900 px-6 py-4 border-b border-uhamka-800">
                <h6 class="font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Informasi Kelompok
                </h6>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Nama Kelompok</span>
                        <p class="text-slate-800 font-semibold">{{ $kelompok->nama_kelompok }}</p>
                    </div>
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Judul PKM</span>
                        <p class="text-slate-800 font-semibold leading-relaxed">{{ $kelompok->judul_pkm }}</p>
                    </div>
                    @if ($kelompok->jenis_pkm)
                        <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                            <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Skema</span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $kelompok->jenis_pkm }}
                            </span>
                        </div>
                    @endif
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Dosen Pembimbing</span>
                        @if ($kelompok->dosenPembimbing)
                            <div class="flex items-center gap-3 mt-1">
                                <div
                                    class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-sm font-bold text-slate-600">
                                    {{ substr($kelompok->dosenPembimbing->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-slate-900 font-bold">{{ $kelompok->dosenPembimbing->name }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ $kelompok->dosenPembimbing->program_studi ?? 'Teknik Informatika' }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-slate-500 italic">Belum ditentukan</p>
                        @endif
                    </div>
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Status</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            {{ ucfirst($kelompok->status) }}
                        </span>
                    </div>
                    @if ($kelompok->deskripsi)
                        <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                            <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Deskripsi</span>
                            <p class="text-slate-800 leading-relaxed">{{ $kelompok->deskripsi }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Daftar Anggota -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-full">
            <div class="bg-uhamka-900 px-6 py-4 border-b border-uhamka-800">
                <h6 class="font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Anggota Kelompok
                    @php
                        $totalAnggota = 1; // ketua
                        $totalAnggota += $kelompok->anggota ? $kelompok->anggota->count() : 0;
                        $freeMembers = \App\Models\KelompokAnggota::where('kelompok_id', $kelompok->id)->count();
                        $totalAnggota += $freeMembers;
                    @endphp
                    <span class="ml-2 px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ $totalAnggota }} Orang</span>
                </h6>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Ketua -->
                    @if ($kelompok->ketua)
                        <div
                            class="flex items-center p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                            <div
                                class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold mr-4">
                                1
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span
                                        class="px-2 py-0.5 bg-uhamka-100 text-uhamka-700 text-[10px] font-bold uppercase rounded-md">Ketua</span>
                                    <h6 class="font-bold text-slate-900">{{ $kelompok->ketua->name }}</h6>
                                </div>
                                <p class="text-xs text-slate-500">{{ $kelompok->ketua->nim ?? '-' }} •
                                    {{ $kelompok->ketua->program_studi ?? 'Teknik Informatika' }}</p>
                            </div>
                        </div>
                    @endif

                    @php $memberIndex = 2; @endphp

                    <!-- Anggota dari relasi User -->
                    @if ($kelompok->anggota && $kelompok->anggota->count() > 0)
                        @foreach ($kelompok->anggota as $anggota)
                            <div
                                class="flex items-center p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                                <div
                                    class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold mr-4">
                                    {{ $memberIndex }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] font-bold uppercase rounded-md">Anggota</span>
                                        <h6 class="font-bold text-slate-900">{{ $anggota->name }}</h6>
                                    </div>
                                    <p class="text-xs text-slate-500">{{ $anggota->nim ?? '-' }} •
                                        {{ $anggota->program_studi ?? 'Teknik Informatika' }}</p>
                                </div>
                            </div>
                            @php $memberIndex++; @endphp
                        @endforeach
                    @endif

                    <!-- Anggota dari tabel KelompokAnggota -->
                    @php
                        $freeMembers = \App\Models\KelompokAnggota::where('kelompok_id', $kelompok->id)->get();
                    @endphp
                    @foreach ($freeMembers as $freeMember)
                        <div
                            class="flex items-center p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                            <div
                                class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold mr-4">
                                {{ $memberIndex }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span
                                        class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] font-bold uppercase rounded-md">Anggota</span>
                                    <h6 class="font-bold text-slate-900">{{ $freeMember->nama }}</h6>
                                </div>
                                <p class="text-xs text-slate-500">{{ $freeMember->nim }} •
                                    {{ $freeMember->program_studi }}</p>
                            </div>
                        </div>
                        @php $memberIndex++; @endphp
                    @endforeach

                    @if ($totalAnggota == 1)
                        <div class="text-center py-4 text-slate-500">
                            <p class="text-sm">Tidak ada anggota lain selain ketua.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
