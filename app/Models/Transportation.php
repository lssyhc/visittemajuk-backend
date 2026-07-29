<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Transportation extends Model
{
    protected $table = 'transportations';

    protected $fillable = [
        'title',
        'description',
        'image',
        'estimated_time',
        'estimated_cost',
        'difficulty',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(TransportationSteps::class)->orderBy('order', 'asc');
    }

    public function tips(): HasMany
    {
        return $this->hasMany(TransportationTips::class)->orderBy('order', 'asc');
    }
}
