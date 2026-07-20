<?php

declare(strict_types=1);

namespace App\Http\Requests\PhotoSpot;

use Illuminate\Foundation\Http\FormRequest;

final class ListPhotoSpotRequest extends FormRequest
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
            'category' => ['nullable', 'string', 'max:255'],
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

    public function category(): ?string
    {
        $value = $this->validated('category');

        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
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
