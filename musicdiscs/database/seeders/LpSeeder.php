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
        DB::table('lp')->insert([
            [
                'album' => 'Abbey Road',
                'artist' => 'The Beatles',
                'release_year' => 1969,
                'price' => 25,
                'genre' => 'Rock',
                'status' => 'Available',
                'in_stock' => 10,
                'cover_image' => null,
                'number_of_tracks' => 17,
            ],
            [
                'album' => 'Thriller',
                'artist' => 'Michael Jackson',
                'release_year' => 1982,
                'price' => 30,
                'genre' => 'Pop',
                'status' => 'Available',
                'in_stock' => 5,
                'cover_image' => null,
                'number_of_tracks' => 9,
            ],
            [
                'album' => 'The Dark Side of the Moon',
                'artist' => 'Pink Floyd',
                'release_year' => 1973,
                'price' => 28,
                'genre' => 'Progressive Rock',
                'status' => 'Out of Stock',
                'in_stock' => 0,
                'cover_image' => null,
                'number_of_tracks' => 10,
            ]
        ]);
    }
}
