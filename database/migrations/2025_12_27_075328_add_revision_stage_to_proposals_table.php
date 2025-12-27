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
            $table->integer('revision_stage')->default(0)->after('status_admin'); // 0: tidak revisi, 1-3: tahap revisi
            $table->text('revision_notes')->nullable()->after('revision_stage'); // Catatan revisi dari admin
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropColumn(['revision_stage', 'revision_notes']);
        });
    }
};
