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
            $table->string('file_proposal')->nullable()->after('catatan_penolakan');
            $table->enum('status_admin', ['pending', 'disetujui', 'ditolak', 'revisi'])->default('pending')->after('file_proposal');
            $table->text('catatan_admin')->nullable()->after('status_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn(['file_proposal', 'status_admin', 'catatan_admin']);
        });
    }
};
