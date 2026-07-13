<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Accomodation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class AccomodationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $accomodation = $this->resource;

        if (! $accomodation instanceof Accomodation) {
            throw new LogicException('Resource akomodasi tidak valid.');
        }

        return [
            'id' => $accomodation->slug,
            'title' => $accomodation->title,
            'description' => $accomodation->description,
            'fullDescription' => $accomodation->full_description,
            'imageUrl' => $accomodation->image_url,
            'category' => $accomodation->category,
            'minPrice' => $accomodation->min_price,
            'maxPrice' => $accomodation->max_price,
            'location' => $accomodation->location,
            'contacs' => $accomodation->contacs,
            'siteUrl' => $accomodation->site_url,
            'facilities' => $accomodation->facilities,
            'gallery' => $accomodation->gallery,
            'roomTypes' => RoomTypeResource::collection($accomodation->roomTypes),
        ];
    }
}
