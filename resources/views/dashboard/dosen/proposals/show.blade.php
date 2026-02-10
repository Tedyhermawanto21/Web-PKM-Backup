@extends('layouts.app-modern')

@section('title', 'Detail Proposal')

@section('content')
    <!-- Page Heading -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Proposal PKM</h1>
            <p class="text-slate-500">Tinjau detail dan ambil keputusan verifikasi.</p>
        </div>
        <a href="{{ route('dosen.pengajuan_kelompok_pkm.index') }}"
            class="inline-flex items-center px-4 py-2 bg-white text-slate-700 text-sm font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm hover:shadow-md">
            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div
            class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-center gap-3 text-green-700 animate-fade-in-down">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div
            class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-center gap-3 text-red-700 animate-fade-in-down">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Status Badge Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center mb-8">
        <h4 class="text-lg font-bold text-slate-900 mb-6">Status Proposal</h4>

        @if ($proposal->status == 'menunggu_approval')
            <div class="inline-flex flex-col items-center">
                <span
                    class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold bg-yellow-100 text-yellow-700 mb-4 animate-pulse">
                    <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Menunggu Approval
                </span>
                <p class="text-slate-500">Proposal ini memerlukan persetujuan Anda</p>
            </div>
        @elseif($proposal->status == 'disetujui')
            <div class="inline-flex flex-col items-center">
                <span
                    class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold bg-green-100 text-green-700 mb-4">
                    <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Disetujui
                </span>
                <p class="text-green-600 font-bold">Anda telah menyetujui proposal ini</p>
            </div>
        @elseif($proposal->status == 'ditolak')
            <div class="inline-flex flex-col items-center">
                <span
                    class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold bg-red-100 text-red-700 mb-4">
                    <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Ditolak
                </span>
                <p class="text-red-500 font-bold">Proposal ini telah ditolak</p>
                @if ($proposal->catatan_penolakan)
                    <div class="mt-4 p-4 bg-red-50 rounded-xl border border-red-100 max-w-2xl text-left">
                        <strong class="text-red-800 block mb-1"><i class="fas fa-info-circle mr-1"></i> Catatan
                            Penolakan:</strong>
                        <p class="text-red-700">{{ $proposal->catatan_penolakan }}</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Progress Tracking Card -->
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
                        <p class="text-slate-800 font-semibold">{{ $proposal->nama_kelompok }}</p>
                    </div>
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Judul PKM</span>
                        <p class="text-slate-800 font-semibold leading-relaxed">{{ $proposal->judul_kelompok }}</p>
                    </div>
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Skema</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $proposal->skema }}
                        </span>
                    </div>
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Ketua Kelompok</span>
                        <div class="flex items-center gap-3 mt-1">
                            <div
                                class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-sm font-bold text-slate-600">
                                {{ substr($proposal->ketua->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-slate-900 font-bold">{{ $proposal->ketua->name }}</p>
                                <p class="text-xs text-slate-500">{{ $proposal->ketua->nim }} •
                                    {{ $proposal->ketua->program_studi }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="pb-4 border-b border-slate-50 last:border-0 last:pb-0">
                        <span class="text-xs font-bold text-slate-400 uppercase block mb-1">Tanggal Pengajuan</span>
                        <p class="text-slate-800">{{ $proposal->created_at->format('d F Y, H:i') }}</p>
                    </div>
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
                    Anggota Kelompok
                    @php
                        $anggota = $proposal->anggota;
                        
                        // Fallback: If ProposalAnggota is empty, try to get from Kelompok via Ketua
                        if ($anggota->count() == 0) {
                             $kelompok = \App\Models\Kelompok::where('ketua_id', $proposal->ketua_id)->latest()->first();
                             if ($kelompok) {
                                 // Manual merge similar to Mahasiswa Controller
                                 // 1. Registered Users
                                 $pivotRows = \App\Models\KelompokUser::where('kelompok_id', $kelompok->id)->get();
                                 $userIds = $pivotRows->pluck('user_id')->filter()->unique()->values()->all();
                                 $users = count($userIds) ? \App\Models\User::whereIn('id', $userIds)->get()->keyBy('id') : collect();

                                 $anggotaRegistered = $pivotRows->map(function ($row) use ($users) {
                                     if ($row->user_id && isset($users[$row->user_id])) {
                                         $u = $users[$row->user_id];
                                         return (object) [
                                             'nama' => $u->name,
                                             'nim' => $u->nim ?? '-',
                                             'program_studi' => $u->program_studi ?? '-',
                                             'posisi' => $row->posisi ?? 'anggota'
                                         ];
                                     }
                                     return null;
                                 })->filter()->values();

                                 // 2. Free Users
                                 $freeRows = \App\Models\KelompokAnggota::where('kelompok_id', $kelompok->id)->get();
                                 $anggotaFree = $freeRows->map(function ($row) {
                                     return (object) [
                                         'nama' => $row->nama,
                                         'nim' => $row->nim ?? '-',
                                         'program_studi' => $row->program_studi ?? '-',
                                         'posisi' => $row->posisi ?? 'anggota'
                                     ];
                                 });
                                 
                                 $anggota = $anggotaRegistered->merge($anggotaFree);
                             }
                        }
                    @endphp
                    <span class="ml-2 px-2 py-0.5 bg-white/20 rounded-full text-xs">{{ $anggota->count() }}
                        Orang</span>
                </h6>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach ($anggota as $index => $member)
                        <div
                            class="flex items-center p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                            <div
                                class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold mr-4">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    @if (isset($member->posisi) && $member->posisi == 'ketua')
                                        <span
                                            class="px-2 py-0.5 bg-uhamka-100 text-uhamka-700 text-[10px] font-bold uppercase rounded-md">Ketua</span>
                                    @else
                                        <span
                                            class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] font-bold uppercase rounded-md">Anggota</span>
                                    @endif
                                    <h6 class="font-bold text-slate-900">{{ $member->nama }}</h6>
                                </div>
                                <p class="text-xs text-slate-500">{{ $member->nim }} • {{ $member->program_studi }}</p>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($anggota->count() == 0)
                         <p class="text-slate-500 italic text-center py-4">Tidak ada data anggota.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approval Actions -->
    @if ($proposal->status == 'menunggu_approval')
        <div class="bg-orange-50 border-l-4 border-orange-400 p-8 rounded-r-2xl shadow-sm mb-8">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-orange-100 rounded-full text-orange-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h5 class="text-lg font-bold text-orange-800 mb-2">Tindakan Diperlukan</h5>
                    <p class="text-orange-700 mb-6 max-w-2xl">Silakan tinjau proposal ini dan pilih tindakan yang sesuai.
                        Keputusan Anda akan menentukan langkah selanjutnya bagi mahasiswa.</p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <form action="{{ route('dosen.pengajuan_kelompok_pkm.approve', $proposal->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menyetujui proposal ini dan menjadi dosen pembimbing?')">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-1">
                                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Setujui Proposal
                            </button>
                        </form>

                        <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                            class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-all transform hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Tolak Proposal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    <!-- Reject Data -->
    <div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-slate-900 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="document.getElementById('rejectModal').classList.add('hidden')"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('dosen.pengajuan_kelompok_pkm.reject', $proposal->id) }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
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
                                <div class="mt-2 text-sm text-slate-500 mb-4">
                                    <p>Berikan alasan penolakan untuk membantu mahasiswa memperbaiki proposal mereka.</p>
                                </div>

                                <label for="catatan_penolakan"
                                    class="block text-sm font-medium text-slate-700 mb-2">Alasan Penolakan <span
                                        class="text-red-500">*</span></label>
                                <textarea id="catatan_penolakan" name="catatan_penolakan" rows="4"
                                    class="shadow-sm focus:ring-uhamka-500 focus:border-uhamka-500 mt-1 block w-full sm:text-sm border border-slate-300 rounded-xl p-3"
                                    placeholder="Contoh: Judul tidak sesuai dengan skema, metodologi kurang jelas..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Kirim Penolakan
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
@endpush
