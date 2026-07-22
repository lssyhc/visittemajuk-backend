<?php

declare(strict_types=1);

namespace App\Http\Requests\Footer;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateFooterSocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform' => ['sometimes', 'required', 'string', 'in:instagram,facebook,twitter,tiktok,youtube,threads,other'],
            'label' => ['sometimes', 'nullable', 'string', 'max:64'],
            'url' => ['sometimes', 'required', 'url', 'max:2048'],
            'order' => ['sometimes', 'nullable', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'platform.in' => 'Platform harus salah satu dari: instagram, facebook, twitter, tiktok, youtube, threads, other.',
            'url.url' => 'URL harus berupa URL yang valid.',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function socialAttributes(): array
    {
        return $this->validated();
    }
}
