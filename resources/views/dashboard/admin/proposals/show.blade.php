@extends('layouts.app-modern')

@section('title', 'Detail Proposal PKM')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Proposal</h1>
            <p class="text-slate-500 text-sm">Tinjau detail proposal dan berikan keputusan verifikasi admin.</p>
        </div>
        <a href="{{ route('admin.pengajuan_kelompok_pkm.index') }}"
            class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors">
            ← Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="font-bold text-slate-800">Informasi Proposal</h2>
                </div>

                <h3 class="text-xl font-bold text-slate-900 mb-1">
                    {{ $proposal->judul_kelompok ?? ($proposal->judul_pkm ?? '') }}</h3>
                <p class="text-slate-500 mb-6">{{ $proposal->nama_kelompok ?? '-' }}</p>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500 uppercase font-semibold">Skema PKM</p>
                        <p class="font-bold text-blue-600">{{ $proposal->skema ?? ($proposal->skema_pkm ?? 'PKM-KC') }}</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <p class="text-xs text-slate-500 uppercase font-semibold">Dosen Pembimbing</p>
                        <p class="font-bold text-slate-700">{{ $proposal->dosenPembimbing->name ?? 'Belum Ditentukan' }}</p>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <p class="text-xs text-slate-500 uppercase font-semibold mb-3">File Proposal</p>
                    @if ($proposal->file_proposal)
                        <a href="{{ Storage::url($proposal->file_proposal) }}" target="_blank"
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
            </div>

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
                    @foreach ($proposal->anggota as $mhs)
                        <div class="flex items-center p-4 border border-slate-100 rounded-xl bg-slate-50/50 relative">
                            @if ($loop->first)
                                <span
                                    class="absolute top-2 right-2 px-2 py-0.5 bg-blue-600 text-[10px] text-white font-bold rounded">KETUA</span>
                            @endif
                            <div
                                class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold mr-4">
                                {{ substr($mhs->nama ?? $mhs->name, 0, 1) }}
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
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="font-bold text-slate-800 mb-4">Status Verifikasi</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 border rounded-xl">
                        <span class="text-sm text-slate-600">Dosen Pembimbing</span>
                        <span
                            class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded">{{ $proposal->status_dosen ?? 'menunggu' }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 border rounded-xl">
                        <span class="text-sm text-slate-600">Kaprodi</span>
                        <span
                            class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded">{{ $proposal->status_kaprodi ?? 'menunggu' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h2 class="font-bold text-slate-800 mb-4">Status Review Admin</h2>

                @if ($proposal->status_admin === 'menunggu')
                    <div class="text-center py-6">
                        <div class="w-12 h-12 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-800">Menunggu Review</h3>
                        <p class="text-xs text-slate-500 px-4 mt-1">Proposal ini menunggu keputusan verifikasi admin.</p>
                    </div>

                    <div class="space-y-3 mt-6">
                        <form action="{{ route('admin.pengajuan_kelompok_pkm.approve', $proposal->id) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full py-2.5 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl transition-all shadow-lg shadow-green-100 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Setujui Proposal
                            </button>
                        </form>

                        <form action="{{ route('admin.pengajuan_kelompok_pkm.reject', $proposal->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="catatan_admin"
                                value="Mohon perbaiki dokumen sesuai panduan terbaru.">
                            <button type="submit"
                                class="w-full py-2.5 bg-white border border-red-200 text-red-500 hover:bg-red-50 font-bold rounded-xl transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Tolak Proposal
                            </button>
                        </form>
                    </div>
                @else
                    <div
                        class="p-4 rounded-xl {{ $proposal->status_admin == 'disetujui' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }} text-center">
                        <p class="font-bold capitalize">Status: {{ $proposal->status_admin }}</p>
                        <p class="text-xs mt-1">{{ $proposal->catatan_admin ?? 'Tidak ada catatan' }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Approve Modal -->
    <div id="approveModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="document.getElementById('approveModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-bounce-in">
                <form action="{{ route('admin.pengajuan_kelompok_pkm.approve', $proposal->id) }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">Setujui Proposal
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500 mb-4">Apakah Anda yakin ingin menyetujui proposal ini?
                                    </p>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Catatan (Opsional)</label>
                                    <textarea name="catatan_admin"
                                        class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500 shadow-sm" rows="3"
                                        placeholder="Tambahkan catatan..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Setujui
                        </button>
                        <button type="button" onclick="document.getElementById('approveModal').classList.add('hidden')"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uhamka-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="document.getElementById('rejectModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-bounce-in">
                <form action="{{ route('admin.pengajuan_kelompok_pkm.reject', $proposal->id) }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">Tolak Proposal
                                </h3>
                                <div class="mt-2 text-left">
                                    <p class="text-sm text-slate-500 mb-4">Proposal yang ditolak dapat diupload ulang oleh
                                        mahasiswa.</p>

                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Status Penolakan <span
                                                class="text-red-500">*</span></label>
                                        <select name="revision_stage"
                                            class="w-full rounded-xl border-slate-300 focus:border-red-500 focus:ring-red-500 shadow-sm">
                                            <option value="0">Tolak - Upload Ulang</option>
                                            <option value="1">Revisi Tahap 1</option>
                                            <option value="2">Revisi Tahap 2</option>
                                            <option value="3">Revisi Tahap 3</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Penolakan <span
                                                class="text-red-500">*</span></label>
                                        <textarea name="catatan_admin"
                                            class="w-full rounded-xl border-slate-300 focus:border-red-500 focus:ring-red-500 shadow-sm" rows="4"
                                            placeholder="Jelaskan alasan penolakan..." required></textarea>
                                        <p class="text-xs text-slate-400 mt-1">Minimal 10 karakter</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Tolak
                        </button>
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')"
                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uhamka-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
