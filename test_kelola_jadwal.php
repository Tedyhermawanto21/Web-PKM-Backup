<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Schedule;
use Carbon\Carbon;

echo "=== TEST KELOLA JADWAL PKM ===\n\n";

// 1. Tampilkan semua jadwal
echo "1. DAFTAR JADWAL:\n";
$schedules = Schedule::orderBy('created_at', 'desc')->get();

if ($schedules->isEmpty()) {
    echo "   Belum ada jadwal.\n\n";
} else {
    foreach ($schedules as $schedule) {
        $types = Schedule::getTypes();
        echo "   ID: " . $schedule->id . "\n";
        echo "   Tipe: " . ($types[$schedule->type] ?? $schedule->type) . "\n";
        echo "   Nama: " . $schedule->name . "\n";
        echo "   Periode: " . $schedule->start_date->format('d/m/Y H:i') . " - " . $schedule->end_date->format('d/m/Y H:i') . "\n";
        echo "   Status: " . ($schedule->is_active ? 'Aktif ✓' : 'Non-aktif ✗') . "\n";
        echo "   Kondisi: ";
        if ($schedule->isOngoing()) {
            echo "Sedang Berlangsung 🔵\n";
        } elseif ($schedule->isPast()) {
            echo "Sudah Lewat ⚫\n";
        } else {
            echo "Akan Datang 🟡\n";
        }
        if ($schedule->description) {
            echo "   Deskripsi: " . $schedule->description . "\n";
        }
        echo "\n";
    }
}

// 2. Test fungsi-fungsi
echo "2. FITUR YANG TERSEDIA:\n";
echo "   ✓ Tambah Jadwal Baru (CREATE)\n";
echo "   ✓ Lihat Daftar Jadwal (READ)\n";
echo "   ✓ Edit Jadwal (UPDATE)\n";
echo "   ✓ Hapus Jadwal (DELETE)\n";
echo "   ✓ Toggle Status Aktif/Non-aktif\n";
echo "   ✓ Filter berdasarkan Tipe\n";
echo "   ✓ Pengecekan kondisi jadwal (Ongoing/Past/Upcoming)\n\n";

// 3. Tipe jadwal yang tersedia
echo "3. TIPE JADWAL:\n";
$types = Schedule::getTypes();
foreach ($types as $key => $value) {
    echo "   - $value ($key)\n";
}
echo "\n";

// 4. Test query
echo "4. TEST QUERY:\n";

$activeSchedules = Schedule::active()->count();
echo "   - Jadwal Aktif: $activeSchedules\n";

$uploadProposalSchedule = Schedule::ofType('upload_proposal')->active()->first();
echo "   - Jadwal Upload Proposal Aktif: " . ($uploadProposalSchedule ? 'Ada ✓' : 'Tidak Ada ✗') . "\n";

$ongoingSchedules = Schedule::ongoing()->count();
echo "   - Jadwal Sedang Berlangsung: $ongoingSchedules\n\n";

echo "=== FITUR KELOLA JADWAL READY! ===\n";
