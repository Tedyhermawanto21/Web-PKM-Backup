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
        Schema::create('kelompok_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_id')->constrained('kelompoks')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('posisi', ['ketua', 'anggota'])->default('anggota');
            $table->timestamps();
            
            $table->unique(['kelompok_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_user');
    }
};
