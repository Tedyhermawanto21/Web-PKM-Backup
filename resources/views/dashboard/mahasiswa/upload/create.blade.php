@extends('layouts.app-modern')

@section('title', 'Upload Proposal PKM')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Upload Proposal PKM</h1>
            <p class="text-slate-500">Upload file proposal yang sudah disetujui.</p>
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

    <!-- Upload Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="bg-uhamka-900 px-6 py-4 border-b border-uhamka-800">
            <h6 class="font-bold text-white flex items-center">
                <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                Pilih Proposal dan Upload File
            </h6>
        </div>
        <div class="p-6">
            <!-- Info Note -->
            <div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-100 flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-blue-700">
                    Pilih proposal yang sudah disetujui oleh Dosen dan Kaprodi, kemudian upload file proposal dalam
                    format PDF, DOC, atau DOCX (maksimal 20MB).
                </p>
            </div>

            @if ($proposals->count() > 0)
                <form action="{{ route('mahasiswa.upload.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Pilih Proposal -->
                    <div class="mb-6">
                        <label for="proposal_id" class="block text-sm font-bold text-slate-700 mb-2">
                            Pilih Proposal <span class="text-red-500">*</span>
                        </label>
                        <select name="proposal_id" id="proposal_id"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-800 focus:ring-2 focus:ring-uhamka-500 focus:border-uhamka-500 transition-all outline-none"
                            required>
                            <option value="">-- Pilih Proposal --</option>
                            @foreach ($proposals as $proposal)
                                <option value="{{ $proposal->id }}"
                                    {{ old('proposal_id') == $proposal->id ? 'selected' : '' }}>
                                    {{ $proposal->nama_kelompok }} - {{ $proposal->judul_kelompok }}
                                    ({{ $proposal->skema }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-6">
                        <label for="file_proposal" class="block text-sm font-bold text-slate-700 mb-2">
                            File Proposal <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="file" id="file_proposal" name="file_proposal" accept=".pdf,.doc,.docx"
                                required
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-800 focus:ring-2 focus:ring-uhamka-500 focus:border-uhamka-500 transition-all outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-uhamka-50 file:text-uhamka-700 hover:file:bg-uhamka-100" />
                        </div>
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
                            Upload Proposal
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
            @else
                <!-- No Proposals Available -->
                <div class="text-center py-8">
                    <div class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Belum Ada Proposal yang Siap</h3>
                    <p class="text-slate-500 mb-6 max-w-md mx-auto">
                        Proposal harus disetujui oleh Dosen dan Kaprodi terlebih dahulu sebelum dapat diupload.
                    </p>
                    <a href="{{ route('mahasiswa.pengajuan_kelompok_pkm.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-uhamka-700 hover:bg-uhamka-800 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Lihat Pengajuan PKM
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
