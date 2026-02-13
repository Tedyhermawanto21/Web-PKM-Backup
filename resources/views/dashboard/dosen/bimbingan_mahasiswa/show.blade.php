@extends('layouts.app-modern')

@section('title', 'Detail Kelompok Bimbingan')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Kelompok Bimbingan</h1>
            <p class="text-slate-500 text-sm">Informasi lengkap kelompok yang Anda bimbing.</p>
        </div>
        <a href="{{ route('dosen.bimbingan_mahasiswa.index') }}"
            class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors">
            ← Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            <!-- Informasi Kelompok -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Informasi Kelompok</h2>
                </div>

                <h3 class="text-xl font-bold text-slate-900 mb-1">
                    {{ $kelompok->judul_pkm }}</h3>
                <p class="text-slate-500 mb-6">{{ $kelompok->nama_kelompok }}</p>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500 uppercase font-semibold">Skema PKM</p>
                        <p class="font-bold text-blue-600">{{ $kelompok->jenis_pkm }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500 uppercase font-semibold">Tanggal Pengajuan</p>
                        <p class="font-bold text-slate-700">{{ $kelompok->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="border-t pt-6">
                     <p class="text-xs text-slate-500 uppercase font-semibold mb-3">File Proposal</p>
                    @if ($kelompok->file_proposal)
                        <a href="{{ Storage::url($kelompok->file_proposal) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 rounded-lg font-semibold text-sm border border-green-200 hover:bg-green-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download File
                        </a>
                    @else
                        <p class="text-sm text-slate-400">Tidak ada file proposal</p>
                    @endif
                </div>

                @if($kelompok->deskripsi)
                <div class="border-t pt-6 mt-6">
                    <p class="text-xs text-slate-500 uppercase font-semibold mb-3">Deskripsi</p>
                    <p class="text-slate-700 leading-relaxed">{{ $kelompok->deskripsi }}</p>
                </div>
                @endif
            </div>

            <!-- Anggota Kelompok -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-purple-50 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Anggota Kelompok</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($allAnggota as $mhs)
                        <div class="flex items-center p-4 border border-slate-100 rounded-xl bg-slate-50/50 relative">
                            @if (($mhs->posisi ?? '') == 'ketua')
                                <span
                                    class="absolute top-2 right-2 px-2 py-0.5 bg-blue-600 text-[10px] text-white font-bold rounded">KETUA</span>
                            @endif
                            <div
                                class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold mr-4">
                                {{ substr($mhs->nama ?? $mhs->name ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm leading-tight">{{ $mhs->nama ?? $mhs->name }}</p>
                                <p class="text-xs text-slate-500">{{ $mhs->nim ?? '' }} • {{ $mhs->program_studi ?? '' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Status Verifikasi -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="font-bold text-slate-800 mb-4">Status Verifikasi</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 border rounded-xl">
                        <span class="text-sm text-slate-600">Dosen Pembimbing</span>
                        <span
                            class="px-2 py-1 {{ $kelompok->status == 'approved' ? 'bg-green-100 text-green-700' : ($kelompok->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }} text-[10px] font-bold rounded capitalize">{{ $kelompok->status == 'submitted' ? 'Menunggu' : ($kelompok->status == 'approved' ? 'Disetujui' : 'Ditolak') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 border rounded-xl">
                        <span class="text-sm text-slate-600">Kaprodi</span>
                        <span
                            class="px-2 py-1 {{ ($kelompok->status_kaprodi ?? 'menunggu') == 'disetujui' ? 'bg-green-100 text-green-700' : (($kelompok->status_kaprodi ?? 'menunggu') == 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }} text-[10px] font-bold rounded capitalize">{{ $kelompok->status_kaprodi ?? 'menunggu' }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 border rounded-xl">
                        <span class="text-sm text-slate-600">Admin</span>
                        <span
                            class="px-2 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded capitalize">Menunggu</span>
                    </div>
                </div>
            </div>

            <!-- Tindakan / Status Bimbingan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="font-bold text-slate-800 mb-4">Tindakan</h2>
                
                @if ($kelompok->catatan_kaprodi)
                 <div class="mb-4 p-3 rounded-xl bg-yellow-50 border border-yellow-200">
                    <p class="text-xs text-yellow-700 font-medium mb-1">Catatan Kaprodi:</p>
                    <p class="text-sm text-yellow-800">{{ $kelompok->catatan_kaprodi }}</p>
                </div>
                @endif
                
                <div class="space-y-3">
                    <button
                        class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Mulai Bimbingan
                    </button>
                    <button
                        class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Kirim Pesan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
