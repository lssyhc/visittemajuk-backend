<?php

declare(strict_types=1);

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateSiteSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'home' => ['sometimes', 'array'],
            'home.hero' => ['sometimes', 'array'],
            'home.hero.title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'home.hero.subtitle' => ['sometimes', 'nullable', 'string', 'max:255'],
            'home.hero.image' => ['sometimes', 'file', 'image', 'mimes:jpg,jpeg,webp', 'max:1024'],
            'home.hero.button_text' => ['sometimes', 'nullable', 'string', 'max:255'],
            'home.hero.button_link' => ['sometimes', 'nullable', 'string', 'max:2048', 'regex:/^(\/|#)/'],
            'home.intro' => ['sometimes', 'array'],
            'home.intro.title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'home.intro.body' => ['sometimes', 'nullable', 'string'],
            'home.intro.image' => ['sometimes', 'file', 'image', 'mimes:jpg,jpeg,webp', 'max:1024'],
            'home.section_titles' => ['sometimes', 'array'],
            'home.section_titles.*' => ['sometimes', 'nullable', 'string', 'max:255'],
            'home.features' => ['sometimes', 'array'],
            'home.features.*.title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'home.features.*.body' => ['sometimes', 'nullable', 'string'],
            'home.features.*.icon' => ['sometimes', 'nullable', 'string', 'max:64'],
            'home.transport_cta' => ['sometimes', 'array'],
            'home.transport_cta.title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'home.transport_cta.body' => ['sometimes', 'nullable', 'string'],
            'home.transport_cta.button_text' => ['sometimes', 'nullable', 'string', 'max:255'],
            'home.transport_cta.button_link' => ['sometimes', 'nullable', 'string', 'max:2048', 'regex:/^(\/|#)/'],
            'home.transport_cta.image' => ['sometimes', 'file', 'image', 'mimes:jpg,jpeg,webp', 'max:1024'],
            'footer' => ['sometimes', 'array'],
            'footer.brand' => ['sometimes', 'array'],
            'footer.brand.tagline' => ['sometimes', 'nullable', 'string', 'max:512'],
            'footer.brand.brand_text' => ['sometimes', 'nullable', 'string', 'max:255'],
            'footer.contact' => ['sometimes', 'array'],
            'footer.contact.address' => ['sometimes', 'nullable', 'string', 'max:512'],
            'footer.contact.phone' => ['sometimes', 'nullable', 'string', 'max:64'],
            'footer.contact.email' => ['sometimes', 'nullable', 'email:rfc', 'max:255'],
        ];
    }
}
