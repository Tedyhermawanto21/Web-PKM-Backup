@extends('layouts.app-modern')

@section('title', 'Detail Proposal')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Proposal</h1>
            <p class="text-slate-500">Informasi lengkap tentang proposal yang diupload.</p>
        </div>
        <a href="{{ route('mahasiswa.upload.index') }}"
            class="inline-flex items-center px-4 py-2 bg-white text-slate-700 text-sm font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm hover:shadow-md">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Informasi Proposal (Left - 2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-uhamka-900 px-6 py-4 border-b border-uhamka-800">
                <h6 class="font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Informasi Proposal
                </h6>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="pb-4 border-b border-slate-50">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Nama Kelompok</span>
                        <p class="text-slate-800 font-semibold">{{ $proposal->nama_kelompok }}</p>
                    </div>
                    <div class="pb-4 border-b border-slate-50">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Judul PKM</span>
                        <p class="text-slate-800 font-semibold leading-relaxed">{{ $proposal->judul_kelompok }}</p>
                    </div>
                    <div class="pb-4 border-b border-slate-50">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Skema</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-uhamka-100 text-uhamka-700">
                            {{ $proposal->skema }}
                        </span>
                    </div>
                    <div class="pb-4 border-b border-slate-50">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Ketua</span>
                        <p class="text-slate-800 font-semibold">{{ $proposal->ketua->name }}</p>
                    </div>
                    <div class="pb-4 border-b border-slate-50">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Dosen Pembimbing</span>
                        @if ($proposal->dosenPembimbing)
                            <div class="flex items-center gap-3 mt-1">
                                <div
                                    class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-sm font-bold text-slate-600">
                                    {{ substr($proposal->dosenPembimbing->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-slate-900 font-bold">{{ $proposal->dosenPembimbing->name }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ $proposal->dosenPembimbing->program_studi ?? 'Teknik Informatika' }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-slate-500 italic">Belum ditentukan</p>
                        @endif
                    </div>
                    <div class="pb-4 border-b border-slate-50">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Status Dosen</span>
                        @if ($proposal->status_dosen == 'menunggu')
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">Menunggu</span>
                        @elseif($proposal->status_dosen == 'disetujui')
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">Disetujui</span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Ditolak</span>
                        @endif
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Status Kaprodi</span>
                        @if ($proposal->status_kaprodi == 'menunggu')
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">Menunggu</span>
                        @elseif($proposal->status_kaprodi == 'disetujui')
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">Disetujui</span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Ditolak</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Upload (Right - 1 col) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-fit">
            <div class="bg-uhamka-900 px-6 py-4 border-b border-uhamka-800">
                <h6 class="font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Status Upload
                </h6>
            </div>
            <div class="p-6">
                <div class="text-center mb-4">
                    @if ($proposal->status_admin == 'pending')
                        <div
                            class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h5 class="font-bold text-yellow-700">Menunggu Review</h5>
                    @elseif($proposal->status_admin == 'disetujui')
                        <div
                            class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h5 class="font-bold text-green-700">Disetujui</h5>
                    @elseif($proposal->status_admin == 'ditolak')
                        <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h5 class="font-bold text-red-700">
                            @if ($proposal->revision_stage > 0)
                                Perlu Revisi Tahap {{ $proposal->revision_stage }}
                            @else
                                Ditolak
                            @endif
                        </h5>
                    @endif
                </div>

                @if ($proposal->revision_stage > 0)
                    <div class="mb-4 p-3 rounded-xl bg-yellow-50 border border-yellow-200">
                        <p class="text-sm text-yellow-700 font-medium">
                            <span class="font-bold">Status Revisi:</span> Proposal Anda memerlukan revisi tahap
                            {{ $proposal->revision_stage }}.
                        </p>
                    </div>
                @endif

                @if ($proposal->catatan_admin)
                    <div class="mb-4 p-3 rounded-xl bg-blue-50 border border-blue-200">
                        <p class="text-xs font-bold text-blue-700 uppercase mb-1">Catatan Admin:</p>
                        <p class="text-sm text-blue-800">{{ $proposal->catatan_admin }}</p>
                    </div>
                @endif

                @if ($proposal->file_proposal)
                    <a href="{{ Storage::url($proposal->file_proposal) }}" target="_blank"
                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download File
                    </a>
                @endif

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
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Upload Ulang File
                        </a>
                    @else
                        <button
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-slate-200 text-slate-400 font-bold rounded-xl cursor-not-allowed"
                            disabled>
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Jadwal Revisi Belum Dibuka
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Anggota Kelompok -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="bg-uhamka-900 px-6 py-4 border-b border-uhamka-800">
            <h6 class="font-bold text-white flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Anggota Kelompok
                <span class="ml-2 px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ count($proposal->anggota) }}
                    Orang</span>
            </h6>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-900 font-bold uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">NIM</th>
                        <th class="px-6 py-4">Program Studi</th>
                        <th class="px-6 py-4 text-center">Posisi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach ($proposal->anggota as $index => $anggota)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $anggota->nama }}</td>
                            <td class="px-6 py-4">{{ $anggota->nim }}</td>
                            <td class="px-6 py-4">{{ $anggota->program_studi }}</td>
                            <td class="px-6 py-4 text-center">
                                @if ($anggota->posisi == 'ketua')
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-uhamka-100 text-uhamka-700">Ketua</span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-500">Anggota</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
