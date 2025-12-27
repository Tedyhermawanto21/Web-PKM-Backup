@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    <h1 class="h3 mb-4 text-gray-800">Dashboard Admin</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Selamat Datang, Administrator!</h6>
        </div>
        <div class="card-body">
            <p>Sebagai Administrator, Anda memiliki akses penuh untuk:</p>
            <ul>
                <li>Mengelola semua pengguna sistem</li>
                <li>Mengatur konfigurasi sistem</li>
                <li>Melihat semua aktivitas dan log sistem</li>
                <li>Manajemen database dan backup</li>
            </ul>
        </div>
    </div>
@endsection
