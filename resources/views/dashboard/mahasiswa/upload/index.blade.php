@extends('layouts.mahasiswa')

@section('title', 'Upload Proposal')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Upload Proposal PKM</h1>
        @if ($uploadSchedule)
            <a href="{{ route('mahasiswa.upload.create') }}" class="btn btn-primary">
                <i class="fas fa-upload"></i> Upload Proposal PKM
            </a>
        @else
            <button class="btn btn-secondary" disabled>
                <i class="fas fa-lock"></i> Upload Ditutup
            </button>
        @endif
    </div>

    <!-- Schedule Information -->
    @if ($uploadSchedule)
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-calendar-check"></i>
            <strong>Jadwal Upload Dibuka!</strong><br>
            Periode upload proposal:
            <strong>{{ $uploadSchedule->start_date->format('d M Y H:i') }}</strong>
            s/d
            <strong>{{ $uploadSchedule->end_date->format('d M Y H:i') }}</strong>
            @if ($uploadSchedule->description)
                <br><small>{{ $uploadSchedule->description }}</small>
            @endif
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @else
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Jadwal Upload Belum Dibuka</strong><br>
            Mohon tunggu pengumuman dari admin untuk jadwal upload proposal berikutnya.
        </div>
    @endif

    <!-- Revision Schedule Information -->
    @php
        $hasRevision = $uploadedProposals->where('revision_stage', '>', 0)->first();
        $revisionSchedule = null;
        if ($hasRevision) {
            $revisionType = 'revisi_' . $hasRevision->revision_stage;
            $revisionSchedule = \App\Models\Schedule::ofType($revisionType)->active()->ongoing()->first();
        }
    @endphp
    @if ($hasRevision && $revisionSchedule)
        <div class="alert alert-info alert-dismissible fade show">
            <i class="fas fa-calendar-check"></i>
            <strong>Jadwal Revisi Tahap {{ $hasRevision->revision_stage }} Dibuka!</strong><br>
            Periode revisi:
            <strong>{{ $revisionSchedule->start_date->format('d M Y H:i') }}</strong>
            s/d
            <strong>{{ $revisionSchedule->end_date->format('d M Y H:i') }}</strong>
            @if ($revisionSchedule->description)
                <br><small>{{ $revisionSchedule->description }}</small>
            @endif
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @elseif($hasRevision)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Jadwal Revisi Tahap {{ $hasRevision->revision_stage }} Belum Dibuka</strong><br>
            Proposal Anda memerlukan revisi. Mohon tunggu jadwal revisi dibuka oleh admin.
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Proposal PKM yang Sudah Diupload</h6>
        </div>
        <div class="card-body">
            @if ($uploadedProposals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kelompok</th>
                                <th>Judul PKM</th>
                                <th>Skema</th>
                                <th>File</th>
                                <th>Status Admin</th>
                                <th>Tahap Revisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($uploadedProposals as $index => $proposal)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $proposal->nama_kelompok }}</td>
                                    <td>{{ $proposal->judul_kelompok }}</td>
                                    <td><span class="badge badge-info">{{ $proposal->skema }}</span>
                                    </td>
                                    <td>
                                        @if ($proposal->file_proposal)
                                            <a href="{{ Storage::url($proposal->file_proposal) }}" target="_blank"
                                                class="btn btn-sm btn-success">
                                                <i class="fas fa-file-download"></i> Download
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($proposal->status_admin == 'menunggu')
                                            <span class="badge badge-warning">Menunggu Review</span>
                                        @elseif($proposal->status_admin == 'disetujui')
                                            <span class="badge badge-success">Disetujui</span>
                                        @elseif($proposal->status_admin == 'ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($proposal->revision_stage > 0)
                                            <span class="badge badge-warning">Revisi
                                                {{ $proposal->revision_stage }}</span>
                                        @else
                                            <span class="badge badge-secondary">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('mahasiswa.upload.show', $proposal->id) }}"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
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
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled
                                                    title="Jadwal revisi belum dibuka">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <img src="{{ asset('dashboard-assets/img/undraw_empty.svg') }}" alt="No Data"
                        style="max-width: 300px; margin-bottom: 20px;">
                    <h5 class="text-gray-600 mb-2">Belum Ada Proposal yang Diupload</h5>
                    <p class="text-muted">Klik tombol "Upload Proposal PKM" untuk mengupload file
                        proposal Anda.</p>
                    <a href="{{ route('mahasiswa.upload.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-upload"></i> Upload Proposal PKM
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
