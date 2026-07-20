<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class PhotoSpotGalleries extends Model
{
    protected $table = 'photospotgalleries';

    protected $fillable = [
        'image',
        'photo_spot_id',
    ];

    public function photoSpot(): BelongsTo
    {
        return $this->belongsTo(PhotoSpot::class);
    }
}
