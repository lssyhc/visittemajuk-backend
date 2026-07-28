<?php

declare(strict_types=1);

use App\Models\Destination;

describe('POST /api/reviews with destination_slug', function () {
    it('creates a review with valid destination_slug', function () {
        $destination = Destination::query()->create([
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'description' => 'desc',
            'full_description' => 'full',
            'image' => 'https://example.test/img.jpg',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Temajuk',
            'open_hours' => '24 jam',
            'facilities' => [],
            'activities' => [],
            'tips' => [],
        ]);

        $response = $this->postJson('/api/reviews', [
            'name' => 'Pengunjung',
            'text' => 'Tempat yang sangat indah.',
            'destination_slug' => 'pantai-temajuk',
            'rating' => 5,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Pengunjung')
            ->assertJsonPath('data.rating', 5);

        $this->assertDatabaseHas('reviews', [
            'destination_id' => $destination->id,
            'name' => 'Pengunjung',
            'rating' => 5,
        ]);
    });

    it('rejects invalid destination_slug with 422', function () {
        Destination::query()->create([
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'description' => 'desc',
            'full_description' => 'full',
            'image' => 'https://example.test/img.jpg',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Temajuk',
            'open_hours' => '24 jam',
            'facilities' => [],
            'activities' => [],
            'tips' => [],
        ]);

        $this->postJson('/api/reviews', [
            'name' => 'X',
            'text' => 'Teks',
            'destination_slug' => 'slug-tidak-ada',
            'rating' => 4,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['destination_slug']);
    });

    it('rejects rating outside 1-5 range', function () {
        Destination::query()->create([
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'description' => 'desc',
            'full_description' => 'full',
            'image' => 'https://example.test/img.jpg',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Temajuk',
            'open_hours' => '24 jam',
            'facilities' => [],
            'activities' => [],
            'tips' => [],
        ]);

        $this->postJson('/api/reviews', [
            'name' => 'X',
            'text' => 'Teks',
            'destination_slug' => 'pantai-temajuk',
            'rating' => 10,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['rating']);
    });

    it('rejects text longer than 512 characters', function () {
        Destination::query()->create([
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'description' => 'desc',
            'full_description' => 'full',
            'image' => 'https://example.test/img.jpg',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
            'location' => 'Temajuk',
            'open_hours' => '24 jam',
            'facilities' => [],
            'activities' => [],
            'tips' => [],
        ]);

        $this->postJson('/api/reviews', [
            'name' => 'X',
            'text' => str_repeat('a', 513),
            'destination_slug' => 'pantai-temajuk',
            'rating' => 4,
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['text']);
    });
});
