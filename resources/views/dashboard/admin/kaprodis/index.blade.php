@extends('layouts.app-modern')

@section('title', 'Manajemen Kaprodi')

@section('content')
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Kaprodi</h1>
            <p class="text-slate-500">Kelola akun Kepala Program Studi (Kaprodi).</p>
        </div>
        <a href="{{ route('admin.kaprodis.create') }}"
            class="inline-flex items-center px-4 py-2 bg-uhamka-600 text-white text-sm font-bold rounded-xl hover:bg-uhamka-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
             <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Buat Kaprodi
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 flex items-center gap-3">
             <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
            <h6 class="font-bold text-slate-800">Daftar Kaprodi</h6>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider font-bold border-b border-slate-100">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">NIDN</th>
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Program Studi</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-500">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-md text-xs font-bold flex items-center gap-1 w-fit">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .884-.5 2-2 2h-4c-1.5 0-2-1.116-2-2"/></svg>
                                    {{ $user->nomorInduk->value ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $user->program_studi }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.kaprodis.edit', $user) }}"
                                        class="p-2 bg-yellow-50 text-yellow-600 rounded-lg hover:bg-yellow-100 border border-yellow-200 transition-all" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.kaprodis.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus Kaprodi ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 border border-red-200 transition-all" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center gap-2">
                                     <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    <span>Belum ada data Kaprodi.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
