<?php

declare(strict_types=1);

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

final class SaveReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'text' => ['required', 'string'],
            'destination_id' => ['required', 'integer'],
            'rating' => ['required', 'integer'],
        ];
    }

    /**
     * @return array{
     *     name: string,
     *     text: string,
     *     destination_id: int,
     *     rating: int,
     * }
     */
    public function reviewAttributes(): array
    {
        $validated = $this->validated();

        return [
            'name' => (string) $validated['name'],
            'text' => (string) $validated['text'],
            'destination_id' => (int) $validated['destination_id'],
            'rating' => (int) $validated['rating'],
        ];
    }
}
