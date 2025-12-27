@extends('layouts.app-modern')

@section('title', 'Detail Proposal PKM')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Proposal PKM</h1>
            <p class="text-slate-500">Lihat detail informasi dan status pengajuan Anda.</p>
        </div>
        <a href="{{ route('mahasiswa.proposals.index') }}" class="inline-flex items-center px-4 py-2 bg-white text-slate-700 text-sm font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm hover:shadow-md">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
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

    <!-- Status Badge Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center mb-8">
        <h4 class="text-lg font-bold text-slate-900 mb-6">Status Proposal</h4>
        
        @if ($proposal->status == 'draft')
             <div class="inline-flex flex-col items-center">
                <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold bg-slate-100 text-slate-600 mb-4">
                    <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Draft
                </span>
                <p class="text-slate-500">Proposal masih dalam bentuk draft dan belum diajukan</p>
            </div>
        @elseif($proposal->status == 'menunggu_approval')
            <div class="inline-flex flex-col items-center">
                <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold bg-yellow-100 text-yellow-700 mb-4 animate-pulse">
                     <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Menunggu Approval
                </span>
                <p class="text-slate-500">Proposal sedang menunggu persetujuan dari dosen pembimbing</p>
            </div>
        @elseif($proposal->status == 'disetujui')
             <div class="inline-flex flex-col items-center">
                 <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold bg-green-100 text-green-700 mb-4">
                    <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Disetujui
                </span>
                <p class="text-green-600 font-bold">Selamat! Proposal Anda telah disetujui oleh dosen pembimbing</p>
            </div>
        @elseif($proposal->status == 'ditolak')
             <div class="inline-flex flex-col items-center">
                 <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold bg-red-100 text-red-700 mb-4">
                     <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Ditolak
                </span>
                <p class="text-red-500 font-bold">Proposal ditolak oleh dosen pembimbing</p>
                @if ($proposal->catatan_penolakan)
                     <div class="mt-4 p-4 bg-red-50 rounded-xl border border-red-100 max-w-2xl text-left">
                        <strong class="text-red-800 block mb-1"><i class="fas fa-info-circle mr-1"></i> Catatan Penolakan:</strong>
                        <p class="text-red-700">{{ $proposal->catatan_penolakan }}</p>
                         <p class="text-xs text-red-600 mt-2">Anda dapat mengedit dan mengajukan proposal ini kembali.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Informasi Kelompok -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-full">
            <div class="bg-uhamka-900 px-6 py-4 border-b border-uhamka-800">
                <h6 class="font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Informasi Kelompok
                </h6>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                     <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Nama Kelompok</span>
                        <p class="text-slate-800 font-semibold">{{ $proposal->nama_kelompok }}</p>
                    </div>
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                         <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Judul PKM</span>
                        <p class="text-slate-800 font-semibold leading-relaxed">{{ $proposal->judul_kelompok }}</p>
                    </div>
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                         <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Skema</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $proposal->skema }}
                        </span>
                    </div>
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                         <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Dosen Pembimbing</span>
                        @if ($proposal->dosenPembimbing)
                            <div class="flex items-center gap-3 mt-1">
                                <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-sm font-bold text-slate-600">
                                    {{ substr($proposal->dosenPembimbing->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-slate-900 font-bold">{{ $proposal->dosenPembimbing->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $proposal->dosenPembimbing->program_studi }}</p>
                                </div>
                            </div>
                        @else
                             <p class="text-slate-500 italic">Belum dipilih</p>
                        @endif
                    </div>
                     <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                         <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Tanggal Pengajuan</span>
                        <p class="text-slate-800">{{ $proposal->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Anggota -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-full">
            <div class="bg-uhamka-900 px-6 py-4 border-b border-uhamka-800">
                 <h6 class="font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Anggota Kelompok <span class="ml-2 px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ $proposal->anggota->count() }} Orang</span>
                </h6>
            </div>
             <div class="p-6">
                 <div class="space-y-4">
                     @foreach ($proposal->anggota as $index => $anggota)
                         <div class="flex items-center p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                             <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold mr-4">
                                {{ $index + 1 }}
                             </div>
                             <div>
                                 <div class="flex items-center gap-2 mb-1">
                                    @if ($anggota->posisi == 'ketua')
                                        <span class="px-2 py-0.5 bg-uhamka-100 text-uhamka-700 text-[10px] font-bold uppercase rounded-md">Ketua</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] font-bold uppercase rounded-md">Anggota</span>
                                    @endif
                                    <h6 class="font-bold text-slate-900">{{ $anggota->nama }}</h6>
                                 </div>
                                 <p class="text-xs text-slate-500">{{ $anggota->nim }} • {{ $anggota->program_studi }}</p>
                             </div>
                         </div>
                     @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    @if (in_array($proposal->status, ['draft', 'ditolak']))
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center">
            <h5 class="font-bold text-slate-900 mb-4">Tindakan</h5>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('mahasiswa.proposals.edit', $proposal->id) }}"
                    class="inline-flex items-center px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Edit Proposal
                </a>
                <form action="{{ route('mahasiswa.proposals.destroy', $proposal->id) }}" method="POST"
                    class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proposal ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-1">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Hapus Proposal
                    </button>
                </form>
            </div>
        </div>
    @endif

@endsection
