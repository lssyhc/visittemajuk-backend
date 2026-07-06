<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    protected $table = 'destinations';

    protected $fillable = [
        'title',
        'description',
        'full_description',
        'image',
        'category',
        'price',
        'location',
        'open_hours',
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
