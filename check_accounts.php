<?php

use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Foundation\Console\Kernel;
use Illuminate\Foundation\Application;

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

// Boot konsol kernel
$kernel = $app->make(Kernel::class);

// Connect to database
$app['db'];

echo "=== AKUN BERHASIL DIBUAT ===\n\n";

try {
    // Check users directly with SQL
    $users = \DB::select("
        SELECT u.name, u.email, r.name as role_name 
        FROM users u 
        JOIN roles r ON u.role_id = r.id 
        WHERE u.email IN ('mahasiswa@uhamka.ac.id', 'dosen@uhamka.ac.id', 'kaprodi@uhamka.ac.id')
        ORDER BY u.email
    ");

    foreach ($users as $user) {
        echo "✓ {$user->name}\n";
        echo "  Email: {$user->email}\n";
        echo "  Role: {$user->role_name}\n";
        echo "  Password: uhamka123\n\n";
    }
    
    echo "=== INFO LOGIN ===\n";
    echo "Akun Mahasiswa: mahasiswa@uhamka.ac.id\n";
    echo "Akun Dosen: dosen@uhamka.ac.id\n";
    echo "Akun Kaprodi: kaprodi@uhamka.ac.id\n";
    echo "Password semua: uhamka123\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}