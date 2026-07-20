<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\AdditionalCulinary;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

class AdditionalCulinaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $additionalCulinary = $this->resource;
        if (! $additionalCulinary instanceof AdditionalCulinary) {
            throw new LogicException('Resource kuliner khas tidak valid.');
        }

        return [
            'id' => $additionalCulinary->id,
            'title' => $additionalCulinary->title,
            'description' => $additionalCulinary->description,
            'image' => $additionalCulinary->image,
        ];
    }
}
