@extends('layouts.app-modern')

@section('title', 'Detail Mahasiswa')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Mahasiswa</h1>
            <p class="text-slate-500">Informasi lengkap kelompok mahasiswa PKM.</p>
        </div>
        <a href="{{ route('kaprodi.daftar_mahasiswa.index') }}"
            class="inline-flex items-center px-4 py-2 bg-white text-slate-700 text-sm font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm hover:shadow-md">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Status Badge Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center mb-8">
        <h4 class="text-lg font-bold text-slate-900 mb-6">Status Verifikasi</h4>

        @php
            $dosenStatus = $kelompok->status;
            $kaprodiStatus = $kelompok->status_kaprodi ?? 'menunggu';
        @endphp

        @if ($kaprodiStatus == 'disetujui')
            <div class="inline-flex flex-col items-center">
                <span
                    class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold bg-green-100 text-green-700 mb-4">
                    <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Disetujui
                </span>
                <p class="text-green-600 font-bold">Kelompok telah disetujui oleh Kaprodi dan dapat melanjutkan ke tahap
                    berikutnya</p>
            </div>
        @elseif($kaprodiStatus == 'ditolak')
            <div class="inline-flex flex-col items-center">
                <span
                    class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold bg-red-100 text-red-700 mb-4">
                    <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Ditolak
                </span>
                <p class="text-red-500 font-bold">Kelompok ditolak oleh Kaprodi</p>
                @if ($kelompok->catatan_kaprodi)
                    <div class="mt-4 p-4 bg-red-50 rounded-xl border border-red-100 max-w-2xl text-left">
                        <strong class="text-red-800 block mb-1">Alasan Penolakan:</strong>
                        <p class="text-red-700">{{ $kelompok->catatan_kaprodi }}</p>
                    </div>
                @endif
            </div>
        @else
            <div class="inline-flex flex-col items-center">
                <span
                    class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold bg-yellow-100 text-yellow-700 mb-4">
                    <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Menunggu
                </span>
                <p class="text-slate-500">Kelompok masih dalam proses verifikasi</p>
            </div>
        @endif
    </div>

    <!-- Progress Tracking Card -->
    @if(isset($proposal))
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 mb-8">
            <div class="flex items-center justify-between mb-8">
                 <h4 class="text-lg font-bold text-slate-900">Tahapan Proposal</h4>
                 <span class="text-xs font-medium px-3 py-1 bg-slate-100 text-slate-500 rounded-full">Progress Terkini</span>
            </div>
            
            <div class="relative px-4">
                <!-- Connecting Line -->
                <div class="absolute left-0 top-1/2 -mt-px w-full h-1 bg-slate-100 rounded-full" aria-hidden="true"></div>
                
                <!-- Steps -->
                <ul class="relative flex justify-between w-full">
                    @php
                        // Define the Admin Schedule Steps
                        $steps = [
                            'pengajuan_kelompok' => ['label' => 'Pengajuan', 'status' => 'upcoming'],
                            'upload_proposal' => ['label' => 'Upload Proposal', 'status' => 'upcoming'],
                            'revisi_1' => ['label' => 'Revisi Tahap 1', 'status' => 'upcoming'],
                            'revisi_2' => ['label' => 'Revisi Tahap 2', 'status' => 'upcoming'],
                            'revisi_3' => ['label' => 'Revisi Tahap 3', 'status' => 'upcoming'],
                        ];

                        // Check Active Schedule from Admin (Optional: Highlight active phase?)
                        // For now, track STUDENT progress through these phases.

                        // 1. Pengajuan Kelompok
                        // Complete if Dosen and Kaprodi Approved
                        if ($proposal->status == 'menunggu_approval' || $proposal->status_dosen == 'menunggu' || $proposal->status_dosen == 'ditolak' || $proposal->status_dosen == 'pending') {
                             $steps['pengajuan_kelompok']['status'] = 'current';
                             if($proposal->status_dosen == 'ditolak') $steps['pengajuan_kelompok']['status'] = 'rejected';
                        } elseif ($proposal->status_kaprodi == 'menunggu') {
                             $steps['pengajuan_kelompok']['status'] = 'current'; // Still in approval phase
                        } elseif ($proposal->status_kaprodi == 'ditolak') {
                             $steps['pengajuan_kelompok']['status'] = 'rejected';
                        } else {
                             // Approved by both
                             $steps['pengajuan_kelompok']['status'] = 'complete';

                             // 2. Upload Proposal
                             if (!$proposal->file_proposal) {
                                 $steps['upload_proposal']['status'] = 'current';
                             } else {
                                 $steps['upload_proposal']['status'] = 'complete';

                                 // 3. Revisi Stages
                                 // Logic: Check revision_stage and status_admin
                                 // If status_admin is 'disetujui', they are DONE (Green). All remaining stages 'complete' or skipped?
                                 // If status_admin is 'pending', they are waiting for review (Show between Upload and Revisi 1?)
                                 
                                 if ($proposal->status_admin == 'disetujui') {
                                     // Mark all as complete? Or just show up to where they got approved?
                                     // Let's mark Revisi 1 as complete (passed)
                                     $steps['revisi_1']['status'] = 'complete';
                                     $steps['revisi_2']['status'] = 'complete';
                                     $steps['revisi_3']['status'] = 'complete';
                                 } elseif ($proposal->status_admin == 'pending') {
                                     // Waiting for review, theoretically 'current' could be Revisi 1 (waiting for feedback)
                                     // Or create a visual "Waiting" state on Upload?
                                     // Let's mark Upload as Complete and Revisi 1 as "Waiting" (Upcoming)
                                     // OR mark Revisi 1 as Current?
                                     $steps['revisi_1']['status'] = 'current'; // Waiting for result of Phase 1
                                 } elseif ($proposal->status_admin == 'revisi') {
                                     $stage = $proposal->revision_stage ?? 1;
                                     if ($stage == 1) {
                                         $steps['revisi_1']['status'] = 'current';
                                     } elseif ($stage == 2) {
                                         $steps['revisi_1']['status'] = 'complete';
                                         $steps['revisi_2']['status'] = 'current';
                                     } elseif ($stage >= 3) {
                                         $steps['revisi_1']['status'] = 'complete';
                                         $steps['revisi_2']['status'] = 'complete';
                                         $steps['revisi_3']['status'] = 'current';
                                     }
                                 }
                             }
                        }
                    @endphp

                    @foreach ($steps as $key => $step)
                        <li class="relative flex flex-col items-center group flex-1">
                            @if ($step['status'] == 'complete')
                                <div class="h-10 w-10 rounded-full bg-uhamka-600 flex items-center justify-center ring-4 ring-white z-10 shadow-sm transition-all duration-300 transform group-hover:scale-110">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="mt-4 text-xs font-bold text-uhamka-700 text-center uppercase tracking-wide">{{ $step['label'] }}</span>
                            @elseif($step['status'] == 'current')
                                <div class="h-10 w-10 rounded-full bg-white border-2 border-uhamka-600 flex items-center justify-center ring-4 ring-white z-10 shadow-md animate-pulse">
                                    <div class="h-3 w-3 rounded-full bg-uhamka-600"></div>
                                </div>
                                <span class="mt-4 text-xs font-bold text-uhamka-700 text-center uppercase tracking-wide">{{ $step['label'] }}</span>
                            @elseif($step['status'] == 'rejected')
                                <div class="h-10 w-10 rounded-full bg-red-600 flex items-center justify-center ring-4 ring-white z-10 shadow-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <span class="mt-4 text-xs font-bold text-red-700 text-center uppercase tracking-wide">{{ $step['label'] }} (Ditolak)</span>
                            @else
                                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center ring-4 ring-white z-10">
                                    <span class="text-sm font-bold text-slate-400">{{ $loop->iteration }}</span>
                                </div>
                                <span class="mt-4 text-xs font-medium text-slate-400 text-center uppercase tracking-wide">{{ $step['label'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Informasi Kelompok -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-full">
            <div class="bg-uhamka-900 px-6 py-4 border-b border-uhamka-800">
                <h6 class="font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Informasi Kelompok
                </h6>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Nama Kelompok</span>
                        <p class="text-slate-800 font-semibold">{{ $kelompok->nama_kelompok }}</p>
                    </div>
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Judul PKM</span>
                        <p class="text-slate-800 font-semibold leading-relaxed">{{ $kelompok->judul_pkm }}</p>
                    </div>
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Skema</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $kelompok->jenis_pkm }}
                        </span>
                    </div>
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Dosen Pembimbing</span>
                        @if ($kelompok->dosenPembimbing)
                            <div class="flex items-center gap-3 mt-1">
                                <div
                                    class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-sm font-bold text-slate-600">
                                    {{ substr($kelompok->dosenPembimbing->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-slate-900 font-bold">{{ $kelompok->dosenPembimbing->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $kelompok->dosenPembimbing->program_studi ?? '-' }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-slate-500 italic">Belum dipilih</p>
                        @endif
                    </div>
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Tanggal Pengajuan</span>
                        <p class="text-slate-800">{{ $kelompok->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    @if ($kelompok->deskripsi)
                        <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                            <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Deskripsi</span>
                            <p class="text-slate-800 leading-relaxed">{{ $kelompok->deskripsi }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Daftar Anggota -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden h-full">
            <div class="bg-uhamka-900 px-6 py-4 border-b border-uhamka-800">
                <h6 class="font-bold text-white flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Anggota Kelompok <span
                        class="ml-2 px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ $allAnggota->count() }}
                        Orang</span>
                </h6>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($allAnggota as $index => $anggota)
                        <div
                            class="flex items-center p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                            <div
                                class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold mr-4">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    @if ($anggota->posisi == 'ketua')
                                        <span
                                            class="px-2 py-0.5 bg-uhamka-100 text-uhamka-700 text-[10px] font-bold uppercase rounded-md">Ketua</span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] font-bold uppercase rounded-md">Anggota</span>
                                    @endif
                                    <h6 class="font-bold text-slate-900">{{ $anggota->nama }}</h6>
                                </div>
                                <p class="text-xs text-slate-500">{{ $anggota->nim ?? '-' }} •
                                    {{ $anggota->program_studi ?? '-' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-slate-500">
                            <p class="text-sm">Tidak ada anggota.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection
