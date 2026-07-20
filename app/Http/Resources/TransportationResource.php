<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Transportation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

class TransportationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $transportation = $this->resource;

        if (! $transportation instanceof Transportation) {
            throw new LogicException('Resource transportasi tidak valid.');
        }

        return [
            'id' => $transportation->id,
            'title' => $transportation->title,
            'description' => $transportation->description,
            'image' => $transportation->image,
            'estimated_time' => $transportation->estimated_time,
            'estimated_cost' => $transportation->estimated_cost,
            'difficulty' => $transportation->difficulty,
            'transportation_steps' => $transportation->steps,
            'transportation_tips' => $transportation->tips,
        ];
    }
}
