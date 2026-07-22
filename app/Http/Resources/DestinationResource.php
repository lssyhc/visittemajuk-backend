<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class DestinationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $destination = $this->resource;

        if (! $destination instanceof Destination) {
            throw new LogicException('Resource destinasi tidak valid.');
        }

        return [
            'id' => $destination->slug,
            'title' => $destination->title,
            'description' => $destination->description,
            'fullDescription' => $destination->full_description,
            'image' => $destination->image,
            'category' => $destination->category,
            'price' => $destination->price,
            'location' => $destination->location,
            'locationMap' => $destination->location_map,
            'openHours' => $destination->open_hours,
            'facilities' => $destination->facilities,
            'activities' => $destination->activities,
            'tips' => $destination->tips,
            'galleries' => $destination->relationLoaded('galleries')
                ? $destination->galleries->map(fn ($g): array => [
                    'id' => $g->id,
                    'image' => $g->image,
                    'sort_order' => $g->sort_order,
                ])->all()
                : [],
        ];
    }
}
