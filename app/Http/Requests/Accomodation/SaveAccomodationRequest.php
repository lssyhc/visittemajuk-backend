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
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'fullDescription' => ['required', 'string'],
            'imageUrl' => ['required', 'string', 'url', 'max:2048'],
            'category' => ['required', 'string', 'in:resort,wisma,bungalow,homestay,villa'],
            'minPrice' => ['required', 'numeric', 'min:0'],
            'maxPrice' => ['required', 'numeric', 'min:0'],
            'location' => ['required', 'string'],
            'contacs' => ['required', 'string', 'max:255'],
            'siteUrl' => ['nullable', 'string', 'url', 'max:2048'],
            'facilities' => ['present', 'array'],
            'facilities.*' => ['required', 'string', 'max:255'],
            'gallery' => ['present', 'array'],
            'gallery.*' => ['required', 'string', 'url', 'max:2048'],
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
            'category.in' => 'Kategori harus salah satu dari: resort, wisma, bungalow, homestay, villa.',
            'imageUrl.url' => 'URL gambar utama harus berupa URL yang valid.',
            'siteUrl.url' => 'URL website harus berupa URL yang valid.',
            'gallery.*.url' => 'Setiap URL galeri harus berupa URL yang valid.',
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
     *     image_url: string,
     *     category: string,
     *     min_price: string,
     *     max_price: string,
     *     location: string,
     *     contacs: string,
     *     site_url: string,
     *     facilities: list<string>,
     *     gallery: list<string>
     * }
     */
    public function accomodationAttributes(): array
    {
        $validated = $this->validated();

        return [
            'title' => (string) $validated['title'],
            'description' => (string) $validated['description'],
            'full_description' => (string) $validated['fullDescription'],
            'image_url' => (string) $validated['imageUrl'],
            'category' => (string) $validated['category'],
            'min_price' => (string) $validated['minPrice'],
            'max_price' => (string) $validated['maxPrice'],
            'location' => (string) $validated['location'],
            'contacs' => (string) $validated['contacs'],
            'site_url' => (string) $validated['siteUrl'],
            'facilities' => array_values($validated['facilities']),
            'gallery' => array_values($validated['gallery']),
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
