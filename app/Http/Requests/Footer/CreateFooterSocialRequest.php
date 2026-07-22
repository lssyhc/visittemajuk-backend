<?php

declare(strict_types=1);

namespace App\Http\Requests\Footer;

use Illuminate\Foundation\Http\FormRequest;

final class CreateFooterSocialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'platform' => ['required', 'string', 'in:instagram,facebook,twitter,tiktok,youtube,threads,other'],
            'label' => ['nullable', 'string', 'max:64'],
            'url' => ['required', 'url', 'max:2048'],
            'order' => ['nullable', 'integer', 'min:0'],
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
     * @return array{
     *     platform: string,
     *     label: string|null,
     *     url: string,
     *     order: int
     * }
     */
    public function socialAttributes(): array
    {
        $validated = $this->validated();

        return [
            'platform' => (string) $validated['platform'],
            'label' => isset($validated['label']) ? (string) $validated['label'] : null,
            'url' => (string) $validated['url'],
            'order' => isset($validated['order']) ? (int) $validated['order'] : 0,
        ];
    }
}
