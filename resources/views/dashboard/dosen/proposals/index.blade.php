@extends('layouts.app-modern')

@section('title', 'Pengajuan Proposal')

@section('content')
    <!-- Page Heading -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Pengajuan Proposal PKM</h1>
        <p class="text-slate-500">Daftar proposal mahasiswa yang perlu direview.</p>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-center gap-3 text-green-700 animate-fade-in-down">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-center gap-3 text-red-700 animate-fade-in-down">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Menunggu Approval -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-uhamka-200 transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500 text-xl group-hover:scale-110 transition-transform">
                    ⏳
                </div>
                <span class="px-3 py-1 bg-orange-50 text-orange-600 text-xs font-bold rounded-lg uppercase tracking-wider">Menunggu</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-extrabold text-slate-900">{{ $proposals->where('status', 'menunggu_approval')->count() }}</span>
                <span class="text-sm font-bold text-slate-400">Proposal</span>
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
                <span class="text-4xl font-extrabold text-slate-900">{{ $proposals->where('status', 'disetujui')->count() }}</span>
                <span class="text-sm font-bold text-slate-400">Proposal</span>
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
                <span class="text-4xl font-extrabold text-slate-900">{{ $proposals->where('status', 'ditolak')->count() }}</span>
                <span class="text-sm font-bold text-slate-400">Proposal</span>
            </div>
        </div>
    </div>

    <!-- Proposals Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-lg text-slate-900">Daftar Pengajuan Proposal</h3>
        </div>
        
        @if ($proposals->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-900 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nama Kelompok</th>
                            <th class="px-6 py-4">Judul PKM</th>
                            <th class="px-6 py-4">Skema</th>
                            <th class="px-6 py-4">Ketua</th>
                            <th class="px-6 py-4 text-center">Status Dosen</th>
                            <th class="px-6 py-4 text-center">Status Kaprodi</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($proposals as $index => $proposal)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $proposal->nama_kelompok }}</td>
                                <td class="px-6 py-4 line-clamp-2 max-w-xs">{{ Str::limit($proposal->judul_kelompok, 50) }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $proposal->skema }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                            {{ substr($proposal->ketua->name, 0, 1) }}
                                        </div>
                                        <span>{{ $proposal->ketua->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($proposal->status_dosen == 'disetujui')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">Disetujui</span>
                                    @elseif($proposal->status_dosen == 'ditolak')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Ditolak</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 animate-pulse">Menunggu</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($proposal->status_kaprodi == 'disetujui')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">Disetujui</span>
                                    @elseif($proposal->status_kaprodi == 'ditolak')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Ditolak</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500">Menunggu</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ $proposal->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('dosen.proposals.show', $proposal->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-lg text-white bg-uhamka-500 hover:bg-uhamka-600 focus:outline-none transition-all shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                <svg class="w-16 h-16 mb-4 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                <p>Belum ada pengajuan proposal yang masuk.</p>
            </div>
        @endif
    </div>
@endsection
