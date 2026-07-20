<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\PhotoSpot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class PhotoSpotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $photoSpot = $this->resource;

        if (! $photoSpot instanceof PhotoSpot) {
            throw new LogicException('Resource foto spot tidak valid.');
        }

        return [
            'id' => $photoSpot->id,
            'title' => $photoSpot->title,
            'description' => $photoSpot->description,
            'full_description' => $photoSpot->full_description,
            'image' => $photoSpot->image,
            'category' => $photoSpot->category,
            'bestHour' => $photoSpot->bestHour,
            'location' => $photoSpot->location,
            'location_map' => $photoSpot->location_map,
            'tips' => $photoSpot->tips,
            'nearestAttraction' => $photoSpot->nearestAttraction,
            'slug' => $photoSpot->slug,
            'photo_spot_galleries' => $photoSpot->photoSpotGalleries,
        ];
    }
}
