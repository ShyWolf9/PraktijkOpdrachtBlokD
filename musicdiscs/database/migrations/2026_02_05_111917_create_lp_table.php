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
        Schema::create('lp', function (Blueprint $table) {
            $table->id();
            $table->string('album');
            $table->string('artist');
            $table->integer('release_year');
            $table->integer('price');
            $table->string('genre');
            $table->string('status');
            $table->integer('in_stock');
            $table->string('cover_image')->nullable();
            $table->integer('number_of_tracks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lp');
    }
};
