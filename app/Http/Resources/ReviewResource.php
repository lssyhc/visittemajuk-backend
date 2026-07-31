<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Destination;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use LogicException;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $text
 * @property-read int $rating
 * @property-read Carbon|null $created_at
 * @property-read Carbon|null $updated_at
 * @property-read Destination|null $destination
 */
final class ReviewResource extends JsonResource
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

        /** @var Destination|null $destination */
        $destination = $review->destination;

        return [
            'id' => $review->id,
            'name' => $review->name,
            'text' => $review->text,
            'destination' => $destination ? [
                'id' => $destination->slug,
                'title' => $destination->title,
            ] : null,
            'rating' => $review->rating,
            'created_at' => $review->created_at,
            'updated_at' => $review->updated_at,
        ];
    }
}
