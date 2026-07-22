<?php

declare(strict_types=1);

namespace App\Http\Requests\PhotographyTip;

use Illuminate\Foundation\Http\FormRequest;

final class CreatePhotographyTipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,webp', 'max:1024'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array{
     *     title: string,
     *     description: string,
     *     order: int
     * }
     */
    public function tipAttributes(): array
    {
        $validated = $this->validated();

        return [
            'title' => (string) $validated['title'],
            'description' => (string) $validated['description'],
            'order' => isset($validated['order']) ? (int) $validated['order'] : 0,
        ];
    }
}
