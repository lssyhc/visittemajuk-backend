<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class PhotoSpot extends Model
{
    protected $table = 'photo_spots';

    protected $fillable = [
        'title',
        'description',
        'full_description',
        'image',
        'category',
        'bestHour',
        'location',
        'location_map',
        'tips',
        'nearestAttraction',
        'slug',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tips' => 'array',
            'nearestAttraction' => 'array',
        ];
    }

    public function photoSpotGalleries(): HasMany
    {
        return $this->hasMany(PhotoSpotGalleries::class);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (is_numeric($value)) {
            return $this->where('id', $value)->firstOrFail();
        }

        if (is_string($value)) {
            return $this->where('slug', $value)->firstOrFail();
        }

        return null;

    }
}
