<?php

namespace Database\Factories;

use App\Models\LP;
use Illuminate\Database\Eloquent\Factories\Factory;

class LPFactory extends Factory
{
    protected $model = LP::class;

    /**
     * Shared pool of exactly 150 unique artists
     */
    private static array $artists = [];

    private static function getArtists(): array
    {
        if (empty(self::$artists)) {
            self::$artists = collect(range(1, 150))
                ->map(fn ($i) => "Artist {$i}")
                ->toArray();
        }

        return self::$artists;
    }

    public function definition(): array
    {
        $artists = self::getArtists();

        return [
            'album' => fake()->words(rand(1, 4), true),

            // ensures only from 150 fixed artists
            'artist' => fake()->randomElement($artists),

            'release_year' => fake()->numberBetween(1950, 2025),

            'price' => fake()->numberBetween(10, 100),

            'genre' => fake()->randomElement([
                'Rock',
                'Pop',
                'Jazz',
                'Hip Hop',
                'Classical',
                'Electronic',
                'Metal',
                'Country'
            ]),

            'status' => fake()->randomElement([
                'New',
                'Used',
                'Mint',
                'Good'
            ]),

            'in_stock' => fake()->numberBetween(0, 50),

            'cover_image' => fake()->imageUrl(300, 300, 'music'),

            'number_of_tracks' => fake()->numberBetween(6, 20),
        ];
    }
}
