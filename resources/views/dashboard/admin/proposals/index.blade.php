@extends('layouts.admin')

@section('title', 'Review Proposal')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Review Proposal PKM</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menunggu Review</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $proposals->where('status_admin', 'menunggu')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Disetujui</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $proposals->where('status_admin', 'disetujui')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Ditolak</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $proposals->where('status_admin', 'ditolak')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Proposal PKM</h6>
        </div>
        <div class="card-body">
            @if ($proposals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kelompok</th>
                                <th>Judul PKM</th>
                                <th>Ketua</th>
                                <th>Skema</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proposals as $index => $proposal)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $proposal->nama_kelompok }}</td>
                                    <td>{{ $proposal->judul_kelompok }}</td>
                                    <td>{{ $proposal->ketua->name }}</td>
                                    <td><span class="badge badge-info">{{ $proposal->skema }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ Storage::url($proposal->file_proposal) }}" target="_blank"
                                            class="btn btn-sm btn-success">
                                            <i class="fas fa-download"></i>
                                        </a>
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
                                        <a href="{{ route('admin.proposals.show', $proposal->id) }}"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
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
                    <p class="text-muted">Mahasiswa belum mengupload proposal PKM.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
