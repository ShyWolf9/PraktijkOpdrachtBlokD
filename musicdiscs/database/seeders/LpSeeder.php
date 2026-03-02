<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <- THIS IS IMPORTANT
use App\Models\Lp;

class LpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get seller user ID
        $sellerId = DB::table('users')->where('email', 'seller@example.com')->value('id');
        
        DB::table('lp')->insert([
            [
                'user_id' => $sellerId,
                'album' => 'Abbey Road',
                'artist' => 'The Beatles',
                'release_year' => 1969,
                'price' => 25.00,
                'genre' => 'Rock',
                'status' => 'Available',
                'in_stock' => 10,
                'sold' => false,
                'cover_image' => null,
                'number_of_tracks' => 17,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $sellerId,
                'album' => 'Thriller',
                'artist' => 'Michael Jackson',
                'release_year' => 1982,
                'price' => 30.00,
                'genre' => 'Pop',
                'status' => 'Available',
                'in_stock' => 5,
                'sold' => false,
                'cover_image' => null,
                'number_of_tracks' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $sellerId,
                'album' => 'The Dark Side of the Moon',
                'artist' => 'Pink Floyd',
                'release_year' => 1973,
                'price' => 28.00,
                'genre' => 'Progressive Rock',
                'status' => 'Available',
                'in_stock' => 3,
                'sold' => false,
                'cover_image' => null,
                'number_of_tracks' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
