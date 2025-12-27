<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // 'upload_proposal', 'revisi_1', 'revisi_2', 'revisi_3'
            $table->string('name'); // Nama jadwal untuk tampilan
            $table->dateTime('start_date'); // Tanggal pembukaan
            $table->dateTime('end_date'); // Tanggal penutupan
            $table->boolean('is_active')->default(true); // Status aktif/non-aktif
            $table->text('description')->nullable(); // Deskripsi tambahan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
