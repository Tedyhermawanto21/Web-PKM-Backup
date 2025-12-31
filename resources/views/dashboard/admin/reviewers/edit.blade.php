@extends('layouts.app-modern')

@section('title', 'Edit Reviewer')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Edit Reviewer</h1>
        <p class="text-slate-500">Perbarui data akun reviewer.</p>
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

    <form action="{{ route('admin.reviewers.update', $user->id) }}" method="POST"
        class="max-w-lg bg-white p-6 rounded-xl border border-slate-100">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                class="mt-1 block w-full border rounded-lg p-2" required>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                class="mt-1 block w-full border rounded-lg p-2" required>
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
            <a href="{{ route('admin.reviewers.index') }}" class="ml-2 text-sm text-slate-500">Batal</a>
        </div>
    </form>
@endsection
