<?php

declare(strict_types=1);

namespace App\Http\Requests\Destination;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

final class CreateDestinationRequest extends FormRequest
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
            'image' => ['required', 'image', 'mimes:jpg,jpeg,webp', 'max:1024'],
            'category' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string'],
            'locationMap' => ['nullable', 'string', 'max:512'],
            'openHours' => ['required', 'string', 'max:255'],
            'facilities' => ['present', 'array'],
            'facilities.*' => ['required', 'string', 'max:255'],
            'activities' => ['present', 'array'],
            'activities.*' => ['required', 'string', 'max:255'],
            'tips' => ['present', 'array'],
            'tips.*' => ['required', 'string', 'max:512'],
            'gallery' => ['sometimes', 'array'],
            'gallery.*' => ['image', 'mimes:jpg,jpeg,webp', 'max:1024'],
        ];
    }

    /**
     * @return array{
     *     title: string,
     *     description: string,
     *     full_description: string,
     *     category: string,
     *     price: string,
     *     location: string,
     *     location_map: string|null,
     *     open_hours: string,
     *     facilities: list<string>,
     *     activities: list<string>,
     *     tips: list<string>
     * }
     */
    public function destinationAttributes(): array
    {
        $validated = $this->validated();

        return [
            'title' => (string) $validated['title'],
            'description' => (string) $validated['description'],
            'full_description' => (string) $validated['fullDescription'],
            'category' => (string) $validated['category'],
            'price' => (string) $validated['price'],
            'location' => (string) $validated['location'],
            'location_map' => isset($validated['locationMap']) ? (string) $validated['locationMap'] : null,
            'open_hours' => (string) $validated['openHours'],
            'facilities' => array_values($validated['facilities']),
            'activities' => array_values($validated['activities']),
            'tips' => array_values($validated['tips']),
        ];
    }

    /**
     * @return array<int, UploadedFile>
     */
    public function galleryFiles(): array
    {
        return $this->file('gallery') ?? [];
    }
}
