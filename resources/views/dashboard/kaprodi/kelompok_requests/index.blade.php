@extends('layouts.app-modern')

@section('title', 'Verifikasi Kelompok')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Verifikasi Pengajuan Kelompok</h1>
        <p class="text-slate-500">Daftar kelompok yang perlu diverifikasi oleh Kaprodi.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-lg text-slate-900">Daftar Permintaan Verifikasi</h3>
        </div>

        @if ($kelompoks->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-900 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nama Kelompok</th>
                            <th class="px-6 py-4">Judul PKM</th>
                            <th class="px-6 py-4">Ketua</th>
                            <th class="px-6 py-4">Dosen Pembimbing</th>
                            <th class="px-6 py-4 text-center">Tanggal</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($kelompoks as $index => $kelompok)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $kelompok->nama_kelompok }}</td>
                                <td class="px-6 py-4 line-clamp-2 max-w-xs">{{ Str::limit($kelompok->judul_pkm, 60) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $kelompok->ketua->name ?? '—' }}</td>
                                <td class="px-6 py-4">{{ $kelompok->dosenPembimbing->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-500">
                                    {{ $kelompok->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('kaprodi.kelompok_requests.show', $kelompok->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded-lg text-white bg-uhamka-500 hover:bg-uhamka-600">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                <svg class="w-16 h-16 mb-4 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p>Tidak ada permintaan verifikasi.</p>
            </div>
        @endif
    </div>
@endsection
