<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Destination extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'slug',
        'title',
        'description',
        'full_description',
        'image',
        'category',
        'price',
        'location',
        'location_map',
        'open_hours',
        'facilities',
        'activities',
        'tips',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'facilities' => 'array',
            'activities' => 'array',
            'tips' => 'array',
        ];
    }

    /**
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * @return HasMany<DestinationGallery, $this>
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(DestinationGallery::class)->orderBy('sort_order')->orderBy('id');
    }
}
