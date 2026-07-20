<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class AccomodationGalleries extends Model
{
    protected $table = 'accomodationgalleries';

    protected $fillable = [
        'image',
        'accomodation_id',
    ];

    public function accomodation(): BelongsTo
    {
        return $this->belongsTo(Accomodation::class);
    }
}
