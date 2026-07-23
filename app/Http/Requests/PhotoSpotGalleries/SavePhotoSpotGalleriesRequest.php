<?php

declare(strict_types=1);

namespace App\Http\Requests\PhotoSpotGalleries;

use Illuminate\Foundation\Http\FormRequest;

final class SavePhotoSpotGalleriesRequest extends FormRequest
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
            'photo_spot_id' => ['required', 'integer', 'exists:photo_spots,id'],
        ];
    }

    /**
     * @return array{
     *     image: string,
     *     photo_spot_id: int
     * }
     */
    public function photoSpotGalleriesAttributes(): array
    {
        $validated = $this->validated();

        return [
            'image' => (string) $validated['image'],
            'photo_spot_id' => (int) $validated['photo_spot_id'],
        ];
    }
}
