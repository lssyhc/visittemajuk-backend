<?php

declare(strict_types=1);

namespace App\Http\Requests\PhotoSpot;

use Illuminate\Foundation\Http\FormRequest;

final class SavePhotoSpotRequest extends FormRequest
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
            'description' => ['required', 'string'],
            'full_description' => ['required', 'string'],
            'image' => $image,
            'category' => ['required', 'string', 'max:255'],
            'bestHour' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string'],
            'location_map' => ['nullable', 'string', 'max:512'],
            'tips' => ['sometimes', 'array'],
            'tips.*' => ['required', 'string'],
            'nearestAttraction' => ['present', 'array'],
            'nearestAttraction.*' => ['required', 'string'],
        ];
    }

    /**
     * @return array{
     *     title: string,
     *     description: string,
     *     full_description: string,
     *     image: string,
     *     category: string,
     *     bestHour: string,
     *     location: string,
     *     location_map: string|null,
     *     tips: list<string>,
     *     nearestAttraction: list<string>
     * }
     */
    public function photoSpotAttributes(): array
    {
        $validated = $this->validated();

        return [
            'title' => (string) $validated['title'],
            'description' => (string) $validated['description'],
            'full_description' => (string) $validated['full_description'],
            'image' => (string) $validated['image'],
            'category' => (string) $validated['category'],
            'bestHour' => (string) $validated['bestHour'],
            'location' => (string) $validated['location'],
            'location_map' => isset($validated['location_map']) ? (string) $validated['location_map'] : null,
            'tips' => isset($validated['tips']) ? array_values($validated['tips']) : [],
            'nearestAttraction' => array_values($validated['nearestAttraction']),
        ];
    }
}
