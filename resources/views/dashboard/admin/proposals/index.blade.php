@extends('layouts.app-modern')

@section('title', 'Review Proposal')

@section('content')
    <!-- Page Heading -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Review Proposal PKM</h1>
        <p class="text-slate-500">Daftar proposal masuk yang menunggu persetujuan admin.</p>
    </div>

    @if (session('success'))
        <div
            class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-center gap-3 text-green-700 animate-fade-in-down">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-uhamka-200 transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div
                    class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center text-orange-500 text-xl group-hover:scale-110 transition-transform">
                    ⏳
                </div>
                <span
                    class="px-3 py-1 bg-orange-50 text-orange-600 text-xs font-bold rounded-lg uppercase tracking-wider">Menunggu</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span
                    class="text-4xl font-extrabold text-slate-900">{{ $proposals->where('status_admin', 'menunggu')->count() }}</span>
                <span class="text-sm font-bold text-slate-400">Proposal</span>
            </div>
        </div>

        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-green-200 transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div
                    class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 text-xl group-hover:scale-110 transition-transform">
                    ✅
                </div>
                <span
                    class="px-3 py-1 bg-green-50 text-green-600 text-xs font-bold rounded-lg uppercase tracking-wider">Disetujui</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span
                    class="text-4xl font-extrabold text-slate-900">{{ $proposals->where('status_admin', 'disetujui')->count() }}</span>
                <span class="text-sm font-bold text-slate-400">Proposal</span>
            </div>
        </div>

        <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-red-200 transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div
                    class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-500 text-xl group-hover:scale-110 transition-transform">
                    ❌
                </div>
                <span
                    class="px-3 py-1 bg-red-50 text-red-600 text-xs font-bold rounded-lg uppercase tracking-wider">Ditolak</span>
            </div>
            <div class="flex items-baseline gap-2">
                <span
                    class="text-4xl font-extrabold text-slate-900">{{ $proposals->where('status_admin', 'ditolak')->count() }}</span>
                <span class="text-sm font-bold text-slate-400">Proposal</span>
            </div>
        </div>
    </div>

    <!-- Proposals Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-lg text-slate-900">Daftar Proposal PKM</h3>
        </div>

        @if ($proposals->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-900 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nama Kelompok</th>
                            <th class="px-6 py-4">Judul PKM</th>
                            <th class="px-6 py-4">Ketua</th>
                            <th class="px-6 py-4">Skema</th>
                            <th class="px-6 py-4 text-center">File</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($proposals as $index => $proposal)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $proposal->nama_kelompok }}</td>
                                <td class="px-6 py-4 line-clamp-2 max-w-xs">{{ $proposal->judul_kelompok }}</td>
                                <td class="px-6 py-4">{{ $proposal->ketua->name }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $proposal->skema }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ Storage::url($proposal->file_proposal) }}" target="_blank"
                                        class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors inline-block text-center"
                                        title="Download">
                                        <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($proposal->status_admin == 'menunggu')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 animate-pulse">Menunggu
                                            Review</span>
                                    @elseif($proposal->status_admin == 'disetujui')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">Disetujui</span>
                                    @elseif($proposal->status_admin == 'ditolak')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.pengajuan_kelompok_pkm.show', $proposal->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-lg text-white bg-uhamka-500 hover:bg-uhamka-600 focus:outline-none transition-all shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
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
                <svg class="w-16 h-16 mb-4 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p>Belum ada proposal yang diupload.</p>
            </div>
        @endif
    </div>
@endsection
