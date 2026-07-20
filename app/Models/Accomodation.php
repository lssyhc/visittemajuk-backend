<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Accomodation extends Model
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
        'min_price',
        'max_price',
        'location',
        'location_map',
        'contacs',
        'site_url',
        'facilities',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'facilities' => 'array',
        ];
    }

    /**
     * Get the room types for this accommodation.
     *
     * @return HasMany<RoomType, $this>
     */
    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomType::class, 'accommodation_id');
    }

    public function accomodationGalleries(): HasMany
    {
        return $this->hasMany(AccomodationGalleries::class);
    }
}
