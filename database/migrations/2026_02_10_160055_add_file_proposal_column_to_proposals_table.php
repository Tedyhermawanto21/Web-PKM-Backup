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
            if (!Schema::hasColumn('proposals', 'file_proposal')) {
                $table->string('file_proposal')->nullable()->after('catatan_penolakan');
            }
            if (!Schema::hasColumn('proposals', 'status_admin')) {
                $table->enum('status_admin', ['pending', 'disetujui', 'ditolak', 'revisi'])->default('pending')->after('file_proposal');
            }
            if (!Schema::hasColumn('proposals', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('status_admin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            if (Schema::hasColumn('proposals', 'file_proposal')) {
                $table->dropColumn('file_proposal');
            }
            if (Schema::hasColumn('proposals', 'status_admin')) {
                $table->dropColumn('status_admin');
            }
            if (Schema::hasColumn('proposals', 'catatan_admin')) {
                $table->dropColumn('catatan_admin');
            }
        });
    }
};
