<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuessNumberScore extends Model
{
    protected $fillable = [
        'user_id',
        'difficulty',
        'attempts',
        'target',
        'reward',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
