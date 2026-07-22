<?php

declare(strict_types=1);

namespace App\Http\Requests\PhotographyTip;

use Illuminate\Foundation\Http\FormRequest;

final class UpdatePhotographyTipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'image' => ['sometimes', 'nullable', 'image', 'mimes:jpg,jpeg,webp', 'max:1024'],
            'order' => ['sometimes', 'nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function tipAttributes(): array
    {
        return $this->validated();
    }
}
