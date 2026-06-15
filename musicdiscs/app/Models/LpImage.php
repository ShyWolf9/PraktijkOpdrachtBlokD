<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpImage extends Model
{
    protected $table = 'lp_images';

    protected $fillable = [
        'lp_id',
        'path',
    ];

    public function lp()
    {
        return $this->belongsTo(Lp::class, 'lp_id');
    }
}
