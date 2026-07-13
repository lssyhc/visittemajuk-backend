<?php

declare(strict_types=1);

namespace App\Http\Requests\Specialty;

use Illuminate\Foundation\Http\FormRequest;

final class SaveSpecialtyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'menu' => ['required', 'string'],
            'culinary_id' => ['required', 'integer'],
        ];
    }

    /**
     * @return array{
     *     menu: string,
     *     culinary_id: int,
     *
     * }
     */
    public function specialtyAttributes(): array
    {
        $validated = $this->validated();

        return [
            'menu' => (string) $validated['menu'],
            'culinary_id' => (int) $validated['culinary_id'],
        ];
    }
}
