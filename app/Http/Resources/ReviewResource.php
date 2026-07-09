<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $review = $this->resource;

        if (! $review instanceof Review) {
            throw new LogicException('Resource review tidak valid.');
        }

        return [
            'name' => $review->name,
            'text' => $review->text,
            'destination' => $review->destination,
            'rating' => $review->rating,
            'created_at' => $review->created_at,
            'updated_at' => $review->updated_at,
        ];
    }
}
