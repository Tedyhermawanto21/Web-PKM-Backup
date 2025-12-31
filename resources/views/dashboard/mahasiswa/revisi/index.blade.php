@extends('layouts.app-modern')

@section('title', 'Revisi Proposal')

@section('content')
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Revisi Proposal</h1>
            <p class="text-slate-500">Halaman khusus untuk melihat dan mengunggah revisi proposal sesuai tahap yang
                ditentukan admin.</p>
        </div>
    </div>

    @if ($revisionSchedule && $revisionSchedule->count() > 0)
        <div
            class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-200 flex items-start gap-4 animate-fade-in-down shadow-sm">
            <div class="p-2 bg-blue-100 rounded-lg text-blue-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-blue-800 mb-1">Jadwal Revisi Aktif</h4>
                <p class="text-sm text-blue-700 mb-2">Admin saat ini membuka jadwal revisi. Silakan cek proposal Anda yang
                    perlu direvisi di daftar berikut.</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-lg text-slate-900">Proposal PKM untuk Revisi</h3>
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
                            <th class="px-6 py-4 text-center">File</th>
                            <th class="px-6 py-4 text-center">Status Admin</th>
                            <th class="px-6 py-4 text-center">Tahap Revisi</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($proposals as $index => $proposal)
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
                                <td class="px-6 py-4 text-center">
                                    @if ($proposal->file_proposal)
                                        <a href="{{ Storage::url($proposal->file_proposal) }}" target="_blank"
                                            class="inline-flex items-center px-3 py-1 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors text-xs font-bold shadow-sm">
                                            Download
                                        </a>
                                    @endif
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
                                    @if ($proposal->revision_stage > 0)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700 border border-orange-200">
                                            Revisi {{ $proposal->revision_stage }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('mahasiswa.upload.show', $proposal->id) }}"
                                            class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors shadow-sm"
                                            title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>

                                        @if ($proposal->status_admin == 'ditolak' || $proposal->revision_stage > 0)
                                            @php
                                                $canEdit = true;
                                                if ($proposal->revision_stage > 0) {
                                                    $revisionType = 'revisi_' . $proposal->revision_stage;
                                                    $revisionSchedule = \App\Models\Schedule::ofType($revisionType)
                                                        ->active()
                                                        ->ongoing()
                                                        ->first();
                                                    $canEdit = $revisionSchedule != null;
                                                }
                                            @endphp
                                            @if ($canEdit)
                                                <a href="{{ route('mahasiswa.upload.edit', $proposal->id) }}"
                                                    class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 transition-colors shadow-sm"
                                                    title="Edit / Revisi">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            @else
                                                <button
                                                    class="p-2 bg-slate-100 text-slate-400 rounded-lg cursor-not-allowed"
                                                    disabled title="Jadwal revisi belum dibuka">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                    </svg>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
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
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                <h5 class="text-slate-600 font-bold mb-1">Tidak Ada Proposal untuk Revisi</h5>
                <p class="text-sm">Tidak ada proposal Anda yang perlu direvisi saat ini.</p>
            </div>
        @endif
    </div>
@endsection
