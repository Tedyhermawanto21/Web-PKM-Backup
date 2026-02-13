@extends('layouts.app-modern')

@section('title', 'Buat Kaprodi')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Buat Akun Kaprodi</h1>
            <p class="text-slate-500">Tambahkan Kepala Program Studi baru ke dalam sistem.</p>
        </div>
        <a href="{{ route('admin.kaprodis.index') }}"
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Form Data Kaprodi
                    </h6>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.kaprodis.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <!-- NIDN -->
                            <div>
                                <label for="nidn" class="block text-sm font-bold text-slate-700 mb-2">NIDN <span class="text-red-500">*</span></label>
                                <input type="text" class="block w-full rounded-xl border-slate-300 focus:border-uhamka-500 focus:ring-uhamka-500 shadow-sm py-3 px-4 @error('nidn') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" id="nidn" name="nidn" value="{{ old('nidn') }}" placeholder="Masukkan NIDN" required>
                                @error('nidn')
                                     <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" class="block w-full rounded-xl border-slate-300 focus:border-uhamka-500 focus:ring-uhamka-500 shadow-sm py-3 px-4 @error('name') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Nama lengkap beserta gelar" required>
                                @error('name')
                                     <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Program Studi -->
                            <div>
                                <label for="program_studi" class="block text-sm font-bold text-slate-700 mb-2">Program Studi <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select class="block w-full rounded-xl border-slate-300 focus:border-uhamka-500 focus:ring-uhamka-500 shadow-sm appearance-none py-3 px-4 @error('program_studi') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" id="program_studi" name="program_studi" required>
                                        <option value="">Pilih Program Studi</option>
                                        @foreach ($prodis as $prodi)
                                            <option value="{{ $prodi->name }}" {{ old('program_studi') == $prodi->name ? 'selected' : '' }}>
                                                {{ $prodi->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                         <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </div>
                                </div>
                                @error('program_studi')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password <span class="text-red-500">*</span></label>
                                    <input type="password" class="block w-full rounded-xl border-slate-300 focus:border-uhamka-500 focus:ring-uhamka-500 shadow-sm py-3 px-4 @error('password') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" id="password" name="password" placeholder="••••••••" required>
                                    @error('password')
                                         <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                                    <input type="password" class="block w-full rounded-xl border-slate-300 focus:border-uhamka-500 focus:ring-uhamka-500 shadow-sm py-3 px-4" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col-reverse sm:flex-row gap-3">
                            <a href="{{ route('admin.kaprodis.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-slate-300 shadow-sm text-sm font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uhamka-500 transition-all">
                                 Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-uhamka-600 hover:bg-uhamka-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uhamka-500 transition-all transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                Simpan Kaprodi
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
                    Informasi Akun
                </h6>
                <ul class="space-y-3 text-sm text-blue-800">
                    <li class="flex items-start gap-2">
                        <span class="bg-blue-200 text-blue-800 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">1</span>
                        <span><strong>NIDN:</strong> NIDN akan digunakan sebagai username dan bagian dari email login default.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="bg-blue-200 text-blue-800 rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</span>
                        <span><strong>Tanggung Jawab:</strong> Akun ini memiliki akses penuh untuk memverifikasi proposal dan membimbing mahasiswa di prodi terkait.</span>
                    </li>
                </ul>
            </div>

            <div class="bg-amber-50 rounded-2xl p-6 border border-amber-100">
                <h6 class="font-bold text-amber-800 flex items-center mb-4">
                     <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Keamanan
                </h6>
                <ul class="space-y-3 text-sm text-amber-800 list-disc ml-4">
                    <li>Gunakan password yang <strong>kuat</strong> dan tidak mudah ditebak.</li>
                    <li>Pastikan data NIDN sesuai dengan PDDIKTI.</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
