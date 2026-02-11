<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - PKM Center UHAMKA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Preserving stacks for any vendor styles -->
    @stack('styles')

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .sidebar-active {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
            border-left: 4px solid #FACC15;
            /* uhamka-yellow */
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside id="sidebar"
            class="bg-uhamka-900 w-64 flex-shrink-0 fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition-transform duration-300 ease-in-out z-50 flex flex-col shadow-2xl border-r border-white/10">
            <!-- Logo -->
            <a href="{{ route('dashboard') }}"
                class="h-20 flex items-center px-6 border-b border-white/10 bg-uhamka-950 hover:bg-uhamka-900 transition-colors">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 bg-uhamka-yellow-400 rounded-lg flex items-center justify-center text-uhamka-900 font-bold font-serif">
                        U</div>
                    <div>
                        <h1 class="text-white font-bold text-lg leading-none">PKM Center</h1>
                        <span
                            class="text-[10px] text-uhamka-yellow-400 font-bold uppercase tracking-wider">Dashboard</span>
                    </div>
                </div>
                <!-- Close Button (Mobile) -->
                <button id="closeSidebar" class="md:hidden ml-auto text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </a>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">

                @if (Auth::user()->isMahasiswa())
                    @php
                        $showRevisionMain = \App\Models\Schedule::whereIn('type', [
                            \App\Models\Schedule::TYPE_REVISI_1,
                            \App\Models\Schedule::TYPE_REVISI_2,
                            \App\Models\Schedule::TYPE_REVISI_3,
                        ])
                            ->active()
                            ->ongoing()
                            ->exists();
                    @endphp
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 px-2">Menu Mahasiswa
                    </div>

                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Dashboard
                    </a>
                    @if (Route::has('mahasiswa.kelompoks.index'))
                        <a href="{{ route('mahasiswa.kelompoks.index') }}"
                            class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('mahasiswa.kelompoks.*') ? 'sidebar-active' : '' }}">
                        @else
                            <a href="#"
                                class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('mahasiswa.kelompoks.*') ? 'sidebar-active' : '' }}">
                    @endif
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Kelompok PKM Saya
                    </a>
                    @if (Route::has('mahasiswa.pengajuan_kelompok_pkm.index'))
                        <a href="{{ route('mahasiswa.pengajuan_kelompok_pkm.index') }}"
                            class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('mahasiswa.pengajuan_kelompok_pkm.*') ? 'sidebar-active' : '' }}">
                        @else
                            <a href="#"
                                class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('mahasiswa.pengajuan_kelompok_pkm.*') ? 'sidebar-active' : '' }}">
                    @endif
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Pengajuan Proposal
                    </a>
                    @if (Route::has('mahasiswa.upload.index'))
                        <a href="{{ route('mahasiswa.upload.index') }}"
                            class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('mahasiswa.upload.*') ? 'sidebar-active' : '' }}">
                        @else
                            <a href="#"
                                class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('mahasiswa.upload.*') ? 'sidebar-active' : '' }}">
                    @endif
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4 4m4-4v12" />
                    </svg>
                    Upload Proposal
                    </a>
                    @if ($showRevisionMain)
                        @if (Route::has('mahasiswa.revisi.index'))
                            <a href="{{ route('mahasiswa.revisi.index') }}"
                                class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('mahasiswa.revisi.*') ? 'sidebar-active' : '' }}">
                            @else
                                <a href="#"
                                    class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('mahasiswa.revisi.*') ? 'sidebar-active' : '' }}">
                        @endif
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Revisi Proposal
                        </a>
                    @endif
                @elseif(Auth::user()->isDosen())
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 px-2">Menu Dosen</div>

                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('dosen.pengajuan_kelompok_pkm.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('dosen.pengajuan_kelompok_pkm.*') ? 'sidebar-active' : '' }}">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Daftar Proposal Kelompok Bimbingan
                    </a>
                    <a href="{{ route('dosen.kelompok_requests.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('dosen.kelompok_requests.*') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM2 21a6 6 0 0112 0v0H2z" />
                        </svg>
                        Permintaan Pembimbing
                    </a>
                    <a href="{{ route('dosen.bimbingan_mahasiswa.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('dosen.bimbingan_mahasiswa.*') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Bimbingan Mahasiswa
                    </a>
                @elseif(Auth::user()->isKaprodi())
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 px-2">Menu Kaprodi</div>

                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="{{ route('kaprodi.kelompok_requests.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('kaprodi.kelompok_requests.*') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM2 21a6 6 0 0112 0v0H2z" />
                        </svg>
                        Verifikasi Kelompok
                    </a>
                    <a href="{{ route('kaprodi.daftar_mahasiswa.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('kaprodi.daftar_mahasiswa.*') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Daftar Mahasiswa
                    </a>

                @elseif(Auth::user()->isAdmin())
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 px-2">Menu Administrator
                    </div>

                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.pengajuan_kelompok_pkm.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('admin.pengajuan_kelompok_pkm.*') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Review Proposal
                    </a>
                    <a href="{{ route('admin.schedules.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('admin.schedules.*') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Kelola Jadwal
                    </a>
                    <a href="{{ route('admin.skemas.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('admin.skemas.*') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Kelola Skema
                    </a>
                    <a href="{{ route('admin.reviewers.index') }}"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('admin.reviewers.*') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Manajemen Reviewer
                    </a>
                    <a href="#"
                        class="flex items-center gap-3 px-3 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-all font-medium {{ request()->routeIs('admin.settings.*') ? 'sidebar-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Pengaturan
                    </a>
                @endif

                <!-- Divider -->
                <div class="my-4 mx-4 border-t border-white/10"></div>

                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 px-2">Akun</div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-red-400 hover:bg-red-500/10 transition-all font-medium">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </nav>

            <!-- User Mini Profile -->
            <div class="p-4 bg-uhamka-950 border-t border-white/10">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-white font-bold border-2 border-slate-600">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <h4 class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</h4>
                        <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-full relative overflow-hidden">

            <!-- Topbar (Glass) -->
            <header
                class="h-20 glass-effect border-b border-slate-200 z-40 flex items-center justify-between px-4 sm:px-6 lg:px-8 absolute top-0 w-full">
                <!-- Mobile Menu Button -->
                <button id="openSidebar" class="md:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Page Title / Breadcrumbs -->
                <div class="flex flex-col">
                    <h2 class="text-xl font-bold text-slate-800 hidden md:block">@yield('title')</h2>
                </div>

                <!-- Right Actions -->
                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <button class="relative p-2 text-slate-500 hover:text-uhamka-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span
                            class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </button>
                    <!-- Divider -->
                    <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
                    <!-- Role Badge -->
                    <span
                        class="px-3 py-1 bg-uhamka-50 text-uhamka-700 rounded-full text-xs font-bold border border-uhamka-100">
                        {{ optional(Auth::user()->role)->display_name ?? (Auth::user()->role->name ?? 'User') }}
                    </span>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto bg-slate-50 pt-24 pb-8 px-4 sm:px-6 lg:px-8 scroll-smooth">
                @yield('content')
            </main>
        </div>

        <!-- Mobile Sidebar Overlay -->
        <div id="sidebarOverlay"
            class="fixed inset-0 bg-slate-900/50 z-40 hidden backdrop-blur-sm md:hidden transition-opacity opacity-0">
        </div>
    </div>

    <script>
        // Sidebar Logic
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        if (openBtn) openBtn.addEventListener('click', toggleSidebar);
        if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);
    </script>

    <!-- Stack Scripts -->
    @stack('scripts')
</body>

</html>
