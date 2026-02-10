@extends('layouts.app-modern')

@section('title', 'Upload Ulang Proposal')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Upload Ulang Proposal</h1>
            <p class="text-slate-500">Perbaiki dan upload ulang file proposal Anda.</p>
        </div>
        <a href="{{ route('mahasiswa.upload.index') }}"
            class="inline-flex items-center px-4 py-2 bg-white text-slate-700 text-sm font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm hover:shadow-md">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <!-- Alert Messages -->
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

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-bold">Terdapat kesalahan:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 ml-7">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Admin Notes -->
    @if ($proposal->catatan_admin)
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="font-bold text-red-700 mb-1">Catatan Admin:</p>
                <p class="text-sm text-red-800">{{ $proposal->catatan_admin }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <!-- Upload File Baru (Left - 2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="bg-uhamka-900 px-6 py-4 border-b border-uhamka-800">
                <h6 class="font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    Upload File Baru
                </h6>
            </div>
            <div class="p-6">
                <form action="{{ route('mahasiswa.upload.update', $proposal->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nama Kelompok (readonly) -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kelompok</label>
                        <input type="text" value="{{ $proposal->nama_kelompok }}" readonly
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 outline-none" />
                    </div>

                    <!-- Judul PKM (readonly) -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Judul PKM</label>
                        <input type="text" value="{{ $proposal->judul_kelompok }}" readonly
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-600 outline-none" />
                    </div>

                    <!-- File Saat Ini -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">File Saat Ini</label>
                        @if ($proposal->file_proposal)
                            <a href="{{ Storage::url($proposal->file_proposal) }}" target="_blank"
                                class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition-colors text-sm font-bold">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Download File Lama
                            </a>
                        @endif
                    </div>

                    <!-- File Upload -->
                    <div class="mb-6">
                        <label for="file_proposal" class="block text-sm font-bold text-slate-700 mb-2">
                            File Proposal Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="file" id="file_proposal" name="file_proposal" accept=".pdf,.doc,.docx" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-800 focus:ring-2 focus:ring-uhamka-500 focus:border-uhamka-500 transition-all outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-uhamka-50 file:text-uhamka-700 hover:file:bg-uhamka-100" />
                        <p class="mt-2 text-xs text-slate-400">Format: PDF, DOC, DOCX | Maksimal 20MB</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-uhamka-700 hover:bg-uhamka-800 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            Upload Ulang
                        </button>
                        <a href="{{ route('mahasiswa.upload.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl shadow-md hover:shadow-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Informasi (Right - 1 col) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-fit">
            <div class="bg-uhamka-900 px-6 py-4 border-b border-uhamka-800">
                <h6 class="font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informasi
                </h6>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="pb-4 border-b border-slate-50">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Skema</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-uhamka-100 text-uhamka-700">
                            {{ $proposal->skema }}
                        </span>
                    </div>
                    <div class="pb-4 border-b border-slate-50">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Dosen Pembimbing</span>
                        @if ($proposal->dosenPembimbing)
                            <div class="flex items-center gap-3 mt-1">
                                <div
                                    class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-sm font-bold text-slate-600">
                                    {{ substr($proposal->dosenPembimbing->name, 0, 1) }}
                                </div>
                                <p class="text-slate-900 font-bold">{{ $proposal->dosenPembimbing->name }}</p>
                            </div>
                        @endif
                    </div>
                    <div>
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Status</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">Ditolak</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
