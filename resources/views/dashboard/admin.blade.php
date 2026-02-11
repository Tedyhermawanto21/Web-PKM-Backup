@extends('layouts.app-modern')

@section('title', 'Dashboard Admin')

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
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-white uppercase tracking-wider">Administrator System</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2 leading-tight">
                    Panel Administrator
                </h1>
                <p class="text-uhamka-100 max-w-lg text-sm sm:text-base leading-relaxed">
                    Kelola sistem, pengguna, dan konfigurasi master data PKM Center.
                </p>
            </div>
        </div>
    </div>

    <!-- Skema Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total User Card -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col hover:shadow-md hover:border-uhamka-200 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold px-2 py-1 bg-green-100 text-green-700 rounded-full">Active</span>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mb-1">{{ $totalUsers }}</div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total User</div>
        </div>

        <!-- Total Proposal Card -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col hover:shadow-md hover:border-uhamka-200 transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-extrabold text-slate-900 mb-1">{{ $totalProposals }}</div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Proposal</div>
        </div>


    </div>

    <!-- Skema List Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-lg text-slate-900">Daftar Skema PKM</h3>
            <a href="{{ route('admin.skemas.index') }}" class="text-sm font-bold text-uhamka-600 hover:text-uhamka-700">Lihat Semua &rarr;</a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($skemaStats as $skema)
                <div class="p-4 rounded-xl border border-slate-200 hover:border-{{ $skema->warna ?? 'slate' }}-500 hover:bg-{{ $skema->warna ?? 'slate' }}-50 transition-all flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-full bg-{{ $skema->warna ?? 'slate' }}-100 text-{{ $skema->warna ?? 'slate' }}-600 flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                        {{ substr($skema->nama, 4, 2) }}
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
                </div>
            @endforeach
        </div>
    </div>
@endsection
