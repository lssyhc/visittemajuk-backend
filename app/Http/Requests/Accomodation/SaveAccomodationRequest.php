<?php

declare(strict_types=1);

namespace App\Http\Requests\Accomodation;

use Illuminate\Foundation\Http\FormRequest;

final class SaveAccomodationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $image = $this->hasFile('image')
            ? 'required|image|mimes:jpg,jpeg,webp|max:1024'
            : 'required|string|max:255';

        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'fullDescription' => ['required', 'string'],
            'image' => $image,
            'category' => ['required', 'string', 'in:resort,wisma,bungalow,homestay,villa'],
            'minPrice' => ['required', 'numeric', 'min:0'],
            'maxPrice' => ['required', 'numeric', 'min:0'],
            'location' => ['required', 'string'],
            'location_map' => ['nullable', 'string', 'max:512'],
            'contacs' => ['required', 'string', 'max:255'],
            'siteUrl' => ['nullable', 'string', 'url', 'max:2048'],
            'facilities' => ['present', 'array'],
            'facilities.*' => ['required', 'string', 'max:255'],
            'roomTypes' => ['present', 'array'],
            'roomTypes.*.name' => ['required', 'string', 'max:255'],
            'roomTypes.*.description' => ['required', 'string'],
            'roomTypes.*.capacity' => ['required', 'integer', 'min:1'],
            'roomTypes.*.price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.mimes' => 'Gambar harus berformat jpg, jpeg, atau webp.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 1 MB.',
            'category.in' => 'Kategori harus salah satu dari: resort, wisma, bungalow, homestay, villa.',
            'siteUrl.url' => 'URL website harus berupa URL yang valid.',
            'facilities.present' => 'Fasilitas wajib diisi.',
            'facilities.*.string' => 'Fasilitas harus berupa string.',
            'roomTypes.present' => 'Tipe kamar wajib disediakan.',
            'roomTypes.*.name.required' => 'Nama tipe kamar wajib diisi.',
            'roomTypes.*.description.required' => 'Deskripsi tipe kamar wajib diisi.',
            'roomTypes.*.capacity.integer' => 'Kapasitas tipe kamar harus berupa angka bulat.',
            'roomTypes.*.price.numeric' => 'Harga tipe kamar harus berupa angka.',
        ];
    }

    /**
     * @return array{
     *     title: string,
     *     description: string,
     *     full_description: string,
     *     image: string,
     *     category: string,
     *     min_price: string,
     *     max_price: string,
     *     location: string,
     *     location_map: string|null,
     *     contacs: string,
     *     site_url: string|null,
     *     facilities: list<string>
     * }
     */
    public function accomodationAttributes(): array
    {
        $validated = $this->validated();

        return [
            'title' => (string) $validated['title'],
            'description' => (string) $validated['description'],
            'full_description' => (string) $validated['fullDescription'],
            'image' => (string) $validated['image'],
            'category' => (string) $validated['category'],
            'min_price' => (string) $validated['minPrice'],
            'max_price' => (string) $validated['maxPrice'],
            'location' => (string) $validated['location'],
            'location_map' => isset($validated['location_map']) ? (string) $validated['location_map'] : null,
            'contacs' => (string) $validated['contacs'],
            'site_url' => isset($validated['siteUrl']) ? (string) $validated['siteUrl'] : null,
            'facilities' => array_values($validated['facilities']),
        ];
    }

    /**
     * @return list<array{name: string, description: string, capacity: int, price: float}>
     */
    public function roomTypeAttributes(): array
    {
        $validated = $this->validated();

        return array_values(array_map(
            static fn (array $rt): array => [
                'name' => (string) $rt['name'],
                'description' => (string) $rt['description'],
                'capacity' => (int) $rt['capacity'],
                'price' => (float) $rt['price'],
            ],
            $validated['roomTypes'],
        ));
    }
}
