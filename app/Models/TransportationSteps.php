<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TransportationSteps extends Model
{
    protected $table = 'transportationsteps';

    protected $fillable = [
        'description',
        'order',
        'duration',
        'cost',
        'transportation_id',
        'vehicle',
    ];

    public function transportation(): BelongsTo
    {
        return $this->belongsTo(Transportation::class);
    }
}
