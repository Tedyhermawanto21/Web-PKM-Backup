@extends('layouts.app-modern')

@section('title', 'Detail Proposal')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Proposal PKM</h1>
            <p class="text-slate-500">Tinjau detail proposal dan berikan keputusan verifikasi admin.</p>
        </div>
        <a href="{{ route('admin.proposals.index') }}"
            class="inline-flex items-center px-4 py-2 bg-white text-slate-700 text-sm font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm hover:shadow-md">
             <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali
        </a>
    </div>

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
                                   <span class="font-bold text-slate-700">{{ $proposal->dosenPembimbing->name ?? '-' }}</span>
                             </div>
                         </div>
                         
                         <div>
                            <span class="text-xs text-slate-500 uppercase font-bold block mb-2">File Proposal</span>
                            <a href="{{ Storage::url($proposal->file_proposal) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 font-bold rounded-lg border border-green-200 hover:bg-green-100 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download File
                            </a>
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
                            @if($anggota->posisi != 'ketua')
                            <div class="flex items-center p-4 bg-white rounded-xl border border-slate-100 hover:border-slate-300 transition-colors shadow-sm group">
                                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-lg mr-4 group-hover:bg-uhamka-100 group-hover:text-uhamka-600 transition-colors">
                                    {{ substr($anggota->nama, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="font-bold text-slate-800">{{ $anggota->nama }}</h6>
                                    <p class="text-xs text-slate-500">{{ $anggota->nim }} • {{ $anggota->program_studi }}</p>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Verification & Status -->
        <div class="lg:col-span-1 space-y-8">
            <!-- Verification Status Card -->
             <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50">
                    <h6 class="font-bold text-slate-800">Status Verifikasi</h6>
                </div>
                <div class="p-6 space-y-4">
                     <!-- Dosen Status -->
                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-sm font-bold text-slate-600">Dosen Pembimbing</span>
                         @if ($proposal->status_dosen == 'disetujui')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>
                         @elseif($proposal->status_dosen == 'ditolak')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                         @else
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>
                         @endif
                    </div>

                    <!-- Kaprodi Status -->
                     <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-sm font-bold text-slate-600">Kaprodi</span>
                        @if ($proposal->status_kaprodi == 'disetujui')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>
                         @elseif($proposal->status_kaprodi == 'ditolak')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                         @else
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>
                         @endif
                    </div>
                </div>
            </div>

            <!-- Admin Review Card -->
             <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50">
                    <h6 class="font-bold text-slate-800">Status Review Admin</h6>
                </div>
                <div class="p-6 text-center">
                    @if ($proposal->status_admin == 'menunggu')
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-amber-100 rounded-full mb-4 animate-pulse">
                            <svg class="w-10 h-10 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h5 class="text-xl font-bold text-slate-800 mb-1">Menunggu Review</h5>
                        <p class="text-sm text-slate-500">Proposal ini menunggu keputusan admin.</p>
                    @elseif($proposal->status_admin == 'disetujui')
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                            <svg class="w-10 h-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h5 class="text-xl font-bold text-slate-800 mb-1">Disetujui</h5>
                         <p class="text-sm text-slate-500">Proposal telah disetujui oleh admin.</p>
                    @elseif($proposal->status_admin == 'ditolak')
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                            <svg class="w-10 h-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h5 class="text-xl font-bold text-slate-800 mb-1">Ditolak</h5>
                         <p class="text-sm text-slate-500">Proposal ditolak oleh admin.</p>
                    @endif

                    @if ($proposal->catatan_admin)
                        <div class="mt-6 p-4 rounded-xl bg-slate-50 border border-slate-100 text-left">
                            <strong class="text-slate-800 block mb-1 text-sm">Catatan Admin:</strong>
                            <p class="text-slate-600 italic text-sm">"{{ $proposal->catatan_admin }}"</p>
                        </div>
                    @endif

                    @if ($proposal->status_admin == 'menunggu')
                        <div class="mt-8 space-y-3">
                            <button onclick="document.getElementById('approveModal').classList.remove('hidden')" class="w-full flex justify-center items-center px-4 py-3 bg-green-600 text-white font-bold rounded-xl shadow-md hover:bg-green-700 hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                                 <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Setujui Proposal
                            </button>
                            <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="w-full flex justify-center items-center px-4 py-3 bg-white text-red-600 font-bold rounded-xl border-2 border-red-100 hover:bg-red-50 hover:border-red-200 transition-all">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Tolak Proposal
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
             <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('approveModal').classList.add('hidden')"></div>
             <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-bounce-in">
                <form action="{{ route('admin.proposals.approve', $proposal->id) }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">Setujui Proposal</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500 mb-4">Apakah Anda yakin ingin menyetujui proposal ini?</p>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Catatan (Opsional)</label>
                                    <textarea name="catatan_admin" class="w-full rounded-xl border-slate-300 focus:border-green-500 focus:ring-green-500 shadow-sm" rows="3" placeholder="Tambahkan catatan..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Setujui
                        </button>
                        <button type="button" onclick="document.getElementById('approveModal').classList.add('hidden')" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-uhamka-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="document.getElementById('rejectModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full animate-bounce-in">
                <form action="{{ route('admin.proposals.reject', $proposal->id) }}" method="POST">
                    @csrf
                     <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">Tolak Proposal</h3>
                                <div class="mt-2 text-left">
                                    <p class="text-sm text-slate-500 mb-4">Proposal yang ditolak dapat diupload ulang oleh mahasiswa.</p>
                                    
                                     <div class="mb-4">
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Status Penolakan <span class="text-red-500">*</span></label>
                                        <select name="revision_stage" class="w-full rounded-xl border-slate-300 focus:border-red-500 focus:ring-red-500 shadow-sm">
                                            <option value="0">Tolak - Upload Ulang</option>
                                            <option value="1">Revisi Tahap 1</option>
                                            <option value="2">Revisi Tahap 2</option>
                                            <option value="3">Revisi Tahap 3</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Penolakan <span class="text-red-500">*</span></label>
                                        <textarea name="catatan_admin" class="w-full rounded-xl border-slate-300 focus:border-red-500 focus:ring-red-500 shadow-sm" rows="4" placeholder="Jelaskan alasan penolakan..." required></textarea>
                                         <p class="text-xs text-slate-400 mt-1">Minimal 10 karakter</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Tolak
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
