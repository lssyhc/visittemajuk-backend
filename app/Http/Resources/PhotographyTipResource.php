<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\PhotographyTip;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class PhotographyTipResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $tip = $this->resource;

        if (! $tip instanceof PhotographyTip) {
            throw new LogicException('Resource tips fotografi tidak valid.');
        }

        return [
            'id' => $tip->id,
            'title' => $tip->title,
            'description' => $tip->description,
            'image' => $tip->image,
            'order' => $tip->order,
        ];
    }
}
