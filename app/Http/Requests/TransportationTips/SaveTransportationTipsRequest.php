<?php

declare(strict_types=1);

namespace App\Http\Requests\TransportationTips;

use Illuminate\Foundation\Http\FormRequest;

final class SaveTransportationTipsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tip' => ['required', 'string', 'max:255'],
            'order' => ['required', 'int'],
            'transportation_id' => ['required', 'integer', 'exists:transportations,id'],
        ];
    }

    /**
     * @return array{
     *     tip: string,
     *     order: int,
     *     transportation_id: int,
     * }
     */
    public function transportationTipsAttributes(): array
    {
        $validated = $this->validated();

        return [
            'tip' => (string) $validated['tip'],
            'order' => (int) $validated['order'],
            'transportation_id' => (int) $validated['transportation_id'],
        ];
    }
}
