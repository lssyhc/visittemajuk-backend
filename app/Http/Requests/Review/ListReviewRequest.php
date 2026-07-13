<?php

declare(strict_types=1);

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

final class ListReviewRequest extends FormRequest
{
    private const int DEFAULT_PER_PAGE = 9;

    private const int MAX_PER_PAGE = 50;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'integer', 'max:255'],
            'rate' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:'.self::MAX_PER_PAGE],
        ];
    }

    public function search(): ?string
    {
        $value = $this->validated('search');

        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }

    public function destination(): int
    {
        $value = $this->validated('destination');

        if (! is_numeric($value)) {
            return 0;
        }

        return (int) $value;
    }

    public function rate(): int
    {
        $value = $this->validated('rate');

        if (! is_numeric($value)) {
            return 0;
        }

        return (int) $value;
    }

    public function perPage(): int
    {
        $value = $this->validated('per_page');

        if (! is_numeric($value)) {
            return self::DEFAULT_PER_PAGE;
        }

        return min((int) $value, self::MAX_PER_PAGE);
    }
}
