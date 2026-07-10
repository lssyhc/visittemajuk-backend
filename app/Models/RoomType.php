<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RoomType extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'accommodation_id',
        'name',
        'description',
        'capacity',
        'price',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    /**
     * Get the accommodation that owns this room type.
     *
     * @return BelongsTo<Accomodation, $this>
     */
    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accomodation::class, 'accommodation_id');
    }
}
