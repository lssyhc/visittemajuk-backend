<?php

declare(strict_types=1);

namespace App\Http\Requests\CulinaryGalleries;

use Illuminate\Foundation\Http\FormRequest;

final class SaveCulinaryGalleriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $image = $this->hasFile('image') ? 'required|image|mimes:jpg,jpeg,webp|max:1024' : 'required|string|max:255';

        return [
            'image' => $image,
            'order' => ['required', 'integer'],
            'culinary_id' => ['required', 'integer', 'exists:culinaries,id'],
        ];
    }

    /**
     * @return array{
     *     image: string,
     *     order: int,
     *     culinary_id: int
     * }
     */
    public function culinaryGalleriesAttributes(): array
    {
        $validated = $this->validated();

        return [
            'image' => (string) $validated['image'],
            'order' => (int) $validated['order'],
            'culinary_id' => (int) $validated['culinary_id'],
        ];
    }
}
