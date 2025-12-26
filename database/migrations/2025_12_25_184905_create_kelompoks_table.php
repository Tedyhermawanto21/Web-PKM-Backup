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
        Schema::create('kelompoks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelompok');
            $table->string('judul_pkm');
            $table->string('jenis_pkm');
            $table->text('deskripsi')->nullable();
            $table->foreignId('ketua_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('dosen_pembimbing_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['draft', 'submitted', 'review', 'approved', 'rejected'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompoks');
    }
};
