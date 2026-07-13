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
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'full_description' => ['required', 'string'],
            'image' => ['required'],
            'category' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string'],
            'location_map' => ['required', 'string'],
            'open_hours' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string'],
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
