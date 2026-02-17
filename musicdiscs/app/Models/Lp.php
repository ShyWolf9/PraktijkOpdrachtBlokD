<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lp extends Model
{
    protected $table = 'lp';

    protected $fillable = [
        'album',
        'artist',
        'release_year',
        'price',
        'genre',
        'status',
        'in_stock',
        'cover_image',
        'number_of_tracks',
    ];
}
