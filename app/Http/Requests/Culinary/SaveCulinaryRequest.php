<?php

declare(strict_types=1);

namespace App\Http\Requests\Culinary;

use Illuminate\Foundation\Http\FormRequest;

final class SaveCulinaryRequest extends FormRequest
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
            'description' => ['required', 'string', 'max:255'],
            'full_description' => ['required', 'string', 'max:896'],
            'image' => $image,
            'category' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'location_map' => ['nullable', 'string', 'max:512'],
            'open_hours' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array{
     *     title: string,
     *     description: string,
     *     full_description: string,
     *     image: string,
     *     category: string,
     *     price: string,
     *     location: string,
     *     location_map: string,
     *     open_hours: string,
     *     contact: string
     * }
     */
    public function culinaryAttributes(): array
    {
        $validated = $this->validated();

        return [
            'title' => (string) $validated['title'],
            'description' => (string) $validated['description'],
            'full_description' => (string) $validated['full_description'],
            'image' => (string) $validated['image'],
            'category' => (string) $validated['category'],
            'price' => (string) $validated['price'],
            'location' => (string) $validated['location'],
            'location_map' => (string) $validated['location_map'],
            'open_hours' => (string) $validated['open_hours'],
            'contact' => (string) $validated['contact'],
        ];
    }
}
