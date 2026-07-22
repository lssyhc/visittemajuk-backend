<?php

declare(strict_types=1);

namespace App\Http\Requests\Destination;

use Illuminate\Foundation\Http\FormRequest;

final class UploadDestinationGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'image', 'mimes:jpg,jpeg,webp', 'max:1024'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
