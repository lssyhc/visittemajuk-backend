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
            'menu' => ['required', 'string', 'max:255'],
            'order' => ['required', 'integer'],
            'culinary_id' => ['required', 'integer'],
        ];
    }

    /**
     * @return array{
     *     menu: string,
     *     order: int,
     *     culinary_id: int,
     *
     * }
     */
    public function specialtyAttributes(): array
    {
        $validated = $this->validated();

        return [
            'menu' => (string) $validated['menu'],
            'order' => (int) $validated['order'],
            'culinary_id' => (int) $validated['culinary_id'],
        ];
    }
}
