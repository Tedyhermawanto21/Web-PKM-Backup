@extends('layouts.app-modern')

@section('title', 'Dashboard Kaprodi')

@section('content')
<!-- Welcome Banner -->
<div class="relative w-full rounded-3xl overflow-hidden bg-gradient-to-r from-uhamka-900 via-uhamka-800 to-uhamka-900 shadow-2xl mb-8 group">
    <!-- Background Decor -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-uhamka-yellow-400 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-blue-400 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

    <div class="relative z-10 p-8 sm:p-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-4">
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

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md hover:border-uhamka-200 transition-all group">
        <div class="w-14 h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
            📊
        </div>
        <div>
            <div class="text-slate-500 text-xs font-bold uppercase">Total Proposal</div>
            <div class="text-2xl font-bold text-slate-900">-</div>
            <div class="text-xs text-slate-400">Data Prodi</div>
        </div>
    </div>
    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md hover:border-uhamka-200 transition-all group">
        <div class="w-14 h-14 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
            👨‍🏫
        </div>
        <div>
            <div class="text-slate-500 text-xs font-bold uppercase">Dosen Pembimbing</div>
            <div class="text-2xl font-bold text-slate-900">-</div>
            <div class="text-xs text-slate-400">Aktif Membimbing</div>
        </div>
    </div>
</div>

<!-- Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <a href="{{ route('kaprodi.proposals.index') }}" class="group p-6 bg-white rounded-2xl border border-slate-200 hover:border-uhamka-500 hover:shadow-lg transition-all flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-uhamka-50 text-uhamka-600 rounded-xl flex items-center justify-center group-hover:bg-uhamka-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 group-hover:text-uhamka-700">Verifikasi Proposal</h3>
                <p class="text-sm text-slate-500">Periksa dan validasi proposal masuk.</p>
            </div>
        </div>
        <svg class="w-5 h-5 text-slate-300 group-hover:text-uhamka-500 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </a>

    <a href="#" class="group p-6 bg-white rounded-2xl border border-slate-200 hover:border-uhamka-500 hover:shadow-lg transition-all flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-uhamka-50 text-uhamka-600 rounded-xl flex items-center justify-center group-hover:bg-uhamka-600 group-hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-slate-900 group-hover:text-uhamka-700">Laporan Kinerja</h3>
                <p class="text-sm text-slate-500">Unduh rekapitulasi data PKM Prodi.</p>
            </div>
        </div>
        <svg class="w-5 h-5 text-slate-300 group-hover:text-uhamka-500 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </a>
</div>
@endsection
