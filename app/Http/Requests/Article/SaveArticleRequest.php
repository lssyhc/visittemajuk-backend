<?php

declare(strict_types=1);

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

final class SaveArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $image = $this->hasFile('image') ? 'required|image|mimes:jpg,jpeg,webp|max:1024' : 'required|string|max:255';

        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => $image,
            'user_id' => ['required', 'integer'],
        ];
    }

    /**
     * @return array{
     *     title: string,
     *     content: string,
     *     image: string,
     *     user_id: int,
     * }
     */
    public function articleAttributes(): array
    {
        $validated = $this->validated();

        return [
            'title' => (string) $validated['title'],
            'content' => (string) $validated['content'],
            'image' => (string) $validated['image'],
            'user_id' => (int) $validated['user_id'],
        ];
    }
}
