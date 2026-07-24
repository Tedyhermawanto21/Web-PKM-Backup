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

                <!-- AI Assistant Section -->
                <div class="mb-10 bg-gradient-to-br from-indigo-50 to-blue-50 p-6 rounded-2xl border border-indigo-100 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-32 h-32 text-indigo-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                    </div>
                    
                    <h5 class="text-lg font-bold text-indigo-900 mb-2 flex items-center relative z-10">
                        <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Pemandu AI (AI Assistant)
                    </h5>
                    <p class="text-sm text-indigo-700 mb-4 relative z-10">Bingung menentukan judul, skema, atau dosen pembimbing? Ceritakan ide Anda secara bebas di bawah ini, dan AI kami akan meracikkannya untuk Anda!</p>
                    
                    <div class="relative z-10">
                        <textarea id="ai_ide_kasar" rows="3" class="w-full rounded-xl border-indigo-200 bg-white text-slate-800 placeholder-slate-400 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition-all duration-200 py-3 px-4 shadow-sm" placeholder="Contoh: Saya ingin membuat tempat sampah pintar menggunakan sensor yang bisa memilah sampah organik dan anorganik untuk mempermudah daur ulang..."></textarea>
                        
                        <button type="button" id="btn_generate_ai" class="mt-3 inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-md hover:bg-indigo-700 hover:shadow-lg transition-all active:scale-95">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            Dapatkan Rekomendasi AI
                        </button>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="ai_loading" class="hidden mt-6 text-center py-4 relative z-10">
                        <div class="inline-block animate-spin w-8 h-8 border-4 border-indigo-200 border-t-indigo-600 rounded-full mb-2"></div>
                        <p class="text-indigo-800 font-medium text-sm animate-pulse">AI sedang meracik ide Anda (ini mungkin memakan waktu beberapa detik)...</p>
                    </div>

                    <!-- Results Container -->
                    <div id="ai_results" class="hidden mt-6 grid grid-cols-1 lg:grid-cols-3 gap-4 relative z-10">
                        <!-- Items injected via JS -->
                    </div>
                </div>

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
                                        @foreach ($skemas as $skemaItem)
                                            <option value="{{ $skemaItem->nama }}"
                                                {{ old('skema') == $skemaItem->nama ? 'selected' : '' }}>
                                                {{ $skemaItem->nama }} ({{ $skemaItem->label }})
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

            // Handle AI Generation
            $('#btn_generate_ai').click(function() {
                let ideKasar = $('#ai_ide_kasar').val().trim();
                if(ideKasar.length < 10) {
                    alert('Tolong ceritakan ide Anda dengan lebih detail (minimal 10 karakter).');
                    return;
                }

                let $btn = $(this);
                let $loading = $('#ai_loading');
                let $results = $('#ai_results');

                $btn.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
                $results.hide().empty();
                $loading.removeClass('hidden');

                $.ajax({
                    url: '{{ route("mahasiswa.pkm_ai.generate") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ide_proposal: ideKasar
                    },
                    success: function(res) {
                        $loading.addClass('hidden');
                        $btn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');

                        const packages = Array.isArray(res.data) ? res.data : (res.data.packages || []);

                        if(res.status === 'success' && packages.length > 0) {
                            packages.forEach(function(item, index) {
                                // Default placeholders if AI didn't find specific ones
                                let schemaName = item.skema ? item.skema.split(' ')[0] : ''; 
                                let dosenId = item.dosen ? item.dosen.id : '';
                                let dosenName = item.dosen ? item.dosen.name : 'Dosen Umum';
                                let dosenMatch = item.dosen ? item.dosen.match_score : 0;
                                
                                let cardHtml = `
                                <div class="bg-white rounded-xl p-5 border border-indigo-100 shadow-sm hover:shadow-md transition-shadow relative">
                                    <div class="absolute top-0 right-0 bg-indigo-500 text-white text-xs font-bold px-2 py-1 rounded-bl-lg rounded-tr-xl">
                                        Opsi ${index + 1}
                                    </div>
                                    <div class="mb-3 mt-2">
                                        <p class="text-xs text-slate-500 font-bold uppercase mb-1">Usulan Judul:</p>
                                        <p class="text-sm font-semibold text-slate-800 line-clamp-3 leading-snug">${item.judul}</p>
                                    </div>
                                    <div class="mb-3 bg-slate-50 p-2 rounded-lg text-xs">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-slate-500">Skema:</span>
                                            <span class="font-bold text-indigo-700">${item.skema || 'Belum Terdeteksi'}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-500">Dosen Pembimbing:</span>
                                            <span class="font-bold text-slate-700 text-right">${dosenName} <br><span class="text-green-600">(${dosenMatch}% Cocok)</span></span>
                                        </div>
                                    </div>
                                    <button type="button" class="w-full py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-600 hover:text-white text-sm font-bold rounded-lg transition-colors applying-btn"
                                            data-judul="${item.judul}" 
                                            data-skema="${schemaName}"
                                            data-dosen="${dosenId}">
                                        Gunakan Ini
                                    </button>
                                </div>`;
                                $results.append(cardHtml);
                            });
                            $results.show();
                        } else {
                            alert('Gagal mendapatkan rekomendasi. Coba ubah ide Anda menjadi sedikit lebih spesifik.');
                        }
                    },
                    error: function(xhr, status, err) {
                        $loading.addClass('hidden');
                        $btn.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
                        alert('Terjadi kesalahan saat terhubung ke AI. Pastikan server AI berjalan.');
                        console.error('AJAX Error:', status, err, xhr.responseText);
                    }
                });
            });

            // Handle "Gunakan Ini" click
            $(document).on('click', '.applying-btn', function() {
                let judul = $(this).attr('data-judul');
                let skema = $(this).attr('data-skema');
                let dosen = $(this).attr('data-dosen');

                if(judul) $('#judul_kelompok').val(judul).focus();
                
                if(skema) {
                    // Try to map exact matched schema or select by contains
                    $('#skema option').each(function() {
                        if($(this).val() === skema || $(this).text().includes(skema)) {
                            $(this).prop('selected', true);
                        }
                    });
                }

                if(dosen) {
                    $('#dosen_pembimbing_id').val(dosen);
                }

                // Smooth scroll to the form
                $('html, body').animate({
                    scrollTop: $("#nama_kelompok").offset().top - 100
                }, 500);
            });
        });
    </script>
@endpush
