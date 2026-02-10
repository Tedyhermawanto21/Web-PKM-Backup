<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Kelompok;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

echo "=== MEMBUAT DATA TEST KELOMPOK ===\n\n";

try {
    // Ambil user mahasiswa dan dosen test
    $mahasiswa = User::where('email', 'mahasiswa@uhamka.ac.id')->first();
    $dosen = User::where('email', 'dosen@uhamka.ac.id')->first();
    
    if (!$mahasiswa || !$dosen) {
        echo "❌ User mahasiswa atau dosen tidak ditemukan!\n";
        exit(1);
    }
    
    // Cek apakah sudah ada kelompok untuk mahasiswa ini
    $existingKelompok = Kelompok::where('ketua_id', $mahasiswa->id)->first();
    
    if ($existingKelompok) {
        echo "⚠️ Kelompok sudah ada untuk mahasiswa ini!\n";
        echo "Nama: {$existingKelompok->nama_kelompok}\n";
        echo "Status: {$existingKelompok->status}\n";
        echo "Status Kaprodi: {$existingKelompok->status_kaprodi}\n\n";
        
        // Update status jika belum approved
        if ($existingKelompok->status !== 'approved' || $existingKelompok->status_kaprodi !== 'disetujui') {
            $existingKelompok->update([
                'status' => 'approved',
                'status_kaprodi' => 'disetujui'
            ]);
            echo "✅ Status kelompok diupdate menjadi approved!\n";
        }
    } else {
        // Buat kelompok baru
        $kelompok = Kelompok::create([
            'nama_kelompok' => 'Tim Innovators',
            'judul_pkm' => 'Sistem Informasi Manajemen PKM Berbasis Web',
            'jenis_pkm' => 'PKM-KC',
            'deskripsi' => 'Pengembangan sistem informasi untuk memudahkan manajemen proposal PKM di perguruan tinggi.',
            'ketua_id' => $mahasiswa->id,
            'dosen_pembimbing_id' => $dosen->id,
            'status' => 'approved',
            'status_kaprodi' => 'disetujui',
            'catatan_kaprodi' => 'Proposal bagus, disetujui untuk melanjutkan ke tahap berikutnya.'
        ]);
        
        echo "✅ Kelompok baru berhasil dibuat!\n";
        echo "Nama: {$kelompok->nama_kelompok}\n";
        echo "Judul: {$kelompok->judul_pkm}\n";
        echo "Ketua: {$mahasiswa->name}\n";
        echo "Dosen Pembimbing: {$dosen->name}\n";
        echo "Status: {$kelompok->status}\n";
        echo "Status Kaprodi: {$kelompok->status_kaprodi}\n";
    }
    
    echo "\n=== INFO ===\n";
    echo "Silakan login sebagai mahasiswa@uhamka.ac.id untuk melihat kelompok yang sudah disetujui.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}