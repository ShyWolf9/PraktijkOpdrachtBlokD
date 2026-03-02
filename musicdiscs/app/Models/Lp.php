<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lp extends Model
{
    protected $table = 'lp';

    protected $fillable = [
        'user_id',
        'album',
        'artist',
        'release_year',
        'price',
        'genre',
        'status',
        'in_stock',
        'cover_image',
        'number_of_tracks',
        'sold',
    ];

    /**
     * Get the seller (user) who created this LP
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
