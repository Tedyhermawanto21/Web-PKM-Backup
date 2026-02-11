@extends('layouts.app-modern')

@section('title', 'Tambah Skema PKM')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Tambah Skema PKM</h1>
        <p class="text-slate-500">Tambahkan jenis skema PKM baru ke dalam sistem.</p>
    </div>

    <div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('admin.skemas.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-6">
                <label for="nama" class="block text-sm font-bold text-slate-700 mb-2">Kode Skema</label>
                <input type="text" name="nama" id="nama" 
                    class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-uhamka-500 @error('nama') border-red-500 @enderror"
                    placeholder="Contoh: PKM-RE" value="{{ old('nama') }}" required>
                @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-slate-400 mt-1">Kode unik untuk skema.</p>
            </div>

            <div class="mb-6">
                <label for="label" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap Skema</label>
                <input type="text" name="label" id="label" 
                    class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-uhamka-500 @error('label') border-red-500 @enderror"
                    placeholder="Contoh: Riset Eksakta" value="{{ old('label') }}" required>
                @error('label')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="panduan" class="block text-sm font-bold text-slate-700 mb-2">Panduan Skema (PDF/DOCX, Max 5MB)</label>
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
                    <option value="blue" {{ old('warna') == 'blue' ? 'selected' : '' }}>Blue</option>
                    <option value="green" {{ old('warna') == 'green' ? 'selected' : '' }}>Green</option>
                    <option value="red" {{ old('warna') == 'red' ? 'selected' : '' }}>Red</option>
                    <option value="yellow" {{ old('warna') == 'yellow' ? 'selected' : '' }}>Yellow</option>
                    <option value="purple" {{ old('warna') == 'purple' ? 'selected' : '' }}>Purple</option>
                    <option value="pink" {{ old('warna') == 'pink' ? 'selected' : '' }}>Pink</option>
                    <option value="indigo" {{ old('warna') == 'indigo' ? 'selected' : '' }}>Indigo</option>
                    <option value="orange" {{ old('warna') == 'orange' ? 'selected' : '' }}>Orange</option>
                    <option value="teal" {{ old('warna') == 'teal' ? 'selected' : '' }}>Teal</option>
                    <option value="cyan" {{ old('warna') == 'cyan' ? 'selected' : '' }}>Cyan</option>
                </select>
                <p class="text-xs text-slate-400 mt-1">Digunakan untuk pewarnaan badge dan card dashboard.</p>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('admin.skemas.index') }}" class="px-5 py-2.5 rounded-xl text-slate-600 font-bold hover:bg-slate-50 transition-colors">Batal</a>
                <button type="submit" class="px-5 py-2.5 bg-uhamka-500 text-white rounded-xl font-bold hover:bg-uhamka-600 transition-colors shadow-lg shadow-uhamka-200">Simpan Skema</button>
            </div>
        </form>
    </div>
@endsection
