<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Kelompok;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

echo "=== MEMBUAT REQUEST PEMBIMBING UNTUK DOSEN ===\n\n";

try {
    // Ambil user dosen test
    $dosen = User::where('email', 'dosen@uhamka.ac.id')->first();
    
    if (!$dosen) {
        echo "❌ Dosen tidak ditemukan!\n";
        exit(1);
    }
    
    // Buat kelompok baru yang meminta dosen sebagai pembimbing
    $kelompokRequest = Kelompok::create([
        'nama_kelompok' => 'Kelompok Hore',
        'judul_pkm' => 'Sistem Pendukung Keputusan', 
        'jenis_pkm' => 'PKM-KC',
        'deskripsi' => 'Aplikasi sistem pendukung keputusan untuk menentukan lokasi usaha terbaik.',
        'ketua_id' => 2, // Mahasiswa Test ID
        'dosen_pembimbing_id' => $dosen->id,
        'status' => 'submitted', // Status submitted untuk permintaan pembimbing
        'status_kaprodi' => 'menunggu'
    ]);
    
    echo "✅ Kelompok permintaan pembimbing berhasil dibuat!\n";
    echo "Nama: {$kelompokRequest->nama_kelompok}\n";
    echo "Judul: {$kelompokRequest->judul_pkm}\n";
    echo "Status: {$kelompokRequest->status}\n";
    echo "Dosen yang diminta: {$dosen->name}\n\n";
    
    echo "=== INFO ===\n";
    echo "Login sebagai dosen@uhamka.ac.id untuk melihat permintaan pembimbing.\n";
    echo "Setelah diterima, kelompok akan muncul di halaman Bimbingan Mahasiswa.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}