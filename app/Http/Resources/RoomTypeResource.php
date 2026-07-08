<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class RoomTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $roomType = $this->resource;

        if (! $roomType instanceof RoomType) {
            throw new LogicException('Resource tipe kamar tidak valid.');
        }

        return [
            'id'          => $roomType->id,
            'name'        => $roomType->name,
            'description' => $roomType->description,
            'capacity'    => $roomType->capacity,
            'price'       => $roomType->price,
        ];
    }
}
