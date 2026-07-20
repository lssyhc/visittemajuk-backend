<?php

declare(strict_types=1);

namespace App\Http\Requests\TransportationSteps;

use Illuminate\Foundation\Http\FormRequest;

final class SaveTransportationStepsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'description' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'string', 'max:255'],
            'cost' => ['required', 'string', 'max:255'],
            'transportation_id' => ['required', 'integer'],
            'vehicle' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array{
     *     description: string,
     *     time: string,
     *     cost: string,
     *     transportation_id: int,
     *     vehicle: string,
     * }
     */
    public function transportationStepsAttributes(): array
    {
        $validated = $this->validated();

        return [
            'description' => (string) $validated['description'],
            'duration' => (string) $validated['duration'],
            'cost' => (string) $validated['cost'],
            'transportation_id' => (int) $validated['transportation_id'],
            'vehicle' => (string) $validated['vehicle'],
        ];
    }
}
