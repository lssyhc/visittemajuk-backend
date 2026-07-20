<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalCulinary extends Model
{
    protected $table = 'additionalculinaries';

    protected $fillable = [
        'title',
        'description',
        'image',
    ];
}
