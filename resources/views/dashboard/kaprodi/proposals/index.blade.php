@extends('layouts.kaprodi')

@section('title', 'Verifikasi Proposal')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pengajuan PKM</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Proposal PKM Saya</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kelompok</th>
                            <th>Judul PKM</th>
                            <th>Skema</th>
                            <th>Dosen Pembimbing</th>
                            <th>Status Dosen</th>
                            <th>Status Kaprodi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proposals as $index => $proposal)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $proposal->nama_kelompok }}</td>
                                <td>{{ $proposal->judul_kelompok }}</td>
                                <td><span class="badge badge-info">{{ $proposal->skema }}</span></td>
                                <td>{{ $proposal->dosenPembimbing->name ?? '-' }}</td>
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
                                <td>
                                    <a href="{{ route('kaprodi.proposals.show', $proposal->id) }}"
                                        class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada proposal yang perlu
                                    diverifikasi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
