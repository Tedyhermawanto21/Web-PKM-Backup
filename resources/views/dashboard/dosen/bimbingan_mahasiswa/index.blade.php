@extends('layouts.app-modern')

@section('title', 'Bimbingan Mahasiswa')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">Bimbingan Mahasiswa</h1>
            <p class="text-slate-500">Kelola dan pantau kelompok PKM yang Anda bimbing.</p>
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

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-6 border-b">
            <h3 class="font-bold">Daftar Kelompok Bimbingan</h3>
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
                            <th class="px-6 py-3">Ketua</th>
                            <th class="px-6 py-3">Jumlah Anggota</th>
                            <th class="px-6 py-3">Status Dosen</th>
                            <th class="px-6 py-3">Status Kaprodi</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($kelompoks as $i => $k)
                            <tr>
                                <td class="px-6 py-4">{{ $i + 1 }}</td>
                                <td class="px-6 py-4 font-bold">{{ $k->nama_kelompok }}</td>
                                <td class="px-6 py-4">{{ Str::limit($k->judul_pkm, 40) }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">{{ $k->jenis_pkm }}</span>
                                </td>
                                <td class="px-6 py-4">{{ $k->ketua->name }}</td>
                                <td class="px-6 py-4">{{ $k->anggota->count() + 1 }} orang</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700">
                                        Disetujui
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'menunggu' => 'bg-yellow-100 text-yellow-700',
                                            'disetujui' => 'bg-green-100 text-green-700',
                                            'ditolak' => 'bg-red-100 text-red-700',
                                        ];
                                        $kaprodiStatus = $k->status_kaprodi ?? 'menunggu';
                                    @endphp
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $statusColors[$kaprodiStatus] ?? 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($kaprodiStatus) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('dosen.bimbingan_mahasiswa.show', $k->id) }}"
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
                                        <a href="#"
                                            class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                            Bimbingan
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
                <h3 class="text-lg font-medium text-slate-600 mb-2">Belum Ada Kelompok Bimbingan</h3>
                <p class="text-sm text-slate-500 mb-4">Kelompok yang Anda terima sebagai pembimbing akan muncul di sini.</p>
                <a href="{{ route('dosen.kelompok_requests.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Cek Permintaan Pembimbing
                </a>
            </div>
        @endif
    </div>

@endsection
