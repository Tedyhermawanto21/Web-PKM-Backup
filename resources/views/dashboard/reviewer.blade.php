@extends('layouts.app-modern')

@section('title', 'Dashboard Reviewer')

@section('content')
    <!-- Welcome Banner -->
    <div
        class="relative w-full rounded-3xl overflow-hidden bg-gradient-to-r from-uhamka-900 via-uhamka-800 to-uhamka-900 shadow-2xl mb-8 group">
        <div
            class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-uhamka-yellow-400 rounded-full mix-blend-overlay filter blur-3xl opacity-20 animate-blob">
        </div>
        <div class="relative z-10 p-8 sm:p-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md border border-white/20 rounded-full mb-4">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    <span class="text-xs font-bold text-white uppercase tracking-wider">Reviewer</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2 leading-tight">Panel Reviewer</h1>
                <p class="text-uhamka-100 max-w-lg text-sm sm:text-base leading-relaxed">Halo, {{ $user->name }} — akses
                    untuk meninjau proposal yang ditugaskan.</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-sm text-slate-500">Total Assigned</h3>
            <p class="text-2xl font-bold mt-2">{{ $totalAssigned ?? '-' }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-sm text-slate-500">Reviewed</h3>
            <p class="text-2xl font-bold mt-2">{{ $reviewed ?? '-' }}</p>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
            <h3 class="text-sm text-slate-500">Pending</h3>
            <p class="text-2xl font-bold mt-2">{{ $pending ?? '-' }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h3 class="font-bold text-lg text-slate-900 mb-4">Instruksi Reviewer</h3>
        <p class="text-slate-600">Silakan pilih proposal yang ditugaskan untuk Anda, lalu berikan komentar dan penilaian
            sesuai jadwal yang berlaku.</p>
    </div>

    <div class="mt-6 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-lg text-slate-900">Tugas Terbaru</h3>
            <a href="{{ route('reviewer.assigned.index') }}" class="text-sm text-uhamka-500">Lihat Semua</a>
        </div>

        @if (isset($recentAssigned) && $recentAssigned->count() > 0)
            <ul class="space-y-3">
                @foreach ($recentAssigned as $p)
                    <li class="p-3 border rounded-lg flex items-center justify-between">
                        <div>
                            <div class="font-bold">{{ $p->nama_kelompok ?? '-' }}</div>
                            <div class="text-xs text-slate-500">{{ Str::limit($p->judul_kelompok ?? $p->judul_pkm, 80) }}
                            </div>
                        </div>
                        <a href="{{ route('reviewer.assigned.show', $p->id) }}"
                            class="px-3 py-1.5 bg-uhamka-500 text-white rounded">Review</a>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-slate-500">Belum ada tugas review. Refresh halaman setelah admin menugaskan reviewer.</div>
        @endif
    </div>
@endsection
