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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->string('judul_kelompok');
            $table->string('nama_kelompok');
            $table->string('skema'); // PKM-KC, PKM-RE, PKM-GT, PKM-AI, PKM-PM, PKM-K, PKM-VGK
            $table->foreignId('ketua_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen_pembimbing_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['draft', 'menunggu_approval', 'disetujui', 'ditolak'])->default('draft');
            $table->text('catatan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
