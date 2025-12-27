@extends('layouts.kaprodi')

@section('title', 'Detail Proposal')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Proposal PKM</h1>
        <a href="{{ route('kaprodi.proposals.index') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm"></i> Kembali
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <!-- Status Section -->
    <div class="row mb-4">
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-body text-center py-4">
                    <h4 class="mb-3">Status Verifikasi</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Status Dosen Pembimbing</h6>
                            @if ($proposal->status_dosen == 'disetujui')
                                <span class="badge badge-success p-3"><i class="fas fa-check-circle"></i> Disetujui</span>
                            @elseif($proposal->status_dosen == 'ditolak')
                                <span class="badge badge-danger p-3"><i class="fas fa-times-circle"></i>
                                    Ditolak</span>
                            @else
                                <span class="badge badge-warning p-3"><i class="fas fa-clock"></i>
                                    Menunggu</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h6>Status Kaprodi</h6>
                            @if ($proposal->status_kaprodi == 'disetujui')
                                <span class="badge badge-success p-3"><i class="fas fa-check-circle"></i> Disetujui</span>
                            @elseif($proposal->status_kaprodi == 'ditolak')
                                <span class="badge badge-danger p-3"><i class="fas fa-times-circle"></i> Ditolak</span>
                            @else
                                <span class="badge badge-warning p-3"><i class="fas fa-clock"></i>
                                    Menunggu Verifikasi</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Proposal -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Proposal</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4"><strong>Nama Kelompok:</strong></div>
                <div class="col-md-8">{{ $proposal->nama_kelompok }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Judul PKM:</strong></div>
                <div class="col-md-8">{{ $proposal->judul_kelompok }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Skema:</strong></div>
                <div class="col-md-8"><span class="badge badge-info">{{ $proposal->skema }}</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Ketua Kelompok:</strong></div>
                <div class="col-md-8">{{ $proposal->ketua->name }} ({{ $proposal->ketua->nim }})
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Dosen Pembimbing:</strong></div>
                <div class="col-md-8">{{ $proposal->dosenPembimbing->name ?? '-' }}</div>
            </div>
        </div>
    </div>

    <!-- Anggota -->
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proposal->anggota as $index => $anggota)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $anggota->nama }}</td>
                                <td>{{ $anggota->nim }}</td>
                                <td>{{ $anggota->program_studi }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada anggota</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Catatan -->
    @if ($proposal->catatan_dosen)
        <div class="alert alert-info">
            <strong><i class="fas fa-comment"></i> Catatan Dosen:</strong><br>
            {{ $proposal->catatan_dosen }}
        </div>
    @endif

    @if ($proposal->catatan_kaprodi)
        <div class="alert alert-warning">
            <strong><i class="fas fa-comment"></i> Catatan Kaprodi:</strong><br>
            {{ $proposal->catatan_kaprodi }}
        </div>
    @endif

    <!-- Action Buttons -->
    @if ($proposal->status_kaprodi == 'menunggu' && $proposal->status_dosen == 'disetujui')
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold">Verifikasi Proposal</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <form action="{{ route('kaprodi.proposals.approve', $proposal->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Catatan (opsional):</label>
                                <textarea name="catatan_kaprodi" class="form-control" rows="3" placeholder="Tambahkan catatan jika diperlukan"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check"></i> Setujui Proposal
                            </button>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <form action="{{ route('kaprodi.proposals.reject', $proposal->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Alasan Penolakan <span class="text-danger">*</span>:</label>
                                <textarea name="catatan_kaprodi" class="form-control" rows="3" placeholder="Berikan alasan penolakan" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-times"></i> Tolak Proposal
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
