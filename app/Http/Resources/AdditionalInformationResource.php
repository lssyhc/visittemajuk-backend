<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\AdditionalInformation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

class AdditionalInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $additionalInformation = $this->resource;

        if (! $additionalInformation instanceof AdditionalInformation) {
            throw new LogicException('Resource informasi tambahan tidak valid.');
        }

        return [
            'id' => $additionalInformation->id,
            'title' => $additionalInformation->title,
            'type' => $additionalInformation->type,
            'description' => $additionalInformation->description,
        ];
    }
}
