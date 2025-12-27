@extends('layouts.app-modern')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Welcome Banner -->
<div class="relative w-full rounded-3xl overflow-hidden bg-gradient-to-r from-uhamka-900 via-uhamka-800 to-uhamka-900 shadow-2xl mb-8 group">
    <!-- Background Decor -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-uhamka-yellow-400 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-blue-400 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>

    <div class="relative z-10 p-8 sm:p-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-4">
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

<!-- System Status Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card 1 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col hover:shadow-md hover:border-uhamka-200 transition-all group">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <span class="text-xs font-bold px-2 py-1 bg-green-100 text-green-700 rounded-full">Active</span>
        </div>
        <div class="text-3xl font-extrabold text-slate-900 mb-1">-</div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total User</div>
    </div>

    <!-- Card 2 -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col hover:shadow-md hover:border-uhamka-200 transition-all group">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-indigo-50 rounded-xl text-indigo-600 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
        <div class="text-3xl font-extrabold text-slate-900 mb-1">-</div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Proposal</div>
    </div>
    
    <!-- Card 3 & 4 (Placeholders for visual balance) -->
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col hover:shadow-md hover:border-uhamka-200 transition-all group">
         <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-amber-50 rounded-xl text-amber-600 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
            </div>
        </div>
        <div class="text-3xl font-extrabold text-slate-900 mb-1">-</div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Database</div>
    </div>

     <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col hover:shadow-md hover:border-uhamka-200 transition-all group">
         <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-red-50 rounded-xl text-red-600 group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
        <div class="text-3xl font-extrabold text-slate-900 mb-1">-</div>
        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Reports</div>
    </div>
</div>

<!-- Quick Links / Menu Grid -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
    <h3 class="font-bold text-lg text-slate-900 mb-6">Manajemen Sistem</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        
        <a href="#" class="p-4 rounded-xl border border-slate-200 hover:border-uhamka-500 hover:bg-slate-50 transition-all flex flex-col items-center text-center gap-3 group">
            <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-uhamka-500 group-hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-sm font-bold text-slate-700">Konfigurasi Umum</span>
        </a>

        <a href="{{ route('admin.schedules.index') }}" class="p-4 rounded-xl border border-slate-200 hover:border-uhamka-500 hover:bg-slate-50 transition-all flex flex-col items-center text-center gap-3 group">
            <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-uhamka-500 group-hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <span class="text-sm font-bold text-slate-700">Kelola Jadwal</span>
        </a>

         <a href="#" class="p-4 rounded-xl border border-slate-200 hover:border-uhamka-500 hover:bg-slate-50 transition-all flex flex-col items-center text-center gap-3 group">
            <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-uhamka-500 group-hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <span class="text-sm font-bold text-slate-700">Manajemen Dosen</span>
        </a>

         <a href="{{ route('admin.proposals.index') }}" class="p-4 rounded-xl border border-slate-200 hover:border-uhamka-500 hover:bg-slate-50 transition-all flex flex-col items-center text-center gap-3 group">
            <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center group-hover:bg-uhamka-500 group-hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <span class="text-sm font-bold text-slate-700">Review Proposal</span>
        </a>

    </div>
</div>
@endsection
