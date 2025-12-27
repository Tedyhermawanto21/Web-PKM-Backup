@extends('layouts.mahasiswa')

@section('title', 'Pengajuan PKM')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pengajuan PKM</h1>
        <a href="{{ route('mahasiswa.proposals.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Buat Proposal Baru
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

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Proposal PKM Saya</h6>
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
                                <th>Dosen Pembimbing</th>
                                <th>Status Dosen</th>
                                <th>Status Kaprodi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($proposals as $index => $proposal)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $proposal->nama_kelompok }}</td>
                                    <td>{{ $proposal->judul_kelompok }}</td>
                                    <td><span class="badge badge-info">{{ $proposal->skema }}</span>
                                    </td>
                                    <td>{{ $proposal->dosenPembimbing->name ?? 'Belum dipilih' }}</td>
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
                                        <a href="{{ route('mahasiswa.proposals.show', $proposal->id) }}"
                                            class="btn btn-info btn-sm" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if (in_array($proposal->status, ['draft', 'ditolak']))
                                            <a href="{{ route('mahasiswa.proposals.edit', $proposal->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('mahasiswa.proposals.destroy', $proposal->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus proposal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada proposal yang diajukan.</p>
                    <a href="{{ route('mahasiswa.proposals.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Proposal Pertama
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection
