<?php

use Illuminate\Foundation\Application;
use App\Models\User;

require_once __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

// Boot the application
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

echo "=== Verifikasi Akun yang Berhasil Dibuat ===\n\n";

$accounts = [
    'mahasiswa@uhamka.ac.id',
    'dosen@uhamka.ac.id', 
    'kaprodi@uhamka.ac.id'
];

foreach ($accounts as $email) {
    $user = User::where('email', $email)->with('role')->first();
    
    if ($user) {
        echo "✓ {$user->name}\n";
        echo "  Email: {$user->email}\n";
        echo "  Role: {$user->role->name}\n";
        echo "  Password: uhamka123 (hash: " . substr($user->password, 0, 20) . "...)\n\n";
    } else {
        echo "✗ Akun {$email} tidak ditemukan\n\n";
    }
}

echo "=== Data Login ===\n";
echo "Username: mahasiswa@uhamka.ac.id | Password: uhamka123\n";
echo "Username: dosen@uhamka.ac.id      | Password: uhamka123\n"; 
echo "Username: kaprodi@uhamka.ac.id    | Password: uhamka123\n";