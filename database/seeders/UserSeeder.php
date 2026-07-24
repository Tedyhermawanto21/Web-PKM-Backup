<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\NomorInduk;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usersData = [
            // Admin
            ['name' => 'Admin PKM', 'email' => 'admin@pkm.ac.id', 'password' => Hash::make('admin123'), 'program_studi' => null, 'no_hp' => '081234567890', 'jenis_kelamin' => 'L', 'role_id' => 5],
            // Default Test Accounts
            ['name' => 'Mahasiswa Test', 'nim' => '2025001', 'email' => 'mahasiswa@uhamka.ac.id', 'password' => Hash::make('uhamka123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567880', 'jenis_kelamin' => 'L', 'role_id' => 1],
            ['name' => 'Dosen Test', 'nidn' => '0425010001', 'email' => 'dosen@uhamka.ac.id', 'password' => Hash::make('uhamka123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567881', 'jenis_kelamin' => 'L', 'role_id' => 2],
            ['name' => 'Kaprodi Test', 'nidn' => '0425010002', 'email' => 'kaprodi@uhamka.ac.id', 'password' => Hash::make('uhamka123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567882', 'jenis_kelamin' => 'L', 'role_id' => 3],
            ['name' => 'Admin Test', 'email' => 'admin@uhamka.ac.id', 'password' => Hash::make('uhamka123'), 'program_studi' => null, 'no_hp' => '081234567883', 'jenis_kelamin' => 'L', 'role_id' => 5],
            
            // Kaprodi
            ['name' => 'Dr. Siti Nurhaliza, M.T', 'nidn' => '0402078902', 'email' => 'kaprodi.ti@ft.ac.id', 'password' => Hash::make('kaprodi123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567892', 'jenis_kelamin' => 'P', 'role_id' => 3],
            ['name' => 'Dr. Budi Santoso, M.Kom', 'nidn' => '0403088903', 'email' => 'kaprodi.si@ft.ac.id', 'password' => Hash::make('kaprodi123'), 'program_studi' => 'Sistem Informasi', 'no_hp' => '081234567893', 'jenis_kelamin' => 'L', 'role_id' => 3],
            
            // Dosen
            ['name' => 'Dr. Rina Wati, M.Kom', 'nidn' => '0404098904', 'email' => 'rina.wati@ft.ac.id', 'password' => Hash::make('dosen123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567894', 'jenis_kelamin' => 'P', 'role_id' => 2],
            ['name' => 'M. Rizki Pratama, M.T', 'nidn' => '0405108905', 'email' => 'rizki.pratama@ft.ac.id', 'password' => Hash::make('dosen123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567895', 'jenis_kelamin' => 'L', 'role_id' => 2],
            ['name' => 'Dra. Fitri Handayani, M.Si', 'nidn' => '0406118906', 'email' => 'fitri.handayani@ft.ac.id', 'password' => Hash::make('dosen123'), 'program_studi' => 'Sistem Informasi', 'no_hp' => '081234567896', 'jenis_kelamin' => 'P', 'role_id' => 2],
            ['name' => 'Agus Setiawan, M.Kom', 'nidn' => '0407128907', 'email' => 'agus.setiawan@ft.ac.id', 'password' => Hash::make('dosen123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567897', 'jenis_kelamin' => 'L', 'role_id' => 2],
            
            // Mahasiswa
            ['name' => 'Andi Wijaya', 'nim' => '2021001', 'email' => 'andi.wijaya@student.ac.id', 'password' => Hash::make('mahasiswa123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567898', 'jenis_kelamin' => 'L', 'role_id' => 1],
            ['name' => 'Dewi Lestari', 'nim' => '2021002', 'email' => 'dewi.lestari@student.ac.id', 'password' => Hash::make('mahasiswa123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567899', 'jenis_kelamin' => 'P', 'role_id' => 1],
            ['name' => 'Raka Permana', 'nim' => '2021003', 'email' => 'raka.permana@student.ac.id', 'password' => Hash::make('mahasiswa123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567900', 'jenis_kelamin' => 'L', 'role_id' => 1],
            ['name' => 'Maya Sari', 'nim' => '2021004', 'email' => 'maya.sari@student.ac.id', 'password' => Hash::make('mahasiswa123'), 'program_studi' => 'Sistem Informasi', 'no_hp' => '081234567901', 'jenis_kelamin' => 'P', 'role_id' => 1],
            ['name' => 'Faisal Rahman', 'nim' => '2021005', 'email' => 'faisal.rahman@student.ac.id', 'password' => Hash::make('mahasiswa123'), 'program_studi' => 'Sistem Informasi', 'no_hp' => '081234567902', 'jenis_kelamin' => 'L', 'role_id' => 1],
            ['name' => 'Sinta Maharani', 'nim' => '2021006', 'email' => 'sinta.maharani@student.ac.id', 'password' => Hash::make('mahasiswa123'), 'program_studi' => 'Sistem Informasi', 'no_hp' => '081234567903', 'jenis_kelamin' => 'P', 'role_id' => 1],
        ];

        foreach ($usersData as $data) {
            $nim = $data['nim'] ?? null;
            $nidn = $data['nidn'] ?? null;
            unset($data['nim'], $data['nidn']);
            
            $data['created_at'] = now();
            $data['updated_at'] = now();

            $nomorIndukId = null;
            if ($nim) {
                $nomorInduk = NomorInduk::create(['value' => $nim, 'type' => 'nim']);
                $nomorIndukId = $nomorInduk->id;
            } elseif ($nidn) {
                $nomorInduk = NomorInduk::create(['value' => $nidn, 'type' => 'nidn']);
                $nomorIndukId = $nomorInduk->id;
            }

            $data['nomor_induk_id'] = $nomorIndukId;
            DB::table('users')->insert($data);
        }
    }
}
