<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\AccomodationGalleries;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class AccomodationGalleriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $accomodationGalleries = $this->resource;

        if (! $accomodationGalleries instanceof AccomodationGalleries) {
            throw new LogicException('Resource galeri akomodasi tidak valid.');
        }

        return [
            'id' => $accomodationGalleries->id,
            'image' => $accomodationGalleries->image,
            'accomodation_id' => $accomodationGalleries->accomodation_id,
        ];
    }
}
