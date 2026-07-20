<?php

declare(strict_types=1);

namespace App\Http\Requests\Transportation;

use Illuminate\Foundation\Http\FormRequest;

final class SaveTransportationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $image = $this->hasFile('image') ? 'required|image|mimes:jpg,jpeg,webp|max:1024' : 'required|string|max:255';

        return [
            'title' => ['required', 'string'],
            'description' => ['required', 'string', 'max:255'],
            'image' => $image,
            'estimated_time' => ['required', 'string', 'max:255'],
            'estimated_cost' => ['required', 'string', 'max:255'],
            'difficulty' => ['required', 'string'],
        ];
    }

    /**
     * @return array{
     *     title: string,
     *     description: string,
     *     image: string,
     *     estimated_time: string,
     *     estimated_cost: string,
     *     difficulty: string,
     * }
     */
    public function transportationAttributes(): array
    {
        $validated = $this->validated();

        return [
            'title' => (string) $validated['title'],
            'description' => (string) $validated['description'],
            'image' => (string) $validated['image'],
            'estimated_time' => (string) $validated['estimated_time'],
            'estimated_cost' => (string) $validated['estimated_cost'],
            'difficulty' => (string) $validated['difficulty'],
        ];
    }
}
