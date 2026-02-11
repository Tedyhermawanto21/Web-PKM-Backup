@extends('layouts.app-modern')

@section('title', 'Dashboard Mahasiswa')

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
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2 leading-tight">
                    Selamat Datang, <br>
                    <span class="text-uhamka-yellow-400">{{ $user->name }}</span>! 👋
                </h1>
                <p class="text-uhamka-100 max-w-lg text-sm sm:text-base leading-relaxed">
                    NIM: {{ $user->nim ?? '-' }} | Prodi: {{ $user->program_studi ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Bento Grid Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        <!-- Profile Card -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-uhamka-200 transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div
                    class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 text-xl group-hover:scale-110 transition-transform">
                    👤
                </div>
                <span
                    class="px-3 py-1 bg-slate-50 text-slate-500 text-xs font-bold rounded-lg uppercase tracking-wider">Biodata</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Mahasiswa Aktif</h3>
            <p class="text-lg font-bold text-slate-800 mb-4">{{ $user->name }}</p>

            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <span class="text-xs text-slate-500 font-semibold">NIM</span>
                    <span class="text-xs font-bold font-mono text-slate-700">{{ $user->nim ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <span class="text-xs text-slate-500 font-semibold">Program Studi</span>
                    <span class="text-xs font-bold text-uhamka-600 text-right">{{ $user->program_studi ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Kelompok Stats -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-green-200 transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div
                    class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 text-xl group-hover:scale-110 transition-transform">
                    👥
                </div>
                <span
                    class="px-3 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-lg uppercase tracking-wider">Tim</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Kelompok Diikuti</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-extrabold text-slate-900">{{ $user->kelompoks->count() }}</span>
                <span class="text-sm font-bold text-slate-400">Tim</span>
            </div>
            <p class="text-xs text-slate-400 mt-2">Bergabung sebagai anggota atau ketua.</p>

            <div class="mt-6">
                <a href="{{ route('mahasiswa.kelompoks.index') }}"
                    class="w-full inline-flex justify-center items-center py-2.5 px-4 border border-slate-200 rounded-xl shadow-sm text-sm font-bold text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition-all">
                    Lihat Detail Kelompok
                </a>
            </div>
        </div>

        <!-- Pengajuan Stats -->
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-uhamka-yellow-200 transition-all group relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-uhamka-yellow-50 rounded-bl-full -mr-4 -mt-4 opacity-50"></div>

            <div class="flex items-start justify-between mb-4 relative z-10">
                <div
                    class="w-12 h-12 bg-uhamka-yellow-50 rounded-xl flex items-center justify-center text-uhamka-yellow-600 text-xl group-hover:scale-110 transition-transform">
                    📝
                </div>
                <span
                    class="px-3 py-1 bg-uhamka-yellow-50 text-uhamka-yellow-700 text-xs font-bold rounded-lg uppercase tracking-wider">Proposal</span>
            </div>
            <h3 class="text-slate-500 text-sm font-medium mb-1">Pengajuan Ketua</h3>
            <div class="flex items-baseline gap-2 relative z-10">
                <span class="text-4xl font-extrabold text-slate-900">{{ $user->kelompokAsKetua->count() }}</span>
                <span class="text-sm font-bold text-slate-400">Judul</span>
            </div>

            <div class="mt-6 relative z-10">
                <a href="{{ route('mahasiswa.pengajuan_kelompok_pkm.create') }}"
                    class="w-full inline-flex justify-center items-center py-2.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-uhamka-900 bg-uhamka-yellow-400 hover:bg-uhamka-yellow-500 focus:outline-none transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Proposal Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Welcome Message Content -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="font-bold text-lg text-slate-900 mb-4">Informasi Dashboard</h3>
        <p class="text-slate-600 leading-relaxed mb-4">Selamat datang di PKM Center. Sistem informasi manajemen Program
            Kreativitas Mahasiswa.</p>
        <p class="text-slate-600 leading-relaxed mb-4">Melalui dashboard ini, Anda dapat:</p>
        <ul class="list-disc list-inside text-slate-600 mb-6 space-y-2">
            <li>Mengelola kelompok PKM</li>
            <li>Mengajukan proposal PKM</li>
            <li>Melihat status pengajuan</li>
            <li>Berkomunikasi dengan dosen pembimbing</li>
        </ul>
    </div>
@endsection
