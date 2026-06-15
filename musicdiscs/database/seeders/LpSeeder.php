<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LP;

class LpSeeder extends Seeder
{
    public function run(): void
    {
        // Get seller user ID
        $sellerId = DB::table('users')
            ->where('email', 'seller@example.com')
            ->value('id');

        // 1. Factory generated LPs (250)
        LP::factory()
            ->count(250)
            ->create([
                'user_id' => $sellerId,
            ]);

        // 2. Optional fixed LPs (ONLY if you really need them)
        LP::create([
            'user_id' => $sellerId,
            'album' => 'Abbey Road',
            'artist' => 'The Beatles',
            'release_year' => 1969,
            'price' => 25,
            'genre' => 'Rock',
            'status' => 'Available',
            'in_stock' => 10,
            'cover_image' => null,
            'number_of_tracks' => 17,
        ]);
    }
}
