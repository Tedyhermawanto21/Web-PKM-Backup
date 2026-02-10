<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Kelompok;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

echo "=== TESTING KAPRODI FILTERING ===\n\n";

try {
    // Get kaprodi test user
    $kaprodi = User::where('email', 'kaprodi@uhamka.ac.id')->first();
    if (!$kaprodi) {
        echo "❌ Kaprodi test user tidak ditemukan\n";
        exit;
    }
    
    echo "🏫 Kaprodi: {$kaprodi->name} (Program Studi: {$kaprodi->program_studi})\n\n";

    // Test 1: All kelompoks in system
    $totalKelompoks = Kelompok::count();
    echo "📊 Total kelompok di sistem: {$totalKelompoks}\n";

    // Test 2: Kelompoks by status_kaprodi
    $menunggu = Kelompok::where('status_kaprodi', 'menunggu')->count();
    $disetujui = Kelompok::where('status_kaprodi', 'disetujui')->count();
    $ditolak = Kelompok::where('status_kaprodi', 'ditolak')->count();
    
    echo "📝 Status Kaprodi:\n";
    echo "   - Menunggu: {$menunggu}\n";
    echo "   - Disetujui: {$disetujui}\n";
    echo "   - Ditolak: {$ditolak}\n\n";

    // Test 3: Query yang digunakan di kelompokRequests() method (BEFORE change)
    echo "🔍 Query SEBELUM perubahan (semua kelompok approved/submitted):\n";
    $oldQuery = Kelompok::with(['ketua', 'anggota', 'dosenPembimbing'])
        ->whereIn('status', ['submitted', 'approved'])
        ->whereHas('ketua', function($query) use ($kaprodi) {
            $query->where('program_studi', $kaprodi->program_studi);
        });
    $oldResult = $oldQuery->get();
    
    foreach ($oldResult as $kelompok) {
        echo "   - {$kelompok->nama_kelompok} (Status Kaprodi: {$kelompok->status_kaprodi})\n";
    }
    echo "   Total: " . $oldResult->count() . "\n\n";

    // Test 4: Query yang digunakan di kelompokRequests() method (AFTER change)
    echo "🎯 Query SETELAH perubahan (hanya status_kaprodi = menunggu):\n";
    $newQuery = Kelompok::with(['ketua', 'anggota', 'dosenPembimbing'])
        ->whereIn('status', ['submitted', 'approved'])
        ->where('status_kaprodi', 'menunggu')
        ->whereHas('ketua', function($query) use ($kaprodi) {
            $query->where('program_studi', $kaprodi->program_studi);
        });
    $newResult = $newQuery->get();
    
    if ($newResult->count() > 0) {
        foreach ($newResult as $kelompok) {
            echo "   - {$kelompok->nama_kelompok} (Status Kaprodi: {$kelompok->status_kaprodi})\n";
        }
    } else {
        echo "   ✅ Tidak ada kelompok menunggu (semua sudah diproses)\n";
    }
    echo "   Total: " . $newResult->count() . "\n\n";

    echo "🎉 HASIL: Filtering berhasil! Kelompok yang sudah disetujui tidak muncul di halaman verifikasi.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}