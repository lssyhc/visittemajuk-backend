<?php

declare(strict_types=1);

namespace App\Http\Requests\Banner;

use Illuminate\Foundation\Http\FormRequest;

final class SaveBannerRequest extends FormRequest
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
            'description' => ['required', 'string', 'max:255'],
            'menu' => ['required', 'string'],
            'image' => $image,
        ];
    }

    /**
     * @return array{
     *     title : string,
     *     description: string,
     *     menu: string,
     *     image: string
     * }
     */
    public function bannerAttributes(): array
    {
        $validated = $this->validated();

        return [
            'title' => (string) $validated['title'],
            'description' => (string) $validated['description'],
            'menu' => (string) $validated['menu'],
            'image' => (string) $validated['image'],
        ];
    }
}
