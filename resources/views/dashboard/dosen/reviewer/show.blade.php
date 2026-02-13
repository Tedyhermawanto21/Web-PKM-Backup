@extends('layouts.app-modern')

@section('title', 'Review Proposal')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Review Proposal</h1>
            <p class="text-slate-500 text-sm">Berikan penilaian dan komentar untuk proposal ini</p>
        </div>
        <a href="{{ route('dosen.reviewer.index') }}"
            class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors">
            ← Kembali
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Proposal Detail -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Informasi Proposal</h2>
                </div>

                <h3 class="text-xl font-bold text-slate-900 mb-1">
                    {{ $proposal->judul_kelompok ?? ($proposal->judul_pkm ?? '') }}</h3>
                <p class="text-slate-500 mb-6">{{ $proposal->nama_kelompok ?? '-' }}</p>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500 uppercase font-semibold">Skema PKM</p>
                        <p class="font-bold text-blue-600">{{ $proposal->skema ?? ($proposal->skema_pkm ?? 'PKM-KC') }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500 uppercase font-semibold">Dosen Pembimbing</p>
                        <p class="font-bold text-slate-700">{{ $proposal->dosenPembimbing->name ?? 'Belum Ditentukan' }}</p>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <p class="text-xs text-slate-500 uppercase font-semibold mb-3">File Proposal</p>
                    @if ($proposal->file_proposal)
                        <a href="{{ Storage::url($proposal->file_proposal) }}" target="_blank"
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
            </div>

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
                    @foreach ($proposal->anggota as $mhs)
                        <div class="flex items-center p-4 border border-slate-100 rounded-xl bg-slate-50/50 relative">
                            @if ($loop->first)
                                <span
                                    class="absolute top-2 right-2 px-2 py-0.5 bg-blue-600 text-[10px] text-white font-bold rounded">KETUA</span>
                            @endif
                            <div
                                class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold mr-4">
                                {{ substr($mhs->nama ?? $mhs->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm leading-tight">{{ $mhs->nama ?? $mhs->name }}
                                </p>
                                <p class="text-xs text-slate-500">{{ $mhs->nim ?? '' }} • {{ $mhs->program_studi ?? '' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Review Form -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="font-bold text-slate-800 mb-4">Form Review</h2>

                @if ($reviewerAssignment->status === 'reviewed')
                    <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200">
                        <div class="flex items-center gap-2 text-green-700 font-bold mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Review Sudah Dikirim
                        </div>
                        <p class="text-sm text-green-600">Review Anda telah dikirim pada
                            {{ $reviewerAssignment->reviewed_at ? $reviewerAssignment->reviewed_at->format('d M Y H:i') : '-' }}
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Skor</label>
                            <div class="px-4 py-3 bg-slate-50 rounded-lg border border-slate-200">
                                <span class="text-2xl font-bold text-blue-600">{{ $reviewerAssignment->score }}</span>
                                <span class="text-slate-500">/100</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Komentar Review</label>
                            <div
                                class="px-4 py-3 bg-slate-50 rounded-lg border border-slate-200 text-slate-700 whitespace-pre-wrap">
                                {{ $reviewerAssignment->comments }}</div>
                        </div>
                    </div>
                @else
                    <form action="{{ route('dosen.reviewer.submit', $proposal->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Skor (0-100) <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="score" min="0" max="100" step="0.01"
                                value="{{ old('score') }}"
                                class="w-full rounded-xl border-slate-300 p-3 text-lg font-bold" placeholder="85" required>
                            <p class="text-xs text-slate-500 mt-1">Berikan skor antara 0 sampai 100</p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Komentar Review <span
                                    class="text-red-500">*</span></label>
                            <textarea name="comments" rows="6" class="w-full rounded-xl border-slate-300 p-3"
                                placeholder="Tuliskan komentar, saran, dan masukan untuk proposal ini..." required>{{ old('comments') }}</textarea>
                            <p class="text-xs text-slate-500 mt-1">Minimal 20 karakter</p>
                        </div>

                        <button type="submit"
                            class="w-full py-3 bg-uhamka-500 text-white rounded-xl font-bold hover:bg-uhamka-600 transition-colors">
                            Kirim Review
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
