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
        Schema::create('skemas', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique(); // PKM-RE, PKM-K, dll
            $table->string('label'); // Riset Eksakta, Kewirausahaan, dll
            $table->string('warna')->nullable(); // blue, red, green, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skemas');
    }
};
