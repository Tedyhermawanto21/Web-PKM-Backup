@extends('layouts.app-modern')

@section('title', 'Manajemen Prodi')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Program Studi</h1>
        <p class="text-slate-500">Kelola data program studi.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6">
        <a href="{{ route('admin.prodis.create') }}"
            class="inline-block px-4 py-2 bg-uhamka-500 text-white rounded-lg">Tambah Prodi</a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-lg text-slate-900">Daftar Program Studi</h3>
        </div>

        @if ($prodis->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-900 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Nama Prodi</th>
                            <th class="px-6 py-4">Fakultas</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($prodis as $index => $prodi)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-mono text-slate-500">{{ $prodi->code ?? '-' }}</td>
                                <td class="px-6 py-4 font-bold">{{ $prodi->name }}</td>
                                <td class="px-6 py-4">{{ $prodi->fakultas }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('admin.prodis.edit', $prodi->id) }}"
                                        class="px-3 py-1.5 text-xs font-bold rounded-lg bg-gray-100">Edit</a>
                                    <form action="{{ route('admin.prodis.destroy', $prodi->id) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1.5 text-xs font-bold rounded-lg bg-red-50 text-red-600 ml-2"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus prodi ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-slate-500">Belum ada program studi yang terdaftar.</div>
        @endif
    </div>
@endsection
