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
        Schema::create('guess_number_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('difficulty');
            $table->unsignedInteger('attempts');
            $table->unsignedInteger('target');
            $table->decimal('reward', 12, 2);
            $table->timestamps();

            $table->index(['difficulty', 'reward']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guess_number_scores');
    }
};
