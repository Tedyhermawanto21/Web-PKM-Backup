@extends('layouts.app-modern')

@section('title', 'Detail Proposal')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Proposal PKM</h1>
            <p class="text-slate-500">Tinjau detail dan status verifikasi proposal mahasiswa.</p>
        </div>
        <a href="{{ route('kaprodi.proposals.index') }}"
            class="inline-flex items-center px-4 py-2 bg-white text-slate-700 text-sm font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm hover:shadow-md">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-center gap-3 text-green-700 animate-fade-in-down">
            <div class="bg-green-100 p-2 rounded-full">
                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column: Primary Info -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Proposal Info Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                             <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                         <h6 class="font-bold text-slate-800">Informasi Proposal</h6>
                    </div>
                </div>
                <div class="p-6">
                     <div class="space-y-6">
                        <div>
                             <h3 class="text-2xl font-bold text-slate-800 mb-2">{{ $proposal->judul_kelompok }}</h3>
                             <p class="text-lg text-slate-600 font-medium">{{ $proposal->nama_kelompok }}</p>
                        </div>

                         <div class="flex flex-wrap gap-4">
                             <div class="px-4 py-2 bg-slate-50 rounded-xl border border-slate-100">
                                   <span class="text-xs text-slate-500 uppercase font-bold block mb-1">Skema PKM</span>
                                   <span class="font-bold text-uhamka-600">{{ $proposal->skema }}</span>
                             </div>
                              <div class="px-4 py-2 bg-slate-50 rounded-xl border border-slate-100">
                                   <span class="text-xs text-slate-500 uppercase font-bold block mb-1">Dosen Pembimbing</span>
                                   <span class="font-bold text-slate-700">{{ $proposal->dosenPembimbing->name ?? 'Belum ada' }}</span>
                             </div>
                         </div>
                     </div>
                </div>
            </div>

             <!-- Members Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                     <div class="bg-indigo-100 p-2 rounded-lg text-indigo-600">
                         <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                     </div>
                    <h6 class="font-bold text-slate-800">Anggota Kelompok</h6>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Ketua -->
                         <div class="flex items-center p-4 bg-gradient-to-br from-indigo-50 to-white rounded-xl border border-indigo-100 shadow-sm relative overflow-hidden group">
                           <div class="absolute top-0 right-0 bg-indigo-500 text-white text-[10px] uppercase font-bold px-2 py-1 rounded-bl-lg">Ketua</div>
                            <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg mr-4 group-hover:scale-110 transition-transform">
                                {{ substr($proposal->ketua->name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="font-bold text-slate-800">{{ $proposal->ketua->name }}</h6>
                                <p class="text-xs text-slate-500">{{ $proposal->ketua->nim }} • {{ $proposal->ketua->program_studi }}</p>
                            </div>
                        </div>

                        <!-- Anggota Loop -->
                         @foreach($proposal->anggota as $anggota)
                            <div class="flex items-center p-4 bg-white rounded-xl border border-slate-100 hover:border-slate-300 transition-colors shadow-sm group">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-lg mr-4 group-hover:bg-uhamka-100 group-hover:text-uhamka-600 transition-colors">
                                    {{ substr($anggota->nama, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="font-bold text-slate-800">{{ $anggota->nama }}</h6>
                                    <p class="text-xs text-slate-500">{{ $anggota->nim }} • {{ $anggota->program_studi }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Notes Cards (If any) -->
             @if ($proposal->catatan_dosen)
                <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100">
                     <h6 class="font-bold text-blue-800 flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        Catatan Dosen
                    </h6>
                    <p class="text-blue-700 italic">"{{ $proposal->catatan_dosen }}"</p>
                </div>
            @endif

            @if ($proposal->catatan_kaprodi)
                <div class="bg-amber-50 rounded-2xl p-6 border border-amber-100">
                     <h6 class="font-bold text-amber-800 flex items-center mb-2">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        Catatan Kaprodi
                    </h6>
                    <p class="text-amber-700 italic">"{{ $proposal->catatan_kaprodi }}"</p>
                </div>
            @endif
        </div>

        <!-- Right Column: Status & Actions -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Status Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                 <div class="p-6 border-b border-slate-100 bg-slate-50">
                    <h6 class="font-bold text-slate-800">Status Proposal</h6>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Status Dosen -->
                    <div>
                         <p class="text-xs text-slate-500 uppercase font-bold mb-2">Verifikasi Dosen</p>
                         @if ($proposal->status_dosen == 'disetujui')
                            <div class="flex items-center p-3 bg-green-50 rounded-xl border border-green-100 text-green-700">
                                 <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                 <span class="font-bold">Disetujui</span>
                            </div>
                         @elseif($proposal->status_dosen == 'ditolak')
                            <div class="flex items-center p-3 bg-red-50 rounded-xl border border-red-100 text-red-700">
                                 <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                 <span class="font-bold">Ditolak</span>
                            </div>
                         @else
                            <div class="flex items-center p-3 bg-amber-50 rounded-xl border border-amber-100 text-amber-700">
                                 <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                 <span class="font-bold">Menunggu</span>
                            </div>
                         @endif
                    </div>

                    <!-- Status Kaprodi -->
                     <div>
                         <p class="text-xs text-slate-500 uppercase font-bold mb-2">Verifikasi Kaprodi</p>
                         @if ($proposal->status_kaprodi == 'disetujui')
                            <div class="flex items-center p-3 bg-green-50 rounded-xl border border-green-100 text-green-700">
                                 <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                 <span class="font-bold">Disetujui</span>
                            </div>
                         @elseif($proposal->status_kaprodi == 'ditolak')
                            <div class="flex items-center p-3 bg-red-50 rounded-xl border border-red-100 text-red-700">
                                 <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                 <span class="font-bold">Ditolak</span>
                            </div>
                         @else
                            <div class="flex items-center p-3 bg-uhamka-50 rounded-xl border border-uhamka-100 text-uhamka-700">
                                 <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                 <span class="font-bold">Menunggu Verifikasi</span>
                            </div>
                         @endif
                    </div>
                </div>
            </div>

            <!-- Action Card -->
             @if ($proposal->status_kaprodi == 'menunggu' && $proposal->status_dosen == 'disetujui')
                 <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                     <div class="p-6 border-b border-slate-100 bg-slate-50 flex items-center gap-3">
                         <div class="bg-uhamka-100 p-2 rounded-lg text-uhamka-600">
                             <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                         </div>
                        <h6 class="font-bold text-slate-800">Tindak Lanjut</h6>
                    </div>
                    <div class="p-6 space-y-4">
                         <form action="{{ route('kaprodi.proposals.approve', $proposal->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Catatan Persetujuan (Opsional)</label>
                                <textarea name="catatan_kaprodi" class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500 shadow-sm" rows="3" placeholder="Tambahkan catatan untuk mahasiswa..."></textarea>
                            </div>
                            <button type="submit" class="w-full flex justify-center items-center px-4 py-3 bg-green-600 text-white font-bold rounded-xl shadow-md hover:bg-green-700 hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Setujui Proposal
                            </button>
                        </form>

                        <div class="relative flex py-2 items-center">
                            <div class="flex-grow border-t border-slate-200"></div>
                            <span class="flex-shrink-0 mx-4 text-slate-400 text-xs font-bold uppercase">Atau</span>
                            <div class="flex-grow border-t border-slate-200"></div>
                        </div>

                        <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="w-full flex justify-center items-center px-4 py-3 bg-white text-red-600 font-bold rounded-xl border-2 border-red-100 hover:bg-red-50 hover:border-red-200 transition-all">
                             <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Tolak Proposal
                        </button>
                    </div>
                 </div>
             @endif
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('rejectModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-bounce-in">
                <form action="{{ route('kaprodi.proposals.reject', $proposal->id) }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">Tolak Proposal</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500 mb-4">Apakah Anda yakin ingin menolak proposal ini? Tindakan ini tidak dapat dibatalkan.</p>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                                    <textarea name="catatan_kaprodi" class="w-full rounded-xl border-slate-300 focus:border-red-500 focus:ring-red-500 shadow-sm" rows="3" placeholder="Jelaskan alasan penolakan..." required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Tolak Proposal
                        </button>
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uhamka-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
