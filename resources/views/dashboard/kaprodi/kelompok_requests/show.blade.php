@extends('layouts.app-modern')

@section('title', 'Detail Verifikasi Kelompok')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Verifikasi Kelompok</h1>
            <p class="text-slate-500 text-sm">Informasi lengkap kelompok untuk verifikasi Kaprodi.</p>
        </div>
        <a href="{{ route('kaprodi.kelompok_requests.index') }}"
            class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors">
            ← Kembali ke Daftar
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-center gap-3 text-green-700 animate-fade-in-down">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            <!-- Informasi Kelompok -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Informasi Kelompok</h2>
                </div>

                <h3 class="text-xl font-bold text-slate-900 mb-1">
                    {{ $kelompok->judul_pkm }}</h3>
                <p class="text-slate-500 mb-6">{{ $kelompok->nama_kelompok }}</p>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500 uppercase font-semibold">Skema PKM</p>
                        <p class="font-bold text-blue-600">{{ $kelompok->jenis_pkm }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500 uppercase font-semibold">Status Dosen</p>
                        <p class="font-bold {{ $kelompok->status == 'approved' ? 'text-green-600' : ($kelompok->status == 'rejected' ? 'text-red-600' : 'text-yellow-600') }} capitalize">{{ $kelompok->status == 'submitted' ? 'Menunggu' : ($kelompok->status == 'approved' ? 'Disetujui' : 'Ditolak') }}</p>
                    </div>
                </div>
                
                 <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500 uppercase font-semibold">Status Kaprodi</p>
                        <p class="font-bold {{ ($kelompok->status_kaprodi ?? 'menunggu') == 'disetujui' ? 'text-green-600' : (($kelompok->status_kaprodi ?? 'menunggu') == 'ditolak' ? 'text-red-600' : 'text-yellow-600') }} capitalize">{{ $kelompok->status_kaprodi ?? 'menunggu' }}</p>
                    </div>
                     <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500 uppercase font-semibold">Tanggal Pengajuan</p>
                        <p class="font-bold text-slate-700">{{ $kelompok->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <p class="text-xs text-slate-500 uppercase font-semibold mb-3">File Proposal</p>
                    @if ($kelompok->file_proposal)
                        <a href="{{ Storage::url($kelompok->file_proposal) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 rounded-lg font-semibold text-sm border border-green-200 hover:bg-green-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download File
                        </a>
                    @else
                        <p class="text-sm text-slate-400">Tidak ada file proposal</p>
                    @endif
                </div>

                @if($kelompok->deskripsi)
                <div class="border-t pt-6 mt-6">
                    <p class="text-xs text-slate-500 uppercase font-semibold mb-3">Deskripsi</p>
                    <p class="text-slate-700 leading-relaxed">{{ $kelompok->deskripsi }}</p>
                </div>
                @endif
                
                @if ($kelompok->catatan_kaprodi)
                    <div class="border-t pt-6 mt-6">
                        <div class="p-3 rounded-xl bg-red-50 border border-red-200">
                            <p class="text-xs font-bold text-red-700 uppercase mb-1">Catatan Penolakan Kaprodi:</p>
                            <p class="text-sm text-red-800">{{ $kelompok->catatan_kaprodi }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Anggota Kelompok -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-purple-50 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Anggota Kelompok</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($allAnggota as $mhs)
                        <div class="flex items-center p-4 border border-slate-100 rounded-xl bg-slate-50/50 relative">
                            @if (($mhs->posisi ?? '') == 'ketua')
                                <span
                                    class="absolute top-2 right-2 px-2 py-0.5 bg-blue-600 text-[10px] text-white font-bold rounded">KETUA</span>
                            @endif
                            <div
                                class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold mr-4">
                                {{ substr($mhs->nama ?? $mhs->name ?? '?', 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm leading-tight">{{ $mhs->nama ?? $mhs->name }}</p>
                                <p class="text-xs text-slate-500">{{ $mhs->nim ?? '' }} • {{ $mhs->program_studi ?? '' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Status Verifikasi -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="font-bold text-slate-800 mb-4">Status Verifikasi</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 border rounded-xl">
                        <span class="text-sm text-slate-600">Dosen Pembimbing</span>
                        <span
                            class="px-2 py-1 {{ $kelompok->status == 'approved' ? 'bg-green-100 text-green-700' : ($kelompok->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }} text-[10px] font-bold rounded capitalize">{{ $kelompok->status == 'submitted' ? 'Menunggu' : ($kelompok->status == 'approved' ? 'Disetujui' : 'Ditolak') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 border rounded-xl">
                        <span class="text-sm text-slate-600">Kaprodi</span>
                        <span
                            class="px-2 py-1 {{ ($kelompok->status_kaprodi ?? 'menunggu') == 'disetujui' ? 'bg-green-100 text-green-700' : (($kelompok->status_kaprodi ?? 'menunggu') == 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }} text-[10px] font-bold rounded capitalize">{{ $kelompok->status_kaprodi ?? 'menunggu' }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 border rounded-xl">
                        <span class="text-sm text-slate-600">Admin</span>
                        <span
                            class="px-2 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded capitalize">Menunggu</span>
                    </div>
                </div>
            </div>

            <!-- Tindakan / Status Permintaan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="font-bold text-slate-800 mb-4">Tindakan</h2>
                
                @php
                    $dosenApproved = $kelompok->status === 'approved';
                    $kaprodiPending = ($kelompok->status_kaprodi ?? 'menunggu') === 'menunggu';
                @endphp

                @if ($dosenApproved && $kaprodiPending)
                    <div class="space-y-3">
                        <form id="form-accept-{{ $kelompok->id }}" action="{{ route('kaprodi.kelompok_requests.accept', $kelompok->id) }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmAccept('{{ $kelompok->id }}')"
                                class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-md flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Setujui Kelompok
                            </button>
                        </form>

                        <form id="form-reject-{{ $kelompok->id }}" action="{{ route('kaprodi.kelompok_requests.reject', $kelompok->id) }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmReject('{{ $kelompok->id }}')"
                                class="w-full py-2.5 bg-white border-2 border-red-500 text-red-500 hover:bg-red-50 font-bold rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Tolak Kelompok
                            </button>
                        </form>
                    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmAccept(id) {
            Swal.fire({
                title: 'Setujui Kelompok?',
                text: "Anda akan menyetujui kelompok ini secara resmi.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563EB', // blue-600
                cancelButtonColor: '#64748B', // slate-500
                confirmButtonText: 'Ya, Setujui',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-accept-' + id).submit();
                }
            })
        }

        function confirmReject(id) {
            Swal.fire({
                title: 'Tolak Kelompok?',
                text: "Kelompok ini akan ditolak.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444', // red-500
                cancelButtonColor: '#64748B', // slate-500
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-reject-' + id).submit();
                }
            })
        }
    </script>
    @endpush
                @else
                    <div class="text-center mb-4">
                         @if(($kelompok->status_kaprodi ?? 'menunggu') == 'disetujui')
                             <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h5 class="font-bold text-green-700">Disetujui Kaprodi</h5>
                        @elseif(($kelompok->status_kaprodi ?? 'menunggu') == 'ditolak')
                             <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h5 class="font-bold text-red-700">Ditolak Kaprodi</h5>
                        @elseif(!$dosenApproved)
                             <div class="w-16 h-16 rounded-full bg-yellow-100 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h5 class="font-bold text-yellow-700">Menunggu Dosen Pemilming</h5>
                            <p class="text-xs text-slate-500 mt-2">Perlu persetujuan Dosen Pembimbing terlebih dahulu.</p>
                        @endif
                    </div>
                     <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-center">
                        <p class="text-sm text-slate-500">
                             @if($kaprodiPending && $dosenApproved)
                                Menunggu tindakan Anda.
                             @else
                                Tindakan sudah dilakukan atau belum tersedia.
                             @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
