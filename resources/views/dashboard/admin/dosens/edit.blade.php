@extends('layouts.app-modern')

@section('title', 'Edit Dosen')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Edit Dosen</h1>
        <p class="text-slate-500">Perbarui data akun dosen.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.dosens.update', $user->id) }}" method="POST"
        class="max-w-lg bg-white p-6 rounded-xl border border-slate-100">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">NIDN</label>
            <input type="text" name="nidn" value="{{ old('nidn', $user->nidn) }}"
                class="mt-1 block w-full border rounded-lg p-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                class="mt-1 block w-full border rounded-lg p-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Program Studi</label>
            <select name="program_studi" class="mt-1 block w-full border rounded-lg p-2" required>
                <option value="">Pilih Program Studi</option>
                @foreach ($prodis as $prodi)
                    <option value="{{ $prodi->name }}"
                        {{ old('program_studi', $user->program_studi) == $prodi->name ? 'selected' : '' }}>
                        {{ $prodi->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Password (kosongkan jika tidak diubah)</label>
            <input type="password" name="password" class="mt-1 block w-full border rounded-lg p-2">
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-slate-700">Confirm Password</label>
            <input type="password" name="password_confirmation" class="mt-1 block w-full border rounded-lg p-2">
        </div>
        <div>
            <button type="submit" class="px-4 py-2 bg-uhamka-500 text-white rounded-lg">Simpan Perubahan</button>
            <a href="{{ route('admin.dosens.index') }}" class="ml-2 text-sm text-slate-500">Batal</a>
        </div>
    </form>
@endsection
