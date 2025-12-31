@extends('layouts.app-modern')

@section('title', 'Detail Permintaan Pembimbing')

@section('content')
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">Detail Permintaan Pembimbing</h1>
        <p class="text-slate-500">Informasi lengkap kelompok yang meminta Anda sebagai pembimbing.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <h2 class="font-bold text-lg">{{ $kelompok->nama_kelompok }}</h2>
                <p class="text-slate-600">{{ $kelompok->judul_pkm }}</p>

                <div class="mt-4">
                    <h3 class="font-semibold">Ketua</h3>
                    <p>{{ $kelompok->ketua->name ?? '—' }}</p>
                </div>

                <div class="mt-4">
                    <h3 class="font-semibold">Anggota</h3>
                    <ul>
                        @foreach ($kelompok->anggota as $anggota)
                            <li>{{ $anggota->name }} ({{ $anggota->email }})</li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-4">
                    <h3 class="font-semibold">Deskripsi</h3>
                    <p class="text-slate-700">{{ $kelompok->deskripsi ?? '-' }}</p>
                </div>
            </div>

            <div>
                <div class="p-4 bg-slate-50 rounded-lg">
                    <p class="text-sm text-slate-500">Tanggal Permintaan</p>
                    <p class="font-bold">{{ $kelompok->created_at->format('d/m/Y H:i') }}</p>

                    <p class="mt-4 text-sm text-slate-500">Status</p>
                    <p class="font-bold">{{ ucfirst($kelompok->status) }}</p>

                    <div class="mt-6">
                        @if ($kelompok->status === 'submitted')
                            <form action="{{ route('dosen.kelompok_requests.accept', $kelompok->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-uhamka-500 text-white font-bold">Terima</button>
                            </form>

                            <form action="{{ route('dosen.kelompok_requests.reject', $kelompok->id) }}" method="POST"
                                class="mt-2">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 rounded-lg bg-red-500 text-white font-bold">Tolak</button>
                            </form>
                        @else
                            <div class="text-sm text-slate-600">Tindakan sudah dilakukan.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('dosen.kelompok_requests.index') }}" class="text-uhamka-500 font-bold">← Kembali ke
                daftar</a>
        </div>
    </div>
@endsection
