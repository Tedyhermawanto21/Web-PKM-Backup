<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Upload Proposal Schedule - Active for testing
        Schedule::create([
            'type' => Schedule::TYPE_UPLOAD_PROPOSAL,
            'name' => 'Periode Upload Proposal Semester Genap 2025',
            'start_date' => $now->copy()->subDay(),
            'end_date' => $now->copy()->addMonth(),
            'is_active' => true,
            'description' => 'Periode upload proposal PKM untuk semester genap tahun 2025. Pastikan proposal sudah disetujui oleh dosen pembimbing dan kaprodi.'
        ]);

        // Revision Stage 1 Schedule - Active for testing
        Schedule::create([
            'type' => Schedule::TYPE_REVISI_1,
            'name' => 'Revisi Tahap 1 - Semester Genap 2025',
            'start_date' => $now->copy()->subDay(),
            'end_date' => $now->copy()->addWeeks(2),
            'is_active' => true,
            'description' => 'Periode revisi tahap 1 untuk proposal yang memerlukan perbaikan minor.'
        ]);

        // Revision Stage 2 Schedule - Not yet open
        Schedule::create([
            'type' => Schedule::TYPE_REVISI_2,
            'name' => 'Revisi Tahap 2 - Semester Genap 2025',
            'start_date' => $now->copy()->addWeeks(2),
            'end_date' => $now->copy()->addWeeks(4),
            'is_active' => true,
            'description' => 'Periode revisi tahap 2 untuk proposal yang memerlukan perbaikan menengah.'
        ]);

        // Revision Stage 3 Schedule - Not yet open
        Schedule::create([
            'type' => Schedule::TYPE_REVISI_3,
            'name' => 'Revisi Tahap 3 - Semester Genap 2025',
            'start_date' => $now->copy()->addWeeks(4),
            'end_date' => $now->copy()->addWeeks(6),
            'is_active' => true,
            'description' => 'Periode revisi tahap 3 untuk proposal yang memerlukan perbaikan major.'
        ]);
    }
}

