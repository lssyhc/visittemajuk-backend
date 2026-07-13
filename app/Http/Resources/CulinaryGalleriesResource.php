<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\CulinaryGalleries;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class CulinaryGalleriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $culinaryGalleries = $this->resource;

        if (! $culinaryGalleries instanceof CulinaryGalleries) {
            throw new LogicException('Resource galeri kuliner tidak valid.');
        }

        return [
            'id' => $culinaryGalleries->id,
            'image' => $culinaryGalleries->image,
            'culinary_id' => $culinaryGalleries->culinary_id,
        ];
    }
}
