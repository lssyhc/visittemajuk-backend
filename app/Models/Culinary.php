<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Culinary extends Model
{
    protected $table = 'culinaries';

    protected $fillable = [
        'title',
        'description',
        'full_description',
        'image',
        'category',
        'price',
        'location',
        'location_map',
        'open_hours',
        'contact',
        'slug',
    ];

    public function specialties(): HasMany
    {
        return $this->hasMany(Specialty::class);
    }

    public function culinaryGalleries(): HasMany
    {
        return $this->hasMany(CulinaryGalleries::class);
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
