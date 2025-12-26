<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Admin
            ['name' => 'Admin PKM', 'nim' => null, 'email' => 'admin@pkm.ac.id', 'password' => Hash::make('admin123'), 'program_studi' => null, 'no_hp' => '081234567890', 'jenis_kelamin' => 'L', 'nidn' => null, 'role_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            
            // Kaprodi
            ['name' => 'Dr. Siti Nurhaliza, M.T', 'nim' => null, 'email' => 'kaprodi.ti@ft.ac.id', 'password' => Hash::make('kaprodi123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567892', 'jenis_kelamin' => 'P', 'nidn' => '0402078902', 'role_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dr. Budi Santoso, M.Kom', 'nim' => null, 'email' => 'kaprodi.si@ft.ac.id', 'password' => Hash::make('kaprodi123'), 'program_studi' => 'Sistem Informasi', 'no_hp' => '081234567893', 'jenis_kelamin' => 'L', 'nidn' => '0403088903', 'role_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            
            // Dosen
            ['name' => 'Dr. Rina Wati, M.Kom', 'nim' => null, 'email' => 'rina.wati@ft.ac.id', 'password' => Hash::make('dosen123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567894', 'jenis_kelamin' => 'P', 'nidn' => '0404098904', 'role_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'M. Rizki Pratama, M.T', 'nim' => null, 'email' => 'rizki.pratama@ft.ac.id', 'password' => Hash::make('dosen123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567895', 'jenis_kelamin' => 'L', 'nidn' => '0405108905', 'role_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dra. Fitri Handayani, M.Si', 'nim' => null, 'email' => 'fitri.handayani@ft.ac.id', 'password' => Hash::make('dosen123'), 'program_studi' => 'Sistem Informasi', 'no_hp' => '081234567896', 'jenis_kelamin' => 'P', 'nidn' => '0406118906', 'role_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Agus Setiawan, M.Kom', 'nim' => null, 'email' => 'agus.setiawan@ft.ac.id', 'password' => Hash::make('dosen123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567897', 'jenis_kelamin' => 'L', 'nidn' => '0407128907', 'role_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            
            // Mahasiswa
            ['name' => 'Andi Wijaya', 'nim' => '2021001', 'email' => 'andi.wijaya@student.ac.id', 'password' => Hash::make('mahasiswa123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567898', 'jenis_kelamin' => 'L', 'nidn' => null, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dewi Lestari', 'nim' => '2021002', 'email' => 'dewi.lestari@student.ac.id', 'password' => Hash::make('mahasiswa123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567899', 'jenis_kelamin' => 'P', 'nidn' => null, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Raka Permana', 'nim' => '2021003', 'email' => 'raka.permana@student.ac.id', 'password' => Hash::make('mahasiswa123'), 'program_studi' => 'Teknik Informatika', 'no_hp' => '081234567900', 'jenis_kelamin' => 'L', 'nidn' => null, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Maya Sari', 'nim' => '2021004', 'email' => 'maya.sari@student.ac.id', 'password' => Hash::make('mahasiswa123'), 'program_studi' => 'Sistem Informasi', 'no_hp' => '081234567901', 'jenis_kelamin' => 'P', 'nidn' => null, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Faisal Rahman', 'nim' => '2021005', 'email' => 'faisal.rahman@student.ac.id', 'password' => Hash::make('mahasiswa123'), 'program_studi' => 'Sistem Informasi', 'no_hp' => '081234567902', 'jenis_kelamin' => 'L', 'nidn' => null, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sinta Maharani', 'nim' => '2021006', 'email' => 'sinta.maharani@student.ac.id', 'password' => Hash::make('mahasiswa123'), 'program_studi' => 'Sistem Informasi', 'no_hp' => '081234567903', 'jenis_kelamin' => 'P', 'nidn' => null, 'role_id' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('users')->insert($users);
    }
}
