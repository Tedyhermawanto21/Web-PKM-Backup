@extends('layouts.app-modern')

@section('title', 'Pengajuan Kelompok PKM')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pengajuan Kelompok PKM</h1>
            <p class="text-slate-500">Isi formulir berikut untuk mendaftarkan Kelompok PKM Anda.</p>
        </div>
        <a href="{{ route('mahasiswa.pengajuan_kelompok_pkm.index') }}"
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

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50">
            <h6 class="font-bold text-slate-800">Formulir Proposal PKM</h6>
        </div>
        <div class="p-8">
            <form action="{{ route('mahasiswa.pengajuan_kelompok_pkm.store') }}" method="POST">
                @csrf

                <!-- Informasi Kelompok -->
                <div class="mb-10">
                    <h5 class="text-lg font-bold text-uhamka-900 border-b border-slate-100 pb-3 mb-8 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-uhamka-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Informasi Kelompok
                    </h5>

                    <div class="space-y-6">
                        <div>
                            <label for="nama_kelompok"
                                class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Nama Kelompok
                                <span class="text-red-500">*</span></label>
                            <input type="text"
                                class="w-full rounded-xl border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-uhamka-500 focus:ring-4 focus:ring-uhamka-100 transition-all duration-200 py-3 px-4 shadow-sm @error('nama_kelompok') border-red-500 focus:ring-red-100 @enderror"
                                id="nama_kelompok" name="nama_kelompok" value="{{ old('nama_kelompok') }}"
                                placeholder="Contoh: Tim Inovasi Teknologi" required>
                            @error('nama_kelompok')
                                <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="judul_kelompok"
                                class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Judul PKM <span
                                    class="text-red-500">*</span></label>
                            <textarea
                                class="w-full rounded-xl border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-uhamka-500 focus:ring-4 focus:ring-uhamka-100 transition-all duration-200 py-3 px-4 shadow-sm @error('judul_kelompok') border-red-500 focus:ring-red-100 @enderror"
                                id="judul_kelompok" name="judul_kelompok" rows="3" placeholder="Masukkan judul lengkap proposal PKM Anda"
                                required>{{ old('judul_kelompok') }}</textarea>
                            @error('judul_kelompok')
                                <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label for="skema"
                                    class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Skema PKM
                                    <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select
                                        class="w-full rounded-xl border-slate-300 bg-slate-50 text-slate-800 focus:bg-white focus:border-uhamka-500 focus:ring-4 focus:ring-uhamka-100 transition-all duration-200 py-3 px-4 shadow-sm appearance-none @error('skema') border-red-500 focus:ring-red-100 @enderror"
                                        id="skema" name="skema" required>
                                        <option value="">-- Pilih Skema PKM --</option>
                                        <option value="PKM-KC" {{ old('skema') == 'PKM-KC' ? 'selected' : '' }}>PKM-KC
                                            (Karsa Cipta)</option>
                                        <option value="PKM-RE" {{ old('skema') == 'PKM-RE' ? 'selected' : '' }}>PKM-RE
                                            (Riset Eksakta)</option>
                                        <option value="PKM-GT" {{ old('skema') == 'PKM-GT' ? 'selected' : '' }}>PKM-GT
                                            (Gagasan Tertulis)</option>
                                        <option value="PKM-AI" {{ old('skema') == 'PKM-AI' ? 'selected' : '' }}>PKM-AI
                                            (Artikel Ilmiah)</option>
                                        <option value="PKM-PM" {{ old('skema') == 'PKM-PM' ? 'selected' : '' }}>PKM-PM
                                            (Pengabdian Masyarakat)</option>
                                        <option value="PKM-K" {{ old('skema') == 'PKM-K' ? 'selected' : '' }}>PKM-K
                                            (Kewirausahaan)</option>
                                        <option value="PKM-VGK" {{ old('skema') == 'PKM-VGK' ? 'selected' : '' }}>PKM-VGK
                                            (Video Gagasan Konstruktif)</option>
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                                @error('skema')
                                    <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="dosen_pembimbing_id"
                                    class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Dosen
                                    Pembimbing <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select
                                        class="w-full rounded-xl border-slate-300 bg-slate-50 text-slate-800 focus:bg-white focus:border-uhamka-500 focus:ring-4 focus:ring-uhamka-100 transition-all duration-200 py-3 px-4 shadow-sm appearance-none @error('dosen_pembimbing_id') border-red-500 focus:ring-red-100 @enderror"
                                        id="dosen_pembimbing_id" name="dosen_pembimbing_id" required>
                                        <option value="">-- Pilih Dosen Pembimbing --</option>
                                        @foreach ($dosens as $dosen)
                                            <option value="{{ $dosen->id }}"
                                                {{ old('dosen_pembimbing_id') == $dosen->id ? 'selected' : '' }}>
                                                {{ $dosen->name }} - {{ $dosen->program_studi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div
                                        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </div>
                                <p
                                    class="mt-2 text-xs text-slate-500 flex items-center bg-blue-50 p-2 rounded-lg border border-blue-100 text-blue-700">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Proposal akan menunggu persetujuan dari dosen pembimbing yang dipilih
                                </p>
                                @error('dosen_pembimbing_id')
                                    <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Ketua -->
                <div class="mb-10">
                    <h5 class="text-lg font-bold text-uhamka-900 border-b border-slate-100 pb-3 mb-8 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-uhamka-500" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Ketua Kelompok
                    </h5>
                    <div
                        class="bg-gradient-to-r from-blue-50 to-white border border-blue-100 rounded-2xl p-6 flex items-start gap-4 shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-3 bg-blue-100 rounded-xl text-blue-600 shadow-inner">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-xl text-blue-900">{{ Auth::user()->name }}</p>
                            <p class="text-blue-700 font-medium mb-1">{{ Auth::user()->nim }} •
                                {{ Auth::user()->program_studi }}</p>
                            <p
                                class="text-sm text-blue-500 italic mt-2 bg-white/50 px-3 py-1 rounded-lg inline-block border border-blue-50">
                                Anda secara otomatis terdaftar sebagai ketua kelompok</p>
                        </div>
                    </div>
                </div>

                <!-- Anggota Kelompok -->
                <div class="mb-10">
                    <h5 class="text-lg font-bold text-uhamka-900 border-b border-slate-100 pb-3 mb-8 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-uhamka-500" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Anggota Kelompok <span class="text-sm font-normal text-slate-500 ml-2">(Maksimal 4 Anggota)</span>
                    </h5>

                    <div id="anggota-container" class="space-y-6">
                        @for ($i = 0; $i < 4; $i++)
                            <div
                                class="border border-slate-200 rounded-2xl p-6 relative bg-slate-50 anggota-card group hover:border-uhamka-300 hover:shadow-md transition-all duration-300">
                                <div class="flex justify-between items-center mb-6">
                                    <div class="flex items-center">
                                        <span
                                            class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm mr-3 group-hover:bg-uhamka-100 group-hover:text-uhamka-700 transition-colors">{{ $i + 1 }}</span>
                                        <h6 class="font-bold text-slate-700 group-hover:text-uhamka-800 transition-colors">
                                            Anggota</h6>
                                    </div>
                                    @if ($i > 0)
                                        <button type="button"
                                            class="text-red-400 hover:text-red-600 text-sm font-bold flex items-center remove-anggota bg-white px-3 py-1 rounded-full border border-slate-200 shadow-sm hover:shadow active:scale-95 transition-all">
                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wide">Nama
                                            <span class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="w-full rounded-xl border-slate-300 bg-white focus:border-uhamka-500 focus:ring-4 focus:ring-uhamka-100 text-slate-800 py-3 px-4 shadow-sm transition-all @error('anggota.' . $i . '.nama') border-red-500 @enderror"
                                            name="anggota[{{ $i }}][nama]"
                                            value="{{ old('anggota.' . $i . '.nama') }}"
                                            placeholder="Nama lengkap anggota" required>
                                        @error('anggota.' . $i . '.nama')
                                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wide">NIM
                                            <span class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="w-full rounded-xl border-slate-300 bg-white focus:border-uhamka-500 focus:ring-4 focus:ring-uhamka-100 text-slate-800 py-3 px-4 shadow-sm transition-all @error('anggota.' . $i . '.nim') border-red-500 @enderror"
                                            name="anggota[{{ $i }}][nim]"
                                            value="{{ old('anggota.' . $i . '.nim') }}" placeholder="NIM anggota"
                                            required>
                                        @error('anggota.' . $i . '.nim')
                                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-500 uppercase mb-2 tracking-wide">Program
                                            Studi <span class="text-red-500">*</span></label>
                                        <input type="text"
                                            class="w-full rounded-xl border-slate-300 bg-white focus:border-uhamka-500 focus:ring-4 focus:ring-uhamka-100 text-slate-800 py-3 px-4 shadow-sm transition-all @error('anggota.' . $i . '.program_studi') border-red-500 @enderror"
                                            name="anggota[{{ $i }}][program_studi]"
                                            value="{{ old('anggota.' . $i . '.program_studi') }}"
                                            placeholder="Program studi anggota" required>
                                        @error('anggota.' . $i . '.program_studi')
                                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center gap-4 pt-8 border-t border-slate-100">
                    <button type="submit"
                        class="inline-flex items-center px-8 py-4 bg-uhamka-600 text-white font-bold text-lg rounded-xl shadow-lg hover:bg-uhamka-700 hover:shadow-xl transition-all transform hover:-translate-y-1">
                        <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Ajukan Kelompok PKM
                    </button>
                    <a href="{{ route('mahasiswa.pengajuan_kelompok_pkm.index') }}"
                        class="inline-flex items-center px-8 py-4 bg-white text-slate-600 font-bold text-lg rounded-xl border border-slate-200 hover:bg-slate-50 hover:text-slate-800 transition-all">
                        <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle remove anggota button
            $(document).on('click', '.remove-anggota', function() {
                // Find closest .anggota-card and remove it
                $(this).closest('.anggota-card').fadeOut(300, function() {
                    $(this).remove();
                });
            });
        });
    </script>
@endpush
