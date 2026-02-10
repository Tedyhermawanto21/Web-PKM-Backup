<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Kelompok;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

echo "=== SETUP DATA TEST KAPRODI ===\n\n";

try {
    // Update kaprodi test dengan program studi
    $kaprodi = User::where('email', 'kaprodi@uhamka.ac.id')->first();
    if ($kaprodi) {
        $kaprodi->update(['program_studi' => 'Teknik Informatika']);
        echo "✅ Kaprodi Test diupdate dengan prodi: Teknik Informatika\n\n";
    }

    // Update mahasiswa test dengan program studi yang sama
    $mahasiswa = User::where('email', 'mahasiswa@uhamka.ac.id')->first();
    if ($mahasiswa) {
        $mahasiswa->update(['program_studi' => 'Teknik Informatika']);
        echo "✅ Mahasiswa Test diupdate dengan prodi: Teknik Informatika\n\n";
    }

    // Update kelompok yang ada menjadi disetujui kaprodi
    $kelompoks = Kelompok::where('status', 'approved')->get();
    
    foreach ($kelompoks as $kelompok) {
        $kelompok->update([
            'status_kaprodi' => 'disetujui',
            'catatan_kaprodi' => 'Proposal baik dan sesuai dengan program studi, disetujui untuk melanjutkan.'
        ]);
        echo "✅ Kelompok '{$kelompok->nama_kelompok}' disetujui kaprodi\n";
    }
    
    // Buat kelompok baru untuk mahasiswa sistem informasi (prodi berbeda)
    $mahasiswaSI = User::create([
        'name' => 'Budi Sistem Informasi',
        'nim' => '2025002',
        'email' => 'budi.si@uhamka.ac.id',
        'password' => bcrypt('uhamka123'),
        'program_studi' => 'Sistem Informasi',
        'no_hp' => '081234567884',
        'jenis_kelamin' => 'L',
        'nidn' => null,
        'role_id' => 1, // Mahasiswa
    ]);

    $dosen = User::where('email', 'dosen@uhamka.ac.id')->first();
    
    $kelompokSI = Kelompok::create([
        'nama_kelompok' => 'Tim Smart Campus',
        'judul_pkm' => 'Sistem Informasi Smart Campus Berbasis IoT',
        'jenis_pkm' => 'PKM-GT',
        'deskripsi' => 'Pengembangan sistem informasi kampus pintar menggunakan teknologi IoT.',
        'ketua_id' => $mahasiswaSI->id,
        'dosen_pembimbing_id' => $dosen->id,
        'status' => 'approved',
        'status_kaprodi' => 'menunggu'
    ]);

    echo "✅ Kelompok baru '{$kelompokSI->nama_kelompok}' dibuat untuk prodi Sistem Informasi\n\n";
    
    echo "=== SUMMARY ===\n";
    echo "Kaprodi TI dapat melihat:\n";
    echo "- Kelompok yang sudah disetujui: " . Kelompok::whereHas('ketua', function($q) {
        $q->where('program_studi', 'Teknik Informatika');
    })->where('status_kaprodi', 'disetujui')->count() . " kelompok\n";
    
    echo "- Kelompok yang menunggu verifikasi: " . Kelompok::whereHas('ketua', function($q) {
        $q->where('program_studi', 'Teknik Informatika');
    })->whereIn('status', ['submitted', 'approved'])->where('status_kaprodi', 'menunggu')->count() . " kelompok\n\n";
    
    echo "=== TESTING ===\n";
    echo "1. Login sebagai kaprodi@uhamka.ac.id / uhamka123\n";
    echo "2. Klik 'Daftar Mahasiswa' untuk melihat mahasiswa TI yang sudah disetujui\n";
    echo "3. Klik 'Verifikasi Kelompok' untuk melihat permintaan TI saja\n\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}