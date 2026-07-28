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
            'home.hero.title' => ['sometimes', 'string', 'max:255'],
            'home.hero.subtitle' => ['sometimes', 'string', 'max:255'],
            'home.hero.image' => ['sometimes', 'file', 'image', 'mimes:jpg,jpeg,webp', 'max:1024'],
            'home.hero.button_text' => ['sometimes', 'string', 'max:255'],
            'home.hero.button_link' => ['sometimes', 'string', 'max:2048'],
            'home.intro' => ['sometimes', 'array'],
            'home.intro.title' => ['sometimes', 'string', 'max:255'],
            'home.intro.body' => ['sometimes', 'string'],
            'home.intro.image' => ['sometimes', 'file', 'image', 'mimes:jpg,jpeg,webp', 'max:1024'],
            'home.section_titles' => ['sometimes', 'array'],
            'home.section_titles.*' => ['sometimes', 'string', 'max:255'],
            'home.features' => ['sometimes', 'array'],
            'home.features.*.title' => ['sometimes', 'string', 'max:255'],
            'home.features.*.body' => ['sometimes', 'string'],
            'home.features.*.icon' => ['sometimes', 'string', 'max:64'],
            'home.transport_cta' => ['sometimes', 'array'],
            'home.transport_cta.title' => ['sometimes', 'string', 'max:255'],
            'home.transport_cta.body' => ['sometimes', 'string'],
            'home.transport_cta.button_text' => ['sometimes', 'string', 'max:255'],
            'home.transport_cta.button_link' => ['sometimes', 'string', 'max:2048'],
            'home.transport_cta.image' => ['sometimes', 'file', 'image', 'mimes:jpg,jpeg,webp', 'max:1024'],
            'footer' => ['sometimes', 'array'],
            'footer.brand' => ['sometimes', 'array'],
            'footer.brand.tagline' => ['sometimes', 'string', 'max:512'],
            'footer.brand.brand_text' => ['sometimes', 'string', 'max:255'],
            'footer.contact' => ['sometimes', 'array'],
            'footer.contact.address' => ['sometimes', 'string', 'max:512'],
            'footer.contact.phone' => ['sometimes', 'string', 'max:64'],
            'footer.contact.email' => ['sometimes', 'email:rfc', 'max:255'],
        ];
    }
}
