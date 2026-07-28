<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\FooterSocial;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class FooterSocialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $social = $this->resource;

        if (! $social instanceof FooterSocial) {
            throw new LogicException('Resource footer social tidak valid.');
        }

        return [
            'id' => $social->id,
            'platform' => $social->platform,
            'label' => $social->label,
            'url' => $social->url,
            'order' => $social->order,
        ];
    }
}
