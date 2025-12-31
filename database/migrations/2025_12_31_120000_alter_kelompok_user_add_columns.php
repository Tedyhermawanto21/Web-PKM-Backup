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
        // no-op migration: previous attempt to alter pivot caused issues
        // This migration intentionally left empty to avoid runtime errors.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // no-op down
    }
};
