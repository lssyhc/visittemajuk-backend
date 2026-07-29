<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Specialty extends Model
{
    protected $table = 'specialties';

    protected $fillable = [
        'menu',
        'order',
        'culinary_id',
    ];

    public function culinary(): BelongsTo
    {
        return $this->belongsTo(Culinary::class);
    }
}
