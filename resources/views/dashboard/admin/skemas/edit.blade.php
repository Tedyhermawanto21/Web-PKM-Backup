@extends('layouts.app-modern')

@section('title', 'Edit Skema PKM')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Edit Skema PKM</h1>
        <p class="text-slate-500">Edit informasi skema PKM.</p>
    </div>

    <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('admin.skemas.update', $skema->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="nama" class="block text-sm font-bold text-slate-700 mb-2">Kode Skema</label>
                <input type="text" name="nama" id="nama" 
                    class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-uhamka-500 @error('nama') border-red-500 @enderror"
                    placeholder="Contoh: PKM-RE" value="{{ old('nama', $skema->nama) }}" required>
                @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-slate-400 mt-1">Kode unik untuk skema.</p>
            </div>

            <div class="mb-6">
                <label for="label" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap Skema</label>
                <input type="text" name="label" id="label" 
                    class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-uhamka-500 @error('label') border-red-500 @enderror"
                    placeholder="Contoh: Riset Eksakta" value="{{ old('label', $skema->label) }}" required>
                @error('label')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="panduan" class="block text-sm font-bold text-slate-700 mb-2">Panduan Skema (PDF/DOCX, Max 5MB)</label>
                @if($skema->panduan)
                    <div class="mb-2 text-sm text-slate-600">
                        File saat ini: <a href="{{ asset('storage/' . $skema->panduan) }}" target="_blank" class="text-uhamka-600 font-bold hover:underline">Download Panduan</a>
                    </div>
                @endif
                <input type="file"
                    class="w-full rounded-xl border-slate-300 bg-slate-50 text-slate-800 placeholder-slate-400 focus:bg-white focus:border-uhamka-500 focus:ring-4 focus:ring-uhamka-100 transition-all duration-200 py-3 px-4 shadow-sm @error('panduan') border-red-500 focus:ring-red-100 @enderror"
                    id="panduan" name="panduan" accept=".pdf,.doc,.docx">
                @error('panduan')
                    <p class="mt-2 text-sm text-red-600 flex items-center"><svg class="w-4 h-4 mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-8">
                <label for="warna" class="block text-sm font-bold text-slate-700 mb-2">Warna Badge (Tailwind Color)</label>
                <select name="warna" id="warna" class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-uhamka-500">
                    <option value="">Pilih Warna</option>
                    @foreach(['blue', 'green', 'red', 'yellow', 'purple', 'pink', 'indigo', 'orange', 'teal', 'cyan'] as $color)
                        <option value="{{ $color }}" {{ old('warna', $skema->warna) == $color ? 'selected' : '' }}>{{ ucfirst($color) }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-slate-400 mt-1">Digunakan untuk pewarnaan badge dan card dashboard.</p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('admin.skemas.index') }}" class="px-5 py-2.5 rounded-xl text-slate-600 font-bold hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-uhamka-500 text-white rounded-xl font-bold hover:bg-uhamka-600 transition-colors shadow-lg shadow-uhamka-200">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
