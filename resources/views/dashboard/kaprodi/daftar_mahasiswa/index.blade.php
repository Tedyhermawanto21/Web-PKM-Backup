@extends('layouts.app-modern')

@section('title', 'Daftar Mahasiswa')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">Daftar Mahasiswa</h1>
            <p class="text-slate-500">Daftar mahasiswa kelompok PKM yang telah disetujui
                {{ $kaprodiProdi ? 'untuk program studi ' . $kaprodiProdi : '' }}.</p>
        </div>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Filter Info -->
    @if ($kaprodiProdi)
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-blue-800 font-medium">Filter Program Studi</h3>
                    <p class="text-blue-700 text-sm">Menampilkan kelompok mahasiswa program studi
                        <strong>{{ $kaprodiProdi }}</strong></p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b">
            <div class="flex items-center justify-between">
                <h3 class="font-bold">Daftar Kelompok yang Disetujui</h3>
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                    {{ $kelompoks->count() }} Kelompok
                </span>
            </div>
        </div>

        @if ($kelompoks->count())
            <div class="p-6 overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-slate-900 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Nama Kelompok</th>
                            <th class="px-6 py-3">Ketua</th>
                            <th class="px-6 py-3">Program Studi</th>
                            <th class="px-6 py-3">Judul PKM</th>
                            <th class="px-6 py-3">Skema</th>
                            <th class="px-6 py-3">Dosen Pembimbing</th>
                            <th class="px-6 py-3">Tanggal Disetujui</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($kelompoks as $i => $k)
                            <tr>
                                <td class="px-6 py-4">{{ $i + 1 }}</td>
                                <td class="px-6 py-4 font-bold">{{ $k->nama_kelompok }}</td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-medium">{{ $k->ketua->name }}</p>
                                        <p class="text-xs text-slate-500">{{ $k->ketua->nim ?? '-' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">
                                        {{ $k->ketua->program_studi ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ Str::limit($k->judul_pkm, 40) }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs">{{ $k->jenis_pkm }}</span>
                                </td>
                                <td class="px-6 py-4">{{ $k->dosenPembimbing->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-xs">
                                        <p>{{ $k->updated_at->format('d/m/Y') }}</p>
                                        <p class="text-slate-500">{{ $k->updated_at->format('H:i') }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('kaprodi.daftar_mahasiswa.show', $k->id) }}"
                                            class="inline-flex items-center px-3 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Detail
                                        </a>
                                    </div>
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
                <h3 class="text-lg font-medium text-slate-600 mb-2">Belum Ada Mahasiswa Disetujui</h3>
                <p class="text-sm text-slate-500 mb-4">
                    @if ($kaprodiProdi)
                        Belum ada kelompok mahasiswa {{ $kaprodiProdi }} yang disetujui.
                    @else
                        Belum ada kelompok mahasiswa yang disetujui.
                    @endif
                </p>
                <a href="{{ route('kaprodi.kelompok_requests.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Cek Permintaan Verifikasi
                </a>
            </div>
        @endif
    </div>

@endsection
