<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionalInformation extends Model
{
    protected $table = 'additionalinformations';

    protected $fillable = [
        'title',
        'description',
        'type',
    ];
}
