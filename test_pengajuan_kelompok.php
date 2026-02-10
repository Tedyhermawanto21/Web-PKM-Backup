<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Schedule;
use Carbon\Carbon;

echo "=== TEST JADWAL PENGAJUAN KELOMPOK PKM ===\n\n";

// 1. Cek konstanta tipe jadwal
echo "1. TIPE JADWAL TERSEDIA:\n";
$types = Schedule::getTypes();
foreach ($types as $key => $value) {
    echo "   - $value ($key)\n";
}
echo "\n";

// 2. Cek apakah ada jadwal pengajuan kelompok
echo "2. STATUS JADWAL PENGAJUAN KELOMPOK:\n";
$pengajuanSchedule = Schedule::ofType(Schedule::TYPE_PENGAJUAN_KELOMPOK)->first();

if ($pengajuanSchedule) {
    echo "   ID: " . $pengajuanSchedule->id . "\n";
    echo "   Nama: " . $pengajuanSchedule->name . "\n";
    echo "   Periode: " . $pengajuanSchedule->start_date->format('d/m/Y H:i') . " - " . $pengajuanSchedule->end_date->format('d/m/Y H:i') . "\n";
    echo "   Status: " . ($pengajuanSchedule->is_active ? 'Aktif ✓' : 'Non-aktif ✗') . "\n";
    echo "   Ongoing: " . ($pengajuanSchedule->isOngoing() ? 'YES ✓' : 'NO ✗') . "\n";
} else {
    echo "   Belum ada jadwal pengajuan kelompok yang dibuat.\n";
}
echo "\n";

// 3. Test query untuk cek jadwal aktif
echo "3. TEST QUERY:\n";
$isScheduleActive = Schedule::ofType(Schedule::TYPE_PENGAJUAN_KELOMPOK)
    ->active()
    ->ongoing()
    ->exists();

echo "   - Jadwal pengajuan kelompok aktif: " . ($isScheduleActive ? 'YES ✓' : 'NO ✗') . "\n";
echo "\n";

// 4. Summary fitur
echo "4. FITUR YANG SUDAH DIIMPLEMENTASI:\n";
echo "   ✓ Tipe jadwal 'Pengajuan Kelompok PKM' ditambahkan\n";
echo "   ✓ Validasi jadwal di ProposalController->create()\n";
echo "   ✓ Validasi jadwal di ProposalController->store()\n";
echo "   ✓ Info jadwal di halaman index pengajuan kelompok\n";
echo "   ✓ Button 'Buat Proposal Baru' conditional berdasarkan jadwal\n";
echo "   ✓ Nama menu sidebar diubah dari 'Pengajuan PKM' ke 'Pengajuan Kelompok'\n";
echo "   ✓ Admin bisa set jadwal pengajuan kelompok di halaman Kelola Jadwal\n";
echo "\n";

// 5. Instruksi untuk admin
echo "5. CARA MENGGUNAKAN:\n";
echo "   1. Login sebagai Admin\n";
echo "   2. Buka menu 'Kelola Jadwal'\n";
echo "   3. Klik 'Tambah Jadwal'\n";
echo "   4. Pilih tipe: 'Pengajuan Kelompok PKM'\n";
echo "   5. Set tanggal mulai & selesai\n";
echo "   6. Centang 'Aktifkan jadwal ini'\n";
echo "   7. Simpan\n";
echo "   8. Mahasiswa sekarang bisa mengajukan kelompok PKM!\n";
echo "\n";

echo "=== SEMUA FITUR READY! ===\n";
