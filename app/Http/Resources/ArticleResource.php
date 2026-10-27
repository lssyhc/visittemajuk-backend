<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;

final class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $article = $this->resource;

        if (! $article instanceof Article) {
            throw new LogicException('Resource artikel tidak valid.');
        }

        return [
            'id' => $article->id,
            'title' => $article->title,
            'image' => $article->image,
            'content' => $article->content,
            'user_id' => $article->user_id,
            'date' => $article->created_at,
            'slug' => $article->slug,
            'user' => $article->user->username,
        ];
    }
}
