<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

echo "=== VERIFIKASI SEMUA AKUN TEST ===\n\n";

$testAccounts = [
    'mahasiswa@uhamka.ac.id',
    'dosen@uhamka.ac.id', 
    'kaprodi@uhamka.ac.id',
    'admin@uhamka.ac.id'
];

foreach ($testAccounts as $email) {
    $user = User::where('email', $email)->with('role')->first();
    
    if ($user) {
        echo "✅ {$user->name}\n";
        echo "   Email: {$user->email}\n";
        echo "   Role: {$user->role->name}\n";
        echo "   Password: uhamka123\n\n";
    } else {
        echo "❌ Akun {$email} tidak ditemukan\n\n";
    }
}

echo "=== RINGKASAN LOGIN ===\n";
echo "👨‍🎓 Mahasiswa: mahasiswa@uhamka.ac.id\n";
echo "👨‍🏫 Dosen: dosen@uhamka.ac.id\n"; 
echo "👨‍💼 Kaprodi: kaprodi@uhamka.ac.id\n";
echo "🔧 Admin: admin@uhamka.ac.id\n";
echo "🔑 Password semua: uhamka123\n";