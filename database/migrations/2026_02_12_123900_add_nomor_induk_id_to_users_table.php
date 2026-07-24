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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('nomor_induk_id')->nullable()->constrained('nomor_induks')->onDelete('set null');
        });

        // Migrate existing data
        $users = \App\Models\User::all();
        foreach ($users as $user) {
            if ($user->nim) {
                $nomorInduk = \App\Models\NomorInduk::create([
                    'value' => $user->nim,
                    'type' => 'nim',
                ]);
                $user->nomor_induk_id = $nomorInduk->id;
                $user->save();
            } elseif ($user->nidn) {
                $nomorInduk = \App\Models\NomorInduk::create([
                    'value' => $user->nidn,
                    'type' => 'nidn',
                ]);
                $user->nomor_induk_id = $nomorInduk->id;
                $user->save();
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_nim_unique');
            $table->dropColumn(['nim', 'nidn']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nim')->nullable();
            $table->string('nidn')->nullable();
            $table->dropForeign(['nomor_induk_id']);
            $table->dropColumn('nomor_induk_id');
        });
    }
};
