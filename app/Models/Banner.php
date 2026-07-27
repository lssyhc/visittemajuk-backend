<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Banner extends Model
{
    protected $table = 'banners';

    protected $fillable = [
        'title',
        'description',
        'menu',
        'image',
    ];
}
