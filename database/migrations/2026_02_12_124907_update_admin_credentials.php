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
        $admin = \App\Models\User::whereHas('role', function($q) {
            $q->where('name', 'admin');
        })->first();

        if ($admin) {
            $admin->email = 'pkmcenter@uhamka.ac.id';
            $admin->password = \Illuminate\Support\Facades\Hash::make('uhamka123');
            $admin->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: Revert to original admin email if known
    }
};
