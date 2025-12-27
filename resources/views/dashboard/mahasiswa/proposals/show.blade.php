@extends('layouts.mahasiswa')

@section('title', 'Detail Proposal PKM')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Proposal PKM</h1>
        <a href="{{ route('mahasiswa.proposals.index') }}"
            class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
        </a>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Status Badge -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-body text-center py-4">
                    <h4 class="mb-3">Status Proposal</h4>
                    @if ($proposal->status == 'draft')
                        <span class="badge badge-secondary p-3" style="font-size: 1.2rem;">
                            <i class="fas fa-file-alt"></i> Draft
                        </span>
                        <p class="mt-3 text-muted">Proposal masih dalam bentuk draft dan belum diajukan
                        </p>
                    @elseif($proposal->status == 'menunggu_approval')
                        <span class="badge badge-warning p-3" style="font-size: 1.2rem;">
                            <i class="fas fa-clock"></i> Menunggu Approval
                        </span>
                        <p class="mt-3 text-muted">Proposal sedang menunggu persetujuan dari dosen
                            pembimbing</p>
                    @elseif($proposal->status == 'disetujui')
                        <span class="badge badge-success p-3" style="font-size: 1.2rem;">
                            <i class="fas fa-check-circle"></i> Disetujui
                        </span>
                        <p class="mt-3 text-success font-weight-bold">Selamat! Proposal Anda telah
                            disetujui oleh dosen pembimbing</p>
                    @elseif($proposal->status == 'ditolak')
                        <span class="badge badge-danger p-3" style="font-size: 1.2rem;">
                            <i class="fas fa-times-circle"></i> Ditolak
                        </span>
                        <p class="mt-3 text-danger">Proposal ditolak oleh dosen pembimbing</p>
                        @if ($proposal->catatan_penolakan)
                            <div class="alert alert-danger mt-3">
                                <strong><i class="fas fa-info-circle"></i> Catatan
                                    Penolakan:</strong><br>
                                {{ $proposal->catatan_penolakan }}
                            </div>
                            <p class="text-muted">Anda dapat mengedit dan mengajukan proposal ini
                                kembali dengan dosen pembimbing yang berbeda</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Proposal Details -->
    <div class="row">
        <!-- Informasi Kelompok -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-users"></i> Informasi Kelompok
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Nama Kelompok</th>
                            <td>{{ $proposal->nama_kelompok }}</td>
                        </tr>
                        <tr>
                            <th>Judul PKM</th>
                            <td>{{ $proposal->judul_kelompok }}</td>
                        </tr>
                        <tr>
                            <th>Skema</th>
                            <td><span class="badge badge-info">{{ $proposal->skema }}</span></td>
                        </tr>
                        <tr>
                            <th>Dosen Pembimbing</th>
                            <td>
                                @if ($proposal->dosenPembimbing)
                                    <strong>{{ $proposal->dosenPembimbing->name }}</strong><br>
                                    <small class="text-muted">{{ $proposal->dosenPembimbing->program_studi }}</small>
                                @else
                                    <span class="text-muted">Belum dipilih</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Pengajuan</th>
                            <td>{{ $proposal->created_at->format('d F Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Daftar Anggota -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-user-friends"></i> Anggota
                        Kelompok</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @foreach ($proposal->anggota as $index => $anggota)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">
                                            @if ($anggota->posisi == 'ketua')
                                                <span class="badge badge-primary mr-2">Ketua</span>
                                            @else
                                                <span class="badge badge-secondary mr-2">Anggota
                                                    {{ $index }}</span>
                                            @endif
                                            {{ $anggota->nama }}
                                        </h6>
                                        <p class="mb-0"><small class="text-muted">NIM:
                                                {{ $anggota->nim }}</small></p>
                                        <p class="mb-0"><small class="text-muted">{{ $anggota->program_studi }}</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    @if (in_array($proposal->status, ['draft', 'ditolak']))
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-body text-center py-4">
                        <h5 class="mb-3">Tindakan</h5>
                        <a href="{{ route('mahasiswa.proposals.edit', $proposal->id) }}"
                            class="btn btn-warning btn-lg mr-2">
                            <i class="fas fa-edit"></i> Edit Proposal
                        </a>
                        <form action="{{ route('mahasiswa.proposals.destroy', $proposal->id) }}" method="POST"
                            class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus proposal ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="fas fa-trash"></i> Hapus Proposal
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
