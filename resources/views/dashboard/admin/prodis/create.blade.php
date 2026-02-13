@extends('layouts.app-modern')

@section('title', 'Tambah Prodi')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Tambah Program Studi</h1>
            <p class="text-slate-500">Isi data untuk menambahkan program studi baru.</p>
        </div>
        <a href="{{ route('admin.prodis.index') }}"
            class="inline-flex items-center px-4 py-2 bg-white text-slate-700 text-sm font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm hover:shadow-md">
             <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form Column -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50">
                    <h6 class="font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-uhamka-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Form Data Prodi
                    </h6>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.prodis.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <!-- Nama Program Studi -->
                            <div>
                                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Program Studi <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <input type="text" class="pl-10 block w-full rounded-xl border-slate-300 focus:border-uhamka-500 focus:ring-uhamka-500 shadow-sm py-3 px-4 @error('name') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Teknik Informatika" required>
                                </div>
                                @error('name')
                                     <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Kode Prodi -->
                            <div>
                                <label for="code" class="block text-sm font-bold text-slate-700 mb-2">Kode Prodi <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                        </svg>
                                    </div>
                                    <input type="text" class="pl-10 block w-full rounded-xl border-slate-300 focus:border-uhamka-500 focus:ring-uhamka-500 shadow-sm py-3 px-4 @error('code') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" id="code" name="code" value="{{ old('code') }}" placeholder="Contoh: TI">
                                </div>
                                @error('code')
                                     <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Fakultas -->
                            <div>
                                <label for="fakultas" class="block text-sm font-bold text-slate-700 mb-2">Fakultas <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <input type="text" class="pl-10 block w-full rounded-xl border-slate-300 focus:border-uhamka-500 focus:ring-uhamka-500 shadow-sm py-3 px-4 @error('fakultas') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" id="fakultas" name="fakultas" value="{{ old('fakultas') }}" placeholder="Contoh: Fakultas Teknik Informatika" required>
                                </div>
                                @error('fakultas')
                                     <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col-reverse sm:flex-row gap-3">
                            <a href="{{ route('admin.prodis.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-slate-300 shadow-sm text-sm font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uhamka-500 transition-all">
                                 Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-uhamka-600 hover:bg-uhamka-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uhamka-500 transition-all transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                Simpan Prodi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Column -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100">
                <h6 class="font-bold text-blue-800 flex items-center mb-4">
                     <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informasi Prodi
                </h6>
                <ul class="space-y-3 text-sm text-blue-800">
                    <li class="flex items-start gap-2">
                        <span class="bg-blue-200 text-blue-800 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">1</span>
                        <span><strong>Nama Program Studi:</strong> Gunakan nama resmi yang terdaftar di universitas.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="bg-blue-200 text-blue-800 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</span>
                        <span><strong>Fakultas:</strong> Pastikan nama fakultas ditulis dengan lengkap.</span>
                    </li>
                </ul>
            </div>

            <div class="bg-amber-50 rounded-2xl p-6 border border-amber-100">
                <h6 class="font-bold text-amber-800 flex items-center mb-4">
                     <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    Catatan Penting
                </h6>
                <ul class="space-y-3 text-sm text-amber-800 list-disc ml-4">
                    <li>Kode Prodi bersifat <strong>opsional</strong>, namun disarankan untuk diisi jika ada.</li>
                    <li>Program Studi yang ditambahkan akan muncul saat pembuatan akun <strong>Dosen</strong> dan <strong>Kaprodi</strong>.</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
