<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class PhotographyTip extends Model
{
    use HasFactory;

    protected $table = 'photography_tips';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'image',
        'order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }
}
