<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Kelompok;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

echo "=== CREATE TEST DATA FOR DEMONSTRATION ===\n\n";

try {
    // Get users
    $mahasiswa = User::where('email', 'mahasiswa@uhamka.ac.id')->first();
    $dosen = User::where('email', 'dosen@uhamka.ac.id')->first();
    
    if (!$mahasiswa || !$dosen) {
        echo "❌ Test users not found\n";
        exit;
    }

    // Create a new kelompok that's waiting for kaprodi verification
    $newKelompok = Kelompok::create([
        'nama_kelompok' => 'Tim Inovasi Digital',
        'judul_pkm' => 'Pengembangan Aplikasi Mobile untuk Edukasi Digital',
        'jenis_pkm' => 'PKM-KC',
        'deskripsi' => 'Aplikasi mobile edukatif untuk meningkatkan literasi digital mahasiswa.',
        'ketua_id' => $mahasiswa->id,
        'dosen_pembimbing_id' => $dosen->id,
        'status' => 'approved', // Approved by dosen
        'status_kaprodi' => 'menunggu' // Waiting for kaprodi
    ]);

    echo "✅ Kelompok baru '{$newKelompok->nama_kelompok}' dibuat dengan status_kaprodi = 'menunggu'\n\n";

    echo "📊 SUMMARY DATA:\n";
    echo "1. Kelompok 'Kelompok Hore' - status_kaprodi: disetujui (TIDAK akan muncul di verifikasi)\n";
    echo "2. Kelompok 'Tim Inovasi Digital' - status_kaprodi: menunggu (AKAN muncul di verifikasi)\n\n";

    echo "🎯 TESTING HASIL:\n";
    echo "Sekarang halaman 'Verifikasi Kelompok' akan menampilkan:\n";
    echo "- Hanya kelompok yang status_kaprodi = 'menunggu'\n";
    echo "- Kelompok yang sudah disetujui tidak muncul lagi\n\n";
    
    echo "✅ Test data siap! Silakan login ke dashboard kaprodi untuk melihat hasilnya.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}