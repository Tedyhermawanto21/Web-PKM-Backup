@extends('layouts.app-modern')

@section('title', 'Dashboard Kaprodi')

@section('content')
    <!-- Welcome Banner -->
    <div
        class="relative w-full rounded-3xl overflow-hidden bg-gradient-to-r from-uhamka-900 via-uhamka-800 to-uhamka-900 shadow-2xl mb-8 group">
        <!-- Background Decor -->
        <div
            class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-uhamka-yellow-400 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-blob">
        </div>
        <div
            class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-blue-400 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-blob animation-delay-2000">
        </div>

        <div class="relative z-10 p-8 sm:p-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-4">
                    <span class="w-2 h-2 rounded-full bg-uhamka-yellow-400 animate-pulse"></span>
                    <span class="text-xs font-bold text-white uppercase tracking-wider">Kepala Program Studi</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2 leading-tight">
                    Selamat Datang, <br>
                    <span class="text-uhamka-yellow-400">{{ $user->name }}</span>
                </h1>
                <p class="text-uhamka-100 max-w-lg text-sm sm:text-base leading-relaxed">
                    NIDN: {{ $user->nidn ?? '-' }} | Prodi: {{ $user->program_studi ?? '-' }}
                </p>
            </div>
            <div class="hidden md:block bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/10">
                <div class="text-center">
                    <div class="text-3xl font-bold text-white">-</div>
                    <div class="text-xs text-uhamka-200 uppercase tracking-widest">Total Proposal</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Skema List Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-lg text-slate-900">Daftar Skema PKM</h3>
            <a href="{{ route('kaprodi.pengajuan_kelompok_pkm.index') }}" class="text-sm font-bold text-uhamka-600 hover:text-uhamka-700">Lihat Semua &rarr;</a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($skemaStats as $skema)
                <a href="{{ route('kaprodi.pengajuan_kelompok_pkm.index', ['skema' => $skema->nama]) }}" class="p-4 rounded-xl border border-slate-200 hover:border-{{ $skema->warna ?? 'slate' }}-500 hover:bg-{{ $skema->warna ?? 'slate' }}-50 transition-all flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-full bg-{{ $skema->warna ?? 'slate' }}-100 text-{{ $skema->warna ?? 'slate' }}-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                        {{ str_replace('PKM-', '', $skema->nama) }}
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 group-hover:text-{{ $skema->warna ?? 'slate' }}-700 transition-colors">{{ $skema->nama }}</h4>
                        <p class="text-xs text-slate-500">{{ $skema->label }}</p>
                    </div>
                    <div class="ml-auto">
                        <span class="px-2 py-1 rounded-md bg-white border border-slate-200 text-xs font-bold text-slate-600">
                            {{ $skema->proposals_count }} Proposal
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('kaprodi.kelompok_requests.index') }}"
            class="group p-6 bg-white rounded-2xl border border-slate-200 hover:border-uhamka-500 hover:shadow-lg transition-all flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-uhamka-50 text-uhamka-600 rounded-xl flex items-center justify-center group-hover:bg-uhamka-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 group-hover:text-uhamka-700">Verifikasi Proposal</h3>
                    <p class="text-sm text-slate-500">Periksa dan validasi proposal masuk.</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-slate-300 group-hover:text-uhamka-500 transform group-hover:translate-x-1 transition-all"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>

        <a href="#"
            class="group p-6 bg-white rounded-2xl border border-slate-200 hover:border-uhamka-500 hover:shadow-lg transition-all flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-uhamka-50 text-uhamka-600 rounded-xl flex items-center justify-center group-hover:bg-uhamka-600 group-hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 group-hover:text-uhamka-700">Laporan Kinerja</h3>
                    <p class="text-sm text-slate-500">Unduh rekapitulasi data PKM Prodi.</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-slate-300 group-hover:text-uhamka-500 transform group-hover:translate-x-1 transition-all"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>
    </div>
@endsection
