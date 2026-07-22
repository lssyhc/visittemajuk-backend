<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

describe('Accomodation image validation', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('api-token', ['api:access'])->plainTextToken;

        DB::table('accomodations')->insert([
            'id' => 1,
            'slug' => 'resort-test',
            'title' => 'Resort Test',
            'description' => 'Desc.',
            'full_description' => 'Full desc.',
            'image' => 'https://example.test/resort.jpg',
            'category' => 'resort',
            'min_price' => 100000,
            'max_price' => 250000,
            'location' => 'Temajuk',
            'location_map' => null,
            'contacs' => '08123456789',
            'site_url' => null,
            'facilities' => json_encode([], JSON_THROW_ON_ERROR),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    });

    it('accepts valid jpg image for create', function () {
        Storage::fake('public');

        $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->post('/api/admin/accomodations', [
                'title' => 'New Resort',
                'description' => 'Desc.',
                'fullDescription' => 'Full.',
                'image' => UploadedFile::fake()->image('resort.jpg'),
                'category' => 'resort',
                'minPrice' => 100000,
                'maxPrice' => 200000,
                'location' => 'Temajuk',
                'contacs' => '08123',
                'facilities' => [],
                'roomTypes' => [['name' => 'Standard', 'description' => 'OK', 'capacity' => 2, 'price' => 100000]],
            ])
            ->assertCreated();
    });

    it('rejects png image for create', function () {
        Storage::fake('public');

        $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->post('/api/admin/accomodations', [
                'title' => 'Bad Image',
                'description' => 'Desc.',
                'fullDescription' => 'Full.',
                'image' => UploadedFile::fake()->create('bad.png', 100, 'image/png'),
                'category' => 'resort',
                'minPrice' => 100000,
                'maxPrice' => 200000,
                'location' => 'Temajuk',
                'contacs' => '08123',
                'facilities' => [],
                'roomTypes' => [['name' => 'Standard', 'description' => 'OK', 'capacity' => 2, 'price' => 100000]],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    });

    it('rejects oversized image (>1MB) for create', function () {
        Storage::fake('public');

        $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->post('/api/admin/accomodations', [
                'title' => 'Large Image',
                'description' => 'Desc.',
                'fullDescription' => 'Full.',
                'image' => UploadedFile::fake()->image('big.jpg')->size(1025),
                'category' => 'resort',
                'minPrice' => 100000,
                'maxPrice' => 200000,
                'location' => 'Temajuk',
                'contacs' => '08123',
                'facilities' => [],
                'roomTypes' => [['name' => 'Standard', 'description' => 'OK', 'capacity' => 2, 'price' => 100000]],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    });

    it('rejects png image for update', function () {
        Storage::fake('public');

        $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->post('/api/admin/accomodations/resort-test', [
                'title' => 'Resort Test',
                'description' => 'Desc.',
                'fullDescription' => 'Full.',
                'image' => UploadedFile::fake()->create('bad.png', 100, 'image/png'),
                'category' => 'resort',
                'minPrice' => 100000,
                'maxPrice' => 200000,
                'location' => 'Temajuk',
                'contacs' => '08123',
                'facilities' => [],
                'roomTypes' => [['name' => 'Standard', 'description' => 'OK', 'capacity' => 2, 'price' => 100000]],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    });

    it('preserves existing image when no new image uploaded on update', function () {
        $this->withHeader('Authorization', 'Bearer '.$this->token)
            ->postJson('/api/admin/accomodations/resort-test', [
                'title' => 'Updated Resort',
                'description' => 'New desc.',
                'fullDescription' => 'New full.',
                'category' => 'resort',
                'minPrice' => 100000,
                'maxPrice' => 200000,
                'location' => 'New Location',
                'contacs' => '08123',
                'facilities' => [],
                'roomTypes' => [['name' => 'Standard', 'description' => 'OK', 'capacity' => 2, 'price' => 100000]],
            ])
            ->assertOk();

        $this->assertDatabaseHas('accomodations', [
            'slug' => 'resort-test',
            'image' => 'https://example.test/resort.jpg',
        ]);
    });
});
