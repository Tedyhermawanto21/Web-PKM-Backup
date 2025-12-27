@extends('layouts.app-modern')

@section('title', 'Dashboard Dosen')

@section('content')
<!-- Welcome Banner -->
<div class="relative w-full rounded-3xl overflow-hidden bg-gradient-to-r from-uhamka-900 via-uhamka-800 to-uhamka-900 shadow-2xl mb-8 group">
    <!-- Background Decor -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-uhamka-yellow-400 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-blob"></div>

    <div class="relative z-10 p-8 sm:p-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-4">
                <span class="w-2 h-2 rounded-full bg-uhamka-yellow-400 animate-pulse"></span>
                <span class="text-xs font-bold text-white uppercase tracking-wider">Dosen Pembimbing</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2 leading-tight">
                Selamat Datang, <br>
                <span class="text-uhamka-yellow-400">{{ $user->name }}</span>
            </h1>
            <p class="text-uhamka-100 max-w-lg text-sm sm:text-base leading-relaxed">
                NIDN: {{ $user->nidn ?? '-' }} | Prodi: {{ $user->program_studi ?? '-' }}
            </p>
        </div>
    </div>
</div>

<!-- Alert for Pending Reviews -->
@if ($user->proposalsAsDosen()->where('status', 'menunggu_approval')->count() > 0)
<div class="mb-8 p-4 rounded-2xl bg-uhamka-yellow-50 border border-uhamka-yellow-200 flex items-start sm:items-center gap-4 shadow-sm animate-pulse-slow">
    <div class="w-10 h-10 rounded-full bg-uhamka-yellow-100 flex items-center justify-center text-uhamka-yellow-600 flex-shrink-0">
        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </div>
    <div class="flex-1">
        <h3 class="font-bold text-uhamka-yellow-800">Perhatian!</h3>
        <p class="text-sm text-uhamka-yellow-700">
            Anda memiliki <span class="font-bold">{{ $user->proposalsAsDosen()->where('status', 'menunggu_approval')->count() }} proposal</span> yang menunggu review Anda.
        </p>
    </div>
    <a href="{{ route('dosen.proposals.index') }}" class="px-4 py-2 bg-uhamka-yellow-400 text-uhamka-900 font-bold text-sm rounded-lg hover:bg-uhamka-yellow-500 transition-colors shadow-sm">
        Review Sekarang
    </a>
</div>
@endif

<!-- Stats Cards (Bento Grid) -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Menunggu Approval -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-uhamka-yellow-200 transition-all group">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500 text-xl group-hover:scale-110 transition-transform">
                ⏳
            </div>
            <span class="px-3 py-1 bg-orange-50 text-orange-600 text-xs font-bold rounded-lg uppercase tracking-wider">Menunggu Approval</span>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-4xl font-extrabold text-slate-900">{{ $user->proposalsAsDosen()->where('status', 'menunggu_approval')->count() }}</span>
        </div>
    </div>

    <!-- Disetujui -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-green-200 transition-all group">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 text-xl group-hover:scale-110 transition-transform">
                ✅
            </div>
            <span class="px-3 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-lg uppercase tracking-wider">Disetujui</span>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-4xl font-extrabold text-slate-900">{{ $user->proposalsAsDosen()->where('status', 'disetujui')->count() }}</span>
        </div>
    </div>

    <!-- Ditolak -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-red-200 transition-all group">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-500 text-xl group-hover:scale-110 transition-transform">
                ❌
            </div>
             <span class="px-3 py-1 bg-red-50 text-red-600 text-xs font-bold rounded-lg uppercase tracking-wider">Ditolak</span>
        </div>
        <div class="flex items-baseline gap-2">
            <span class="text-4xl font-extrabold text-slate-900">{{ $user->proposalsAsDosen()->where('status', 'ditolak')->count() }}</span>
        </div>
    </div>
</div>

<!-- Info Panel -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
    <h3 class="font-bold text-xl text-slate-900 mb-2">Informasi Dosen</h3>
    <p class="text-slate-500 mb-4">Sebagai dosen pembimbing, Anda dapat:</p>
    <ul class="list-disc list-inside text-slate-600 space-y-2">
        <li>Mereview dan menyetujui/menolak proposal PKM mahasiswa</li>
        <li>Membimbing kelompok PKM mahasiswa</li>
        <li>Memberikan review dan masukan pada proposal</li>
        <li>Memantau progress kelompok bimbingan</li>
    </ul>
</div>
@endsection
