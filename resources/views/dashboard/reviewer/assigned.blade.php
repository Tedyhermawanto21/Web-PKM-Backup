@extends('layouts.app-modern')

@section('title', 'Assigned Reviews')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Tugas Review</h1>
        <p class="text-slate-500">Daftar proposal yang ditugaskan untuk Anda.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-lg text-slate-900">Assigned Proposals</h3>
        </div>

        @if ($assigned->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-900 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nama Kelompok</th>
                            <th class="px-6 py-4">Judul PKM</th>
                            <th class="px-6 py-4">Ketua</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($assigned as $index => $p)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold">{{ $p->nama_kelompok }}</td>
                                <td class="px-6 py-4">{{ $p->judul_kelompok }}</td>
                                <td class="px-6 py-4">{{ $p->ketua->name }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('reviewer.assigned.show', $p->id) }}"
                                        class="px-3 py-1.5 bg-uhamka-500 text-white rounded">Review</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-6 text-slate-500">Belum ada tugas review.</div>
        @endif
    </div>
@endsection
