<?php

declare(strict_types=1);

namespace App\Http\Requests\AdditionalCulinary;

use Illuminate\Foundation\Http\FormRequest;

final class SaveAdditionalCulinaryRequest extends FormRequest
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
            'image' => $image,
        ];
    }

    /**
     * @return array{
     *     title : string,
     *     description: string,
     *     image: string
     * }
     */
    public function additionalCulinaryAttributes(): array
    {
        $validated = $this->validated();

        return [
            'title' => (string) $validated['title'],
            'description' => (string) $validated['description'],
            'image' => (string) $validated['image'],
        ];
    }
}
