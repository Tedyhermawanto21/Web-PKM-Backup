<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(
    Illuminate\Http\Request::capture()
);

echo "=== MEMBUAT AKUN ADMIN BARU ===\n\n";

try {
    // Cek apakah akun admin@uhamka.ac.id sudah ada
    $existingAdmin = User::where('email', 'admin@uhamka.ac.id')->first();
    
    if ($existingAdmin) {
        echo "⚠️ Akun admin@uhamka.ac.id sudah ada!\n";
        echo "Nama: {$existingAdmin->name}\n";
        echo "Email: {$existingAdmin->email}\n";
        echo "Akun sudah tersedia untuk digunakan.\n\n";
    } else {
        // Ambil role admin (role_id = 5)
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            echo "❌ Role admin tidak ditemukan!\n";
            exit(1);
        }
        
        // Buat akun admin baru
        $newAdmin = User::create([
            'name' => 'Admin Test',
            'nim' => null,
            'email' => 'admin@uhamka.ac.id',
            'password' => Hash::make('uhamka123'),
            'program_studi' => null,
            'no_hp' => '081234567883',
            'jenis_kelamin' => 'L',
            'nidn' => null,
            'role_id' => $adminRole->id,
        ]);
        
        echo "✅ Akun admin berhasil dibuat!\n";
        echo "Nama: {$newAdmin->name}\n";
        echo "Email: {$newAdmin->email}\n";
        echo "Password: uhamka123\n\n";
    }
    
    echo "=== SEMUA AKUN TEST TERSEDIA ===\n";
    echo "Mahasiswa: mahasiswa@uhamka.ac.id | Password: uhamka123\n";
    echo "Dosen: dosen@uhamka.ac.id | Password: uhamka123\n";
    echo "Kaprodi: kaprodi@uhamka.ac.id | Password: uhamka123\n";
    echo "Admin: admin@uhamka.ac.id | Password: uhamka123\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}