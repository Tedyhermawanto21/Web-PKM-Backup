@extends('layouts.app-modern')

@section('title', 'Edit Jadwal')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Jadwal</h1>
            <p class="text-slate-500">Perbarui informasi jadwal kegiatan PKM.</p>
        </div>
        <a href="{{ route('admin.schedules.index') }}"
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
                        <svg class="w-5 h-5 text-uhamka-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Form Edit Jadwal
                    </h6>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.schedules.update', $schedule->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Type Selection -->
                             <div>
                                <label for="type" class="block text-sm font-bold text-slate-700 mb-2">Tipe Jadwal <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select class="block w-full rounded-xl border-slate-300 focus:border-uhamka-500 focus:ring-uhamka-500 shadow-sm appearance-none py-3 px-4 @error('type') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" id="type" name="type" required>
                                        <option value="">Pilih Tipe Jadwal</option>
                                        @foreach ($types as $key => $value)
                                            <option value="{{ $key }}" {{ old('type', $schedule->type) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                         <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                    </div>
                                </div>
                                @error('type')
                                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Schedule Name -->
                             <div>
                                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Jadwal <span class="text-red-500">*</span></label>
                                <input type="text" class="block w-full rounded-xl border-slate-300 focus:border-uhamka-500 focus:ring-uhamka-500 shadow-sm py-3 px-4 @error('name') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" id="name" name="name" value="{{ old('name', $schedule->name) }}" placeholder="Contoh: Periode Upload Proposal Semester Genap 2025" required>
                                @error('name')
                                     <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Date Range -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="start_date" class="block text-sm font-bold text-slate-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" class="block w-full rounded-xl border-slate-300 focus:border-uhamka-500 focus:ring-uhamka-500 shadow-sm py-3 px-4 @error('start_date') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" id="start_date" name="start_date" value="{{ old('start_date', $schedule->start_date->format('Y-m-d\TH:i')) }}" required>
                                    @error('start_date')
                                         <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-bold text-slate-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" class="block w-full rounded-xl border-slate-300 focus:border-uhamka-500 focus:ring-uhamka-500 shadow-sm py-3 px-4 @error('end_date') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" id="end_date" name="end_date" value="{{ old('end_date', $schedule->end_date->format('Y-m-d\TH:i')) }}" required>
                                    @error('end_date')
                                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-bold text-slate-700 mb-2">Deskripsi</label>
                                <textarea class="block w-full rounded-xl border-slate-300 focus:border-uhamka-500 focus:ring-uhamka-500 shadow-sm py-3 px-4 @error('description') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror" id="description" name="description" rows="3" placeholder="Deskripsi atau catatan tambahan (opsional)">{{ old('description', $schedule->description) }}</textarea>
                                @error('description')
                                     <p class="mt-2 text-sm text-red-600 flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Activation Checkbox -->
                            <div class="flex items-start bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" id="is_active" name="is_active" value="1" class="w-5 h-5 text-uhamka-600 border-gray-300 rounded focus:ring-uhamka-500" {{ old('is_active', $schedule->is_active) ? 'checked' : '' }}>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_active" class="font-bold text-slate-700">Aktifkan jadwal ini</label>
                                    <p class="text-slate-500">Jadwal yang tidak aktif tidak akan berlaku meskipun tanggalnya sudah tiba.</p>
                                </div>
                            </div>
                        </div>

                         <div class="mt-8 flex flex-col-reverse sm:flex-row gap-3">
                            <a href="{{ route('admin.schedules.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-slate-300 shadow-sm text-sm font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uhamka-500 transition-all">
                                 Batal
                            </a>
                            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-uhamka-600 hover:bg-uhamka-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uhamka-500 transition-all transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Update Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info & Status Column -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50">
                    <h6 class="font-bold text-slate-800">Status Saat Ini</h6>
                </div>
                <div class="p-6">
                     <div class="space-y-4">
                        <div class="flex justify-between items-center bg-slate-50 p-3 rounded-lg border border-slate-100">
                            <span class="text-sm font-bold text-slate-600">Status Aktif</span>
                             @if ($schedule->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">Non-aktif</span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center bg-slate-50 p-3 rounded-lg border border-slate-100">
                             <span class="text-sm font-bold text-slate-600">Waktu</span>
                              @if ($schedule->isOngoing())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Sedang Berlangsung</span>
                            @elseif($schedule->isPast())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600">Sudah Lewat</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Akan Datang</span>
                            @endif
                        </div>
                         <div class="pt-4 border-t border-slate-100 space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500">Dibuat</span>
                                <span class="text-slate-800 font-medium">{{ $schedule->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500">Update Terakhir</span>
                                <span class="text-slate-800 font-medium">{{ $schedule->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                         </div>
                     </div>
                </div>
            </div>

             <div class="bg-amber-50 rounded-2xl p-6 border border-amber-100">
                <h6 class="font-bold text-amber-800 flex items-center mb-4">
                     <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    Catatan
                </h6>
                <ul class="space-y-3 text-sm text-amber-800 list-disc ml-4">
                     <li>Perubahan akan segera berlaku untuk seluruh pengguna setelah disimpan.</li>
                     <li>Jika Anda menonaktifkan jadwal yang sedang berlangsung, mahasiswa tidak akan bisa mengakses fitur terkait.</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
