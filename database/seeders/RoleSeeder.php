<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'mahasiswa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'dosen', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'kaprodi', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'admin', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('roles')->insert($roles);
    }
}
