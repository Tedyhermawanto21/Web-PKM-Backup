@extends('layouts.dosen')

@section('title', 'Pengajuan Proposal')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pengajuan Proposal PKM</h1>
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menunggu Approval</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $proposals->where('status', 'menunggu_approval')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Disetujui</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $proposals->where('status', 'disetujui')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Ditolak</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $proposals->where('status', 'ditolak')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pengajuan Proposal</h6>
        </div>
        <div class="card-body">
            @if ($proposals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kelompok</th>
                                <th>Judul PKM</th>
                                <th>Skema</th>
                                <th>Ketua</th>
                                <th>Status Dosen</th>
                                <th>Status Kaprodi</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proposals as $index => $proposal)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $proposal->nama_kelompok }}</td>
                                    <td>{{ Str::limit($proposal->judul_kelompok, 50) }}</td>
                                    <td><span class="badge badge-info">{{ $proposal->skema }}</span>
                                    </td>
                                    <td>{{ $proposal->ketua->name }}</td>
                                    <td>
                                        @if ($proposal->status_dosen == 'disetujui')
                                            <span class="badge badge-success">Disetujui</span>
                                        @elseif($proposal->status_dosen == 'ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @else
                                            <span class="badge badge-warning">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($proposal->status_kaprodi == 'disetujui')
                                            <span class="badge badge-success">Disetujui</span>
                                        @elseif($proposal->status_kaprodi == 'ditolak')
                                            <span class="badge badge-danger">Ditolak</span>
                                        @else
                                            <span class="badge badge-warning">Menunggu</span>
                                        @endif
                                    </td>
                                    <td>{{ $proposal->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('dosen.proposals.show', $proposal->id) }}"
                                            class="btn btn-info btn-sm" title="Lihat Detail">
                                            <i class="fas fa-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada pengajuan proposal yang masuk.</p>
                </div>
            @endif
        </div>
    </div>

@endsection
