@extends('layouts.app-modern')

@section('title', 'Daftar Proposal')

@section('content')
    <!-- Page Heading -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Daftar Proposal PKM</h1>
        <p class="text-slate-500">Daftar proposal mahasiswa dari program studi Anda.</p>
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

    <!-- Skema Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- 'All' Card -->
        <a href="{{ route('kaprodi.pengajuan_kelompok_pkm.index') }}"
            class="bg-white p-6 rounded-2xl shadow-sm border {{ is_null($selectedSkema) ? 'border-uhamka-500 ring-2 ring-uhamka-200' : 'border-slate-100 hover:border-uhamka-200' }} hover:shadow-md transition-all group">
            <div class="flex items-start justify-between mb-4">
                <div
                    class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 text-xl group-hover:scale-110 transition-transform">
                    📚
                </div>
                
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-900">{{ $skemaStats->sum('proposals_count') }}</span>
                <span class="text-sm font-bold text-slate-400">Total Proposal</span>
            </div>
            <p class="text-xs text-slate-500 mt-2 font-medium">Semua Skema</p>
        </a>

        @foreach ($skemaStats as $skema)
            <a href="{{ route('kaprodi.pengajuan_kelompok_pkm.index', ['skema' => $skema->nama]) }}"
                class="bg-white p-6 rounded-2xl shadow-sm border {{ isset($selectedSkema) && $selectedSkema->id == $skema->id ? 'border-' . $skema->warna . '-500 ring-2 ring-' . $skema->warna . '-200' : 'border-slate-100 hover:border-' . $skema->warna . '-200' }} hover:shadow-md transition-all group">
                <div class="flex items-start justify-between mb-4">
                    <div
                        class="w-12 h-12 bg-{{ $skema->warna }}-100 rounded-full flex items-center justify-center text-{{ $skema->warna }}-700 font-extrabold text-sm group-hover:scale-110 transition-transform tracking-tight border-2 border-white shadow-sm ring-2 ring-{{ $skema->warna }}-50">
                        {{ str_replace('PKM-', '', $skema->nama) }}
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-slate-900">{{ $skema->proposals_count }}</span>
                    <span class="text-sm font-bold text-slate-400">Proposal</span>
                </div>
                <p class="text-xs text-slate-500 mt-2 font-medium truncate">{{ $skema->label }}</p>
            </a>
        @endforeach
    </div>

    <!-- Proposals Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-lg text-slate-900">
                    @if($selectedSkema)
                        Daftar Proposal {{ $selectedSkema->nama }}
                    @else
                        Daftar Semua Proposal
                    @endif
                </h3>
                @if($selectedSkema)
                    <p class="text-sm text-slate-500">{{ $selectedSkema->label }}</p>
                @endif
            </div>
            @if($selectedSkema)
                <a href="{{ route('kaprodi.pengajuan_kelompok_pkm.index') }}" class="text-sm text-uhamka-600 font-bold hover:underline">
                    Lihat Semua
                </a>
            @endif
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
                                    @if ($proposal->status_kaprodi == 'disetujui')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">Disetujui</span>
                                    @elseif($proposal->status_kaprodi == 'ditolak')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Ditolak</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 animate-pulse">Menunggu Verifikasi</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('kaprodi.pengajuan_kelompok_pkm.show', $proposal->id) }}"
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
                <p>Belum ada proposal yang sesuai.</p>
                @if($selectedSkema)
                     <a href="{{ route('kaprodi.pengajuan_kelompok_pkm.index') }}" class="mt-2 text-uhamka-600 font-bold hover:underline">Lihat Semua Skema</a>
                @endif
            </div>
        @endif
    </div>
@endsection
