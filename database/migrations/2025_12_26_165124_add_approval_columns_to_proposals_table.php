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
        Schema::table('proposals', function (Blueprint $table) {
            $table->enum('status_dosen', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu')->after('status');
            $table->enum('status_kaprodi', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu')->after('status_dosen');
            $table->text('catatan_dosen')->nullable()->after('status_kaprodi');
            $table->text('catatan_kaprodi')->nullable()->after('catatan_dosen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn(['status_dosen', 'status_kaprodi', 'catatan_dosen', 'catatan_kaprodi']);
        });
    }
};
