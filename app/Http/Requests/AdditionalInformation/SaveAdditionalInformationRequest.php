<?php

declare(strict_types=1);

namespace App\Http\Requests\AdditionalInformation;

use Illuminate\Foundation\Http\FormRequest;

final class SaveAdditionalInformationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string'],
            'description' => ['required', 'string'],
        ];
    }

    /**
     * @return array{
     *     title : string,
     *     type: string,
     *     description: string
     * }
     */
    public function additionalInformationAttributes(): array
    {
        $validated = $this->validated();

        return [
            'title' => (string) $validated['title'],
            'type' => (string) $validated['type'],
            'description' => (string) $validated['description'],
        ];
    }
}
