@extends('layouts.dosen')

@section('title', 'Dashboard Dosen')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Dosen</h1>
    </div>

    <!-- Statistics Cards Row -->
    <div class="row">
        <!-- Proposal Perlu Review Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Menunggu Approval</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $user->proposalsAsDosen()->where('status', 'menunggu_approval')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Disetujui Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Disetujui</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $user->proposalsAsDosen()->where('status', 'disetujui')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ditolak Card -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Ditolak</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $user->proposalsAsDosen()->where('status', 'ditolak')->count() }}
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

    <!-- Alert if proposals pending -->
    @if ($user->proposalsAsDosen()->where('status', 'menunggu_approval')->count() > 0)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Perhatian!</strong> Anda memiliki
            <strong>{{ $user->proposalsAsDosen()->where('status', 'menunggu_approval')->count() }}</strong>
            proposal yang menunggu review Anda.
            <a href="{{ route('dosen.proposals.index') }}" class="alert-link">Klik di
                sini untuk mereview</a>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Welcome Card -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Selamat Datang,
                        {{ $user->name }}!</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <p class="mb-2"><strong>NIDN:</strong>
                                {{ $user->nidn }}</p>
                            <p class="mb-2"><strong>Program Studi:</strong>
                                {{ $user->program_studi }}</p>
                            <hr>
                            <p class="mb-2">Sebagai dosen pembimbing, Anda dapat:</p>
                            <ul class="mb-0">
                                <li>Mereview dan menyetujui/menolak proposal PKM
                                    mahasiswa</li>
                                <li>Membimbing kelompok PKM mahasiswa</li>
                                <li>Memberikan review dan masukan pada proposal</li>
                                <li>Memantau progress kelompok bimbingan</li>
                            </ul>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-chalkboard-teacher fa-5x text-gray-300 mb-3"></i>
                            <p class="text-muted">Dosen Pembimbing PKM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
