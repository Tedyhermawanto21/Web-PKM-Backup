@extends('layouts.kaprodi')

@section('title', 'Dashboard Kaprodi')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Kaprodi</h1>
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
                                Profil Kaprodi
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">{{ $user->name }}
                            </div>
                            <div class="text-xs text-gray-600">NIDN: {{ $user->nidn }}</div>
                            <div class="text-xs text-gray-600">Prodi: {{ $user->program_studi }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Selamat Datang, {{ $user->name }}!</h6>
        </div>
        <div class="card-body">
            <p>Sebagai Kepala Program Studi, Anda dapat:</p>
            <ul>
                <li>Memverifikasi proposal PKM dari program studi</li>
                <li>Melihat laporan dan statistik PKM</li>
                <li>Mengelola dosen pembimbing</li>
            </ul>
        </div>
    </div>
@endsection
