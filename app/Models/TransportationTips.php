<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TransportationTips extends Model
{
    protected $table = 'transportationtips';

    protected $fillable = [
        'tip',
        'transportation_id',
    ];

    public function transportation(): BelongsTo
    {
        return $this->belongsTo(Transportation::class);
    }
}
