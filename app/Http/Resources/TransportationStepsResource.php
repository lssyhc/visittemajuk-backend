<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\TransportationSteps;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class TransportationStepsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $transportationSteps = $this->resource;

        if (! $transportationSteps instanceof TransportationSteps) {
            throw new LogicException('Resource langkah transportasi tidak valid.');
        }

        return [
            'id' => $transportationSteps->id,
            'description' => $transportationSteps->description,
            'duration' => $transportationSteps->duration,
            'cost' => $transportationSteps->cost,
            'transportation_id' => $transportationSteps->transportation_id,
            'vehicle' => $transportationSteps->vehicle,
        ];
    }
}
