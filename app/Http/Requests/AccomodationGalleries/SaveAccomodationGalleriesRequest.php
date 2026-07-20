<?php

declare(strict_types=1);

namespace App\Http\Requests\AccomodationGalleries;

use App\Models\Accomodation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

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
            'accomodation_id' => ['required'],
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
        $accomodationReference = $validated['accomodation_id'];

        $accomodationId = is_numeric($accomodationReference)
            ? (int) $accomodationReference
            : Accomodation::query()->where('slug', (string) $accomodationReference)->value('id');

        if ($accomodationId === null) {
            throw ValidationException::withMessages([
                'accomodation_id' => ['Akomodasi tidak ditemukan.'],
            ]);
        }

        return [
            'image' => (string) $validated['image'],
            'accomodation_id' => (int) $accomodationId,
        ];
    }
}
