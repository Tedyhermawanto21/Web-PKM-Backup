@extends('layouts.mahasiswa')

@section('title', 'Detail Proposal')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Proposal</h1>
        <a href="{{ route('mahasiswa.upload.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Proposal</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Nama Kelompok</th>
                            <td>: {{ $proposal->nama_kelompok }}</td>
                        </tr>
                        <tr>
                            <th>Judul PKM</th>
                            <td>: {{ $proposal->judul_kelompok }}</td>
                        </tr>
                        <tr>
                            <th>Skema</th>
                            <td>: <span class="badge badge-info">{{ $proposal->skema }}</span></td>
                        </tr>
                        <tr>
                            <th>Ketua</th>
                            <td>: {{ $proposal->ketua->name }}</td>
                        </tr>
                        <tr>
                            <th>Dosen Pembimbing</th>
                            <td>: {{ $proposal->dosenPembimbing->name }}</td>
                        </tr>
                        <tr>
                            <th>Status Dosen</th>
                            <td>:
                                @if ($proposal->status_dosen == 'menunggu')
                                    <span class="badge badge-warning">Menunggu</span>
                                @elseif($proposal->status_dosen == 'disetujui')
                                    <span class="badge badge-success">Disetujui</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status Kaprodi</th>
                            <td>:
                                @if ($proposal->status_kaprodi == 'menunggu')
                                    <span class="badge badge-warning">Menunggu</span>
                                @elseif($proposal->status_kaprodi == 'disetujui')
                                    <span class="badge badge-success">Disetujui</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status Upload</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if ($proposal->status_admin == 'menunggu')
                            <i class="fas fa-clock fa-3x text-warning mb-2"></i>
                            <h5 class="text-warning">Menunggu Review</h5>
                        @elseif($proposal->status_admin == 'disetujui')
                            <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                            <h5 class="text-success">Disetujui</h5>
                        @elseif($proposal->status_admin == 'ditolak')
                            <i class="fas fa-times-circle fa-3x text-danger mb-2"></i>
                            <h5 class="text-danger">
                                @if ($proposal->revision_stage > 0)
                                    Perlu Revisi Tahap {{ $proposal->revision_stage }}
                                @else
                                    Ditolak
                                @endif
                            </h5>
                        @endif
                    </div>

                    @if ($proposal->revision_stage > 0)
                        <div class="alert alert-warning">
                            <strong><i class="fas fa-exclamation-triangle"></i> Status Revisi:</strong>
                            <p class="mb-0 mt-2">Proposal Anda memerlukan revisi tahap
                                {{ $proposal->revision_stage }}. Harap perbaiki sesuai catatan di
                                bawah.</p>
                        </div>
                    @endif

                    @if ($proposal->catatan_admin)
                        <div class="alert alert-info">
                            <strong>Catatan Admin:</strong>
                            <p class="mb-0 mt-2">{{ $proposal->catatan_admin }}</p>
                        </div>
                    @endif

                    @if ($proposal->file_proposal)
                        <a href="{{ Storage::url($proposal->file_proposal) }}" target="_blank"
                            class="btn btn-success btn-block">
                            <i class="fas fa-file-download"></i> Download File
                        </a>
                    @endif

                    @if ($proposal->status_admin == 'ditolak' || $proposal->revision_stage > 0)
                        @php
                            $canEdit = true;
                            // Check revision schedule if in revision stage
                            if ($proposal->revision_stage > 0) {
                                $revisionType = 'revisi_' . $proposal->revision_stage;
                                $revisionSchedule = \App\Models\Schedule::ofType($revisionType)
                                    ->active()
                                    ->ongoing()
                                    ->first();
                                $canEdit = $revisionSchedule != null;
                            }
                        @endphp
                        @if ($canEdit)
                            <a href="{{ route('mahasiswa.upload.edit', $proposal->id) }}"
                                class="btn btn-warning btn-block mt-2">
                                <i class="fas fa-edit"></i> Upload Ulang File
                            </a>
                        @else
                            <button class="btn btn-secondary btn-block mt-2" disabled>
                                <i class="fas fa-lock"></i> Jadwal Revisi Belum Dibuka
                            </button>
                        @endif
                        <i class="fas fa-edit"></i> Upload Ulang
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Anggota Kelompok</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Program Studi</th>
                            <th>Posisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proposal->anggota as $index => $anggota)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $anggota->nama }}</td>
                                <td>{{ $anggota->nim }}</td>
                                <td>{{ $anggota->program_studi }}</td>
                                <td>
                                    @if ($anggota->posisi == 'ketua')
                                        <span class="badge badge-primary">Ketua</span>
                                    @else
                                        <span class="badge badge-secondary">Anggota</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
