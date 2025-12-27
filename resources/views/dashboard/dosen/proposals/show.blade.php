@extends('layouts.dosen')

@section('title', 'Detail Proposal')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Proposal PKM</h1>
        <a href="{{ route('dosen.proposals.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
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
                    @if ($proposal->status == 'menunggu_approval')
                        <span class="badge badge-warning p-3" style="font-size: 1.2rem;">
                            <i class="fas fa-clock"></i> Menunggu Approval
                        </span>
                        <p class="mt-3 text-muted">Proposal ini memerlukan persetujuan Anda</p>
                    @elseif($proposal->status == 'disetujui')
                        <span class="badge badge-success p-3" style="font-size: 1.2rem;">
                            <i class="fas fa-check-circle"></i> Disetujui
                        </span>
                        <p class="mt-3 text-success font-weight-bold">Anda telah menyetujui proposal
                            ini</p>
                    @elseif($proposal->status == 'ditolak')
                        <span class="badge badge-danger p-3" style="font-size: 1.2rem;">
                            <i class="fas fa-times-circle"></i> Ditolak
                        </span>
                        <p class="mt-3 text-danger">Proposal ini telah ditolak</p>
                        @if ($proposal->catatan_penolakan)
                            <div class="alert alert-danger mt-3">
                                <strong><i class="fas fa-info-circle"></i> Catatan
                                    Penolakan:</strong><br>
                                {{ $proposal->catatan_penolakan }}
                            </div>
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
                            <th>Ketua Kelompok</th>
                            <td>
                                <strong>{{ $proposal->ketua->name }}</strong><br>
                                <small class="text-muted">NIM: {{ $proposal->ketua->nim }}</small><br>
                                <small class="text-muted">{{ $proposal->ketua->program_studi }}</small>
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
                        Kelompok ({{ $proposal->anggota->count() }} Orang)</h6>
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

    <!-- Approval Actions -->
    @if ($proposal->status == 'menunggu_approval')
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow border-left-warning">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">
                            <i class="fas fa-exclamation-triangle"></i> Tindakan Diperlukan
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-4">Silakan tinjau proposal ini dan pilih tindakan yang sesuai:
                        </p>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h5 class="text-success"><i class="fas fa-check-circle"></i>
                                            Setujui Proposal</h5>
                                        <p class="text-muted">Dengan menyetujui, Anda akan menjadi
                                            dosen pembimbing kelompok ini.</p>
                                        <form action="{{ route('dosen.proposals.approve', $proposal->id) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menyetujui proposal ini dan menjadi dosen pembimbing?')">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-lg">
                                                <i class="fas fa-check"></i> Setujui Proposal
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card border-danger">
                                    <div class="card-body text-center">
                                        <h5 class="text-danger"><i class="fas fa-times-circle"></i>
                                            Tolak Proposal</h5>
                                        <p class="text-muted">Berikan alasan penolakan untuk membantu
                                            mahasiswa.</p>
                                        <button type="button" class="btn btn-danger btn-lg" data-toggle="modal"
                                            data-target="#rejectModal">
                                            <i class="fas fa-times"></i> Tolak Proposal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@push('modals')
    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('dosen.proposals.reject', $proposal->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="rejectModalLabel">
                            <i class="fas fa-times-circle"></i> Tolak Proposal
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="catatan_penolakan">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="catatan_penolakan" name="catatan_penolakan" rows="5" required
                                placeholder="Berikan alasan yang jelas mengapa proposal ini ditolak. Ini akan membantu mahasiswa untuk memperbaiki proposal mereka."></textarea>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Catatan ini akan dikirimkan kepada ketua kelompok.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-paper-plane"></i> Kirim Penolakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
