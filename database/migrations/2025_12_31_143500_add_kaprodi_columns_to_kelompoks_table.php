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
        Schema::table('kelompoks', function (Blueprint $table) {
            $table->enum('status_kaprodi', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu')->after('status');
            $table->text('catatan_kaprodi')->nullable()->after('status_kaprodi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelompoks', function (Blueprint $table) {
            $table->dropColumn(['status_kaprodi', 'catatan_kaprodi']);
        });
    }
};
