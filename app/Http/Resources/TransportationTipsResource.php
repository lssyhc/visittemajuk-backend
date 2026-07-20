<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\TransportationTips;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

class TransportationTipsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $transportationTips = $this->resource;

        if (! $transportationTips instanceof TransportationTips) {
            throw new LogicException('Resource tips transportasi tidak valid.');
        }

        return [
            'id' => $transportationTips->id,
            'tip' => $transportationTips->tip,
            'transportation_id' => $transportationTips->transportation_id,
        ];
    }
}
