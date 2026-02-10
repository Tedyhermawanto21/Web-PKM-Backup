@extends('layouts.app-modern')

@section('title', 'Kelompok PKM Saya')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">Kelompok PKM Saya</h1>
            <p class="text-slate-500">Lihat detail kelompok PKM yang sudah disetujui.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b">
            <h3 class="font-bold">Daftar Kelompok</h3>
        </div>

        @if ($kelompoks->count())
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-900 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Nama Kelompok</th>
                            <th class="px-6 py-3">Judul PKM</th>
                            <th class="px-6 py-3">Skema</th>
                            <th class="px-6 py-3">Dosen Pembimbing</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($kelompoks as $i => $k)
                            <tr>
                                <td class="px-6 py-4">{{ $i + 1 }}</td>
                                <td class="px-6 py-4 font-bold">{{ $k->nama_kelompok }}</td>
                                <td class="px-6 py-4">{{ Str::limit($k->judul_pkm, 40) }}</td>
                                <td class="px-6 py-4"><span
                                        class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">{{ $k->jenis_pkm }}</span>
                                </td>
                                <td class="px-6 py-4">{{ $k->dosenPembimbing->name ?? 'Belum' }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('mahasiswa.kelompoks.show', $k->id) }}"
                                        class="inline-flex items-center px-3 py-2 bg-slate-50 rounded-lg">Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-slate-400">
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.712-3.714M14 40v-4a9.971 9.971 0 01.712-3.714M18 20a6 6 0 1112 0v4a6 6 0 01-12 0v-4z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-slate-600 mb-2">Belum Ada Kelompok Disetujui</h3>
                <p class="text-sm text-slate-500 mb-4">Kelompok PKM yang sudah disetujui akan muncul di sini.</p>
                <a href="{{ route('mahasiswa.pengajuan_kelompok_pkm.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-uhamka-900 text-white text-sm rounded-lg hover:bg-uhamka-800 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Lihat Pengajuan Saya
                </a>
            </div>
        @endif
    </div>

@endsection
