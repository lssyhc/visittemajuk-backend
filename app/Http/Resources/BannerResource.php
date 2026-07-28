<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class BannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $banner = $this->resource;

        if (! $banner instanceof Banner) {
            throw new LogicException('Resource banner tidak valid');
        }

        return [
            'id' => $banner->id,
            'title' => $banner->title,
            'description' => $banner->description,
            'menu' => $banner->menu,
            'image' => $banner->image,
        ];

    }
}
