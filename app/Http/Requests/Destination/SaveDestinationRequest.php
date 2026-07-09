<?php

declare(strict_types=1);

namespace App\Http\Requests\Destination;

use Illuminate\Foundation\Http\FormRequest;

final class SaveDestinationRequest extends FormRequest
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
            'fullDescription' => ['required', 'string'],
            'imageUrl' => ['required', 'string', 'url', 'max:2048'],
            'category' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string'],
            'openHours' => ['required', 'string', 'max:255'],
            'facilities' => ['present', 'array'],
            'facilities.*' => ['required', 'string', 'max:255'],
            'activities' => ['present', 'array'],
            'activities.*' => ['required', 'string', 'max:255'],
            'tips' => ['present', 'array'],
            'tips.*' => ['required', 'string'],
            'gallery' => ['present', 'array'],
            'gallery.*' => ['required', 'string', 'url', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'imageUrl.url' => 'URL gambar utama harus berupa URL yang valid.',
            'gallery.*.url' => 'URL galeri harus berupa URL yang valid.',
        ];
    }

    /**
     * @return array{
     *     title: string,
     *     description: string,
     *     full_description: string,
     *     image_url: string,
     *     category: string,
     *     price: string,
     *     location: string,
     *     open_hours: string,
     *     facilities: list<string>,
     *     activities: list<string>,
     *     tips: list<string>,
     *     gallery: list<string>
     * }
     */
    public function destinationAttributes(): array
    {
        $validated = $this->validated();

        return [
            'title' => (string) $validated['title'],
            'description' => (string) $validated['description'],
            'full_description' => (string) $validated['fullDescription'],
            'image_url' => (string) $validated['imageUrl'],
            'category' => (string) $validated['category'],
            'price' => (string) $validated['price'],
            'location' => (string) $validated['location'],
            'open_hours' => (string) $validated['openHours'],
            'facilities' => array_values($validated['facilities']),
            'activities' => array_values($validated['activities']),
            'tips' => array_values($validated['tips']),
            'gallery' => array_values($validated['gallery']),
        ];
    }
}
