@extends('layouts.app-modern')

@section('title', 'Detail Kelompok PKM')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Kelompok PKM</h1>
            <p class="text-slate-500 text-sm">Informasi lengkap tentang kelompok PKM Anda.</p>
        </div>
        <a href="{{ route('mahasiswa.kelompoks.index') }}"
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
                        <p class="text-xs text-slate-500 uppercase font-semibold">Dosen Pembimbing</p>
                        <p class="font-bold text-slate-700">{{ $kelompok->dosenPembimbing->name ?? 'Belum Ditentukan' }}</p>
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
                    @foreach ($kelompok->anggota as $mhs)
                        <div class="flex items-center p-4 border border-slate-100 rounded-xl bg-slate-50/50 relative">
                            @if ($mhs->posisi == 'ketua')
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
                            class="px-2 py-1 {{ $kelompok->status_dosen == 'disetujui' ? 'bg-green-100 text-green-700' : ($kelompok->status_dosen == 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }} text-[10px] font-bold rounded capitalize">{{ $kelompok->status_dosen ?? 'menunggu' }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 border rounded-xl">
                        <span class="text-sm text-slate-600">Kaprodi</span>
                        <span
                            class="px-2 py-1 {{ $kelompok->status_kaprodi == 'disetujui' ? 'bg-green-100 text-green-700' : ($kelompok->status_kaprodi == 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }} text-[10px] font-bold rounded capitalize">{{ $kelompok->status_kaprodi ?? 'menunggu' }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 border rounded-xl">
                        <span class="text-sm text-slate-600">Admin</span>
                        <span
                            class="px-2 py-1 {{ $kelompok->status_admin == 'disetujui' ? 'bg-green-100 text-green-700' : ($kelompok->status_admin == 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }} text-[10px] font-bold rounded capitalize">{{ $kelompok->status_admin ?? 'menunggu' }}</span>
                    </div>
                </div>
            </div>

            <!-- Tindakan (Opsional, jika ada) -->
            <!-- Jika ingin menambahkan tombol edit/hapus untuk kelompok di sini -->
             @if (in_array($kelompok->status, ['draft', 'ditolak']) && Auth::id() === $kelompok->ketua_id)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                    <h2 class="font-bold text-slate-800 mb-4">Tindakan</h2>
                    <div class="space-y-3">
                         <!-- Note: Adjust routes if needed, usually managed via Proposal for editing -->
                         <a href="{{ route('mahasiswa.pengajuan_kelompok_pkm.edit', $kelompok->id) }}"
                            class="w-full py-2.5 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Kelompok
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
