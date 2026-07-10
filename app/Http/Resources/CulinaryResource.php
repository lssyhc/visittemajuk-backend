<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Culinary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class CulinaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $culinary = $this->resource;

        if (! $culinary instanceof Culinary) {
            throw new LogicException('Resource kuliner tidak valid.');
        }

        return [
            'id' => $culinary->id,
            'title' => $culinary->title,
            'description' => $culinary->description,
            'full_description' => $culinary->full_description,
            'image' => $culinary->image,
            'category' => $culinary->category,
            'price' => $culinary->price,
            'location' => $culinary->location,
            'location_map' => $culinary->location_map,
            'open_hours' => $culinary->open_hours,
            'contact' => $culinary->contact,
            'specialties' => $culinary->specialties,
            'culinary_galleries' => $culinary->culinaryGalleries,
        ];
    }
}
