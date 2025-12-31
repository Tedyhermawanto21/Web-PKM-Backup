@extends('layouts.app-modern')

@section('title', 'Verifikasi Proposal')

@section('content')
    <!-- Page Heading -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Verifikasi Proposal</h1>
        <p class="text-slate-500">Daftar proposal mahasiswa yang menunggu verifikasi program studi.</p>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div
            class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-center gap-3 text-green-700 animate-fade-in-down">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Proposals Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-lg text-slate-900">Daftar Proposal PKM Saya</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-900 font-bold uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Kelompok</th>
                        <th class="px-6 py-4">Judul PKM</th>
                        <th class="px-6 py-4">Skema</th>
                        <th class="px-6 py-4">Dosen Pembimbing</th>
                        <th class="px-6 py-4 text-center">Status Dosen</th>
                        <th class="px-6 py-4 text-center">Status Kaprodi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($proposals as $index => $proposal)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $proposal->nama_kelompok }}</td>
                            <td class="px-6 py-4 line-clamp-2 max-w-xs">{{ $proposal->judul_kelompok }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $proposal->skema }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $proposal->dosenPembimbing->name ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                @if ($proposal->status_dosen == 'disetujui')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">Disetujui</span>
                                @elseif($proposal->status_dosen == 'ditolak')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Ditolak</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 animate-pulse">Menunggu</span>
                                @endif
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
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 animate-pulse">Menunggu</span>
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
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-slate-400">
                                <svg class="w-16 h-16 mx-auto mb-4 text-slate-200" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p>Belum ada proposal yang perlu diverifikasi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
