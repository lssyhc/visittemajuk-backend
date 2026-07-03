<?php

declare(strict_types=1);

use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\DB;

describe('Destination seed data', function () {
    it('seeds the current frontend destination reference content', function () {
        $this->seed(DatabaseSeeder::class);

        expect(DB::table('destinations')->count())->toBeGreaterThanOrEqual(16);

        $this->assertDatabaseHas('destinations', [
            'slug' => 'pantai-temajuk',
            'title' => 'Pantai Temajuk',
            'category' => 'Pantai',
            'price' => 'Rp 10.000',
        ]);

        $this->assertDatabaseHas('destinations', [
            'slug' => 'air-terjun-coras',
            'title' => 'Air Terjun Carocok Antu Soreh (Coras)',
            'category' => 'Air Terjun',
            'price' => 'Rp 20.000',
        ]);

        $response = $this->getJson('/api/destinations?per_page=50')
            ->assertOk();

        $this->assertNotContains('Budaya', $response->json('meta.filters.categories'));
        $this->assertNotContains('Budaya', array_column($response->json('data'), 'category'));

        expect(DB::table('destinations')->where('image_url', 'not like', 'http%')->count())->toBe(0);
        expect(DB::table('destinations')->where('category', 'Budaya')->count())->toBe(0);

        $this->assertDatabaseMissing('destinations', [
            'slug' => 'kampung-nelayan-temajuk',
        ]);

        $this->assertDatabaseMissing('destinations', [
            'slug' => 'pasar-kecil-temajuk',
        ]);
    });
});
