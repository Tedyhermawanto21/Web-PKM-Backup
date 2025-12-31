<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('proposal_reviewer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('proposal_id')->index();
            $table->unsignedBigInteger('reviewer_id')->index();
            $table->enum('status', ['pending', 'reviewed'])->default('pending');
            $table->decimal('score', 5, 2)->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['proposal_id', 'reviewer_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('proposal_reviewer');
    }
};
