@extends('layouts.app-modern')

@section('title', 'Upload Proposal')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Upload Proposal PKM</h1>
            <p class="text-slate-500">Unggah file proposal PKM Anda sesuai jadwal yang ditentukan.</p>
        </div>
        @if ($uploadSchedule)
            <a href="{{ route('mahasiswa.upload.create') }}"
                class="inline-flex items-center px-4 py-2 bg-uhamka-900 text-white text-sm font-bold rounded-xl hover:bg-uhamka-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                </svg>
                Upload Proposal PKM
            </a>
        @else
            <button
                class="inline-flex items-center px-4 py-2 bg-slate-200 text-slate-400 text-sm font-bold rounded-xl cursor-not-allowed"
                disabled>
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Upload Ditutup
            </button>
        @endif
    </div>

    <!-- Schedule Information -->
    @if ($uploadSchedule)
        <div
            class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-start gap-4 animate-fade-in-down shadow-sm">
            <div class="p-2 bg-green-100 rounded-lg text-green-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-green-800 mb-1">Jadwal Upload Dibuka!</h4>
                <p class="text-sm text-green-700 mb-2">Periode upload proposal saat ini sedang berlangsung.</p>
                <div
                    class="flex items-center gap-2 text-sm font-mono text-green-800 bg-green-100 px-3 py-1 rounded-lg inline-flex">
                    <span>{{ $uploadSchedule->start_date->format('d M Y H:i') }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    <span>{{ $uploadSchedule->end_date->format('d M Y H:i') }}</span>
                </div>
                @if ($uploadSchedule->description)
                    <p class="text-xs text-green-600 mt-2 italic border-t border-green-200 pt-2">
                        {{ $uploadSchedule->description }}</p>
                @endif
            </div>
            <button type="button" class="ml-auto text-green-400 hover:text-green-600"
                onclick="this.parentElement.remove()">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @else
        <div
            class="mb-6 p-4 rounded-xl bg-yellow-50 border border-yellow-200 flex items-start gap-4 animate-fade-in-down shadow-sm">
            <div class="p-2 bg-yellow-100 rounded-lg text-yellow-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-yellow-800 mb-1">Jadwal Upload Belum Dibuka</h4>
                <p class="text-sm text-yellow-700">Mohon tunggu pengumuman dari admin untuk jadwal upload proposal
                    berikutnya.</p>
            </div>
        </div>
    @endif

    <!-- Revision Schedule Information -->
    @php
        $hasRevision = $uploadedProposals->where('revision_stage', '>', 0)->first();
        $revisionSchedule = null;
        if ($hasRevision) {
            $revisionType = 'revisi_' . $hasRevision->revision_stage;
            $revisionSchedule = \App\Models\Schedule::ofType($revisionType)->active()->ongoing()->first();
        }
    @endphp
    @if ($hasRevision && $revisionSchedule)
        <div
            class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-200 flex items-start gap-4 animate-fade-in-down shadow-sm">
            <div class="p-2 bg-blue-100 rounded-lg text-blue-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-blue-800 mb-1">Jadwal Revisi Tahap {{ $hasRevision->revision_stage }} Dibuka!</h4>
                <div
                    class="flex items-center gap-2 text-sm font-mono text-blue-800 bg-blue-100 px-3 py-1 rounded-lg inline-flex mt-1">
                    <span>{{ $revisionSchedule->start_date->format('d M Y H:i') }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    <span>{{ $revisionSchedule->end_date->format('d M Y H:i') }}</span>
                </div>
                @if ($revisionSchedule->description)
                    <p class="text-xs text-blue-600 mt-2 italic border-t border-blue-200 pt-2">
                        {{ $revisionSchedule->description }}</p>
                @endif
            </div>
            <button type="button" class="ml-auto text-blue-400 hover:text-blue-600" onclick="this.parentElement.remove()">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @elseif($hasRevision)
        <div
            class="mb-6 p-4 rounded-xl bg-orange-50 border border-orange-200 flex items-start gap-4 animate-fade-in-down shadow-sm">
            <div class="p-2 bg-orange-100 rounded-lg text-orange-600 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-orange-800 mb-1">Jadwal Revisi Tahap {{ $hasRevision->revision_stage }} Belum
                    Dibuka</h4>
                <p class="text-sm text-orange-700">Proposal Anda memerlukan revisi. Mohon tunggu jadwal revisi dibuka oleh
                    admin.</p>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div
            class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-center gap-3 text-green-700 animate-fade-in-down">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div
            class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-center gap-3 text-red-700 animate-fade-in-down">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-lg text-slate-900">Proposal PKM yang Sudah Diupload</h3>
        </div>

        @if ($uploadedProposals->count() > 0)
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
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($uploadedProposals as $index => $proposal)
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
                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($proposal->status_admin == 'pending')
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
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('mahasiswa.upload.show', $proposal->id) }}"
                                            class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors shadow-sm"
                                            title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
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
                <h5 class="text-slate-600 font-bold mb-1">Belum Ada Proposal yang Diupload</h5>
                <p class="text-sm">Silakan upload proposal Anda saat jadwal sudah dibuka.</p>
                <a href="{{ route('mahasiswa.upload.create') }}"
                    class="mt-4 inline-flex items-center px-4 py-2 bg-uhamka-500 text-white text-sm font-bold rounded-xl hover:bg-uhamka-600 transition-all">
                    Upload Proposal PKM
                </a>
            </div>
        @endif
    </div>
@endsection
