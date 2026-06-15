<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class Lp extends Model
{
    protected $table = 'lp';

    protected $fillable = [
        'user_id',
        'album',
        'artist',
        'release_year',
        'price',
        'sale_price',
        'genre',
        'status',
        'in_stock',
        'cover_image',
        'number_of_tracks',
        'sold',
        'clicks',
    ];

    /**
     * Get the seller (user) who created this LP
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Images associated with this LP
     */
    public function images()
    {
        return $this->hasMany(LpImage::class, 'lp_id');
    }

    /**
     * Return first image path or null
     */
    public function getFirstImageAttribute()
    {
        // Prefer the new lp_images entries when available and ensure file exists in storage
        $first = $this->images()->first();
        if ($first) {
            if (Storage::disk('public')->exists($first->path)) {
                return $first->path;
            }
            // if lp_images record exists but file missing, ignore and continue to fallback
        }

        // Fallback to legacy `cover_image` column if present
        if (!empty($this->cover_image)) {
            $legacy = $this->cover_image;

            // If legacy value already looks like a storage path (e.g., 'lps/xxx.jpg') and exists, use it
            if (Storage::disk('public')->exists($legacy)) {
                return $legacy;
            }

            // If legacy value is an absolute path on disk and file exists, copy into storage and create lp_images record
            if (file_exists($legacy) && is_file($legacy)) {
                try {
                    $filename = basename($legacy);
                    $targetDir = 'lps';
                    $targetPath = $targetDir . '/' . $filename;

                    // avoid overwriting if already present
                    if (!Storage::disk('public')->exists($targetPath)) {
                        Storage::disk('public')->putFileAs($targetDir, new File($legacy), $filename);
                    }

                    // persist record so future calls don't need to copy again
                    $this->images()->create(['path' => $targetPath]);

                    return $targetPath;
                } catch (\Exception $e) {
                    // ignore and fall through to null
                }
            }
        }

        return null;
    }
}
