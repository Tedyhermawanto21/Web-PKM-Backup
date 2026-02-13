<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('proposal_reviewer', function (Blueprint $table) {
            $table->timestamp('reviewed_at')->nullable()->after('comments');
        });
    }

    public function down()
    {
        Schema::table('proposal_reviewer', function (Blueprint $table) {
            $table->dropColumn('reviewed_at');
        });
    }
};
