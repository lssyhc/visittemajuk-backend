<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CulinaryGalleries extends Model
{
    protected $table = 'culinarygalleries';

    protected $fillable = [
        'image',
        'culinary_id',
    ];

    public function culinary(): BelongsTo
    {
        return $this->belongsTo(Culinary::class);
    }
}
