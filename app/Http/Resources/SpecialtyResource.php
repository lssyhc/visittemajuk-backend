<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class SpecialtyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $specialty = $this->resource;

        if (! $specialty instanceof Specialty) {
            throw new LogicException('Resource spesialisasi tidak valid.');
        }

        return [
            'id' => $specialty->id,
            'menu' => $specialty->menu,
            'culinary_id' => $specialty->culinary_id,
        ];
    }
}
