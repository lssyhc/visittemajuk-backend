<?php

declare(strict_types=1);

namespace App\Http\Requests\Review;

use App\Models\Destination;
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
            'name' => ['required', 'string', 'max:255'],
            'text' => ['required', 'string', 'max:512'],
            'destination_slug' => ['required', 'string', 'exists:destinations,slug'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ];
    }

    public function messages(): array
    {
        return [
            'text.max' => 'Ulasan tidak boleh lebih dari :max karakter.',
            'destination_slug.exists' => 'Destinasi yang dipilih tidak ditemukan.',
            'rating.min' => 'Rating minimal :min.',
            'rating.max' => 'Rating maksimal :max.',
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

        $destinationId = (int) Destination::query()
            ->where('slug', (string) $validated['destination_slug'])
            ->value('id');

        return [
            'name' => (string) $validated['name'],
            'text' => (string) $validated['text'],
            'destination_id' => $destinationId,
            'rating' => (int) $validated['rating'],
        ];
    }
}
