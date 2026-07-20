<?php

declare(strict_types=1);

namespace App\Http\Requests\AccomodationGalleries;

use Illuminate\Foundation\Http\FormRequest;

final class SaveAccomodationGalleriesRequest extends FormRequest
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
            'accomodation_id' => ['required', 'integer'],
        ];
    }

    /**
     * @return array{
     *     image: string,
     *     accomodation_id: int
     * }
     */
    public function accomodationGalleriesAttributes(): array
    {
        $validated = $this->validated();

        return [
            'image' => (string) $validated['image'],
            'accomodation_id' => (int) $validated['accomodation_id'],
        ];
    }
}
