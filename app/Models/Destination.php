<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'image_url',
        'category',
        'price',
        'location',
        'open_hours',
        'facilities',
        'activities',
        'tips',
        'gallery',
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
            'gallery' => 'array',
        ];
    }
}
