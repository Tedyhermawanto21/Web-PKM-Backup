@extends('layouts.mahasiswa')

@section('title', 'Dashboard')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard Mahasiswa</h1>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Profile Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Profil Mahasiswa
                        </div>
                        <div class="h6 mb-0 font-weight-bold text-gray-800">{{ $user->name }}</div>
                        <div class="text-xs text-gray-600">NIM: {{ $user->nim }}</div>
                        <div class="text-xs text-gray-600">Prodi: {{ $user->program_studi }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kelompok Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Kelompok PKM
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $user->kelompoks->count() }}
                        </div>
                        <div class="text-xs text-gray-600">Kelompok Terdaftar</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pengajuan Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Pengajuan PKM
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $user->kelompokAsKetua->count() }}
                        </div>
                        <div class="text-xs text-gray-600">Sebagai Ketua</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Welcome Message -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Selamat Datang, {{ $user->name }}!</h6>
            </div>
            <div class="card-body">
                <p>Selamat datang di PKM Center. Sistem informasi manajemen Program Kreativitas Mahasiswa.</p>
                <p>Melalui dashboard ini, Anda dapat:</p>
                <ul>
                    <li>Mengelola kelompok PKM</li>
                    <li>Mengajukan proposal PKM</li>
                    <li>Melihat status pengajuan</li>
                    <li>Berkomunikasi dengan dosen pembimbing</li>
                </ul>
                <a href="#" class="btn btn-primary">Buat Kelompok Baru</a>
            </div>
        </div>
    </div>
</div>
@endsection
