<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lp', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            $table->boolean('sold')->default(false)->after('price');
        });
        
        // Modify price column to decimal
        DB::statement('ALTER TABLE lp MODIFY price DECIMAL(10, 2) DEFAULT 10.00');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lp', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'sold']);
        });
        
        // Revert price to integer
        DB::statement('ALTER TABLE lp MODIFY price INT DEFAULT 0');
    }
};
