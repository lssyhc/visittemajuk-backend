<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Specialty extends Model
{
    protected $table = 'Specialties';

    protected $fillable = [
        'menu',
        'culinary_id',
    ];

    public function culinary(): BelongsTo
    {
        return $this->belongsTo(Culinary::class);
    }
}
