<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\PhotoSpotGalleries;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class PhotoSpotGalleriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $photoSpotGalleries = $this->resource;

        if (! $photoSpotGalleries instanceof PhotoSpotGalleries) {
            throw new LogicException('Resource galeri foto spot tidak valid.');
        }

        return [
            'id' => $photoSpotGalleries->id,
            'image' => $photoSpotGalleries->image,
            'photo_spot_id' => $photoSpotGalleries->photo_spot_id,
        ];
    }
}
