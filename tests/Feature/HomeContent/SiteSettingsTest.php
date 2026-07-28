<?php

declare(strict_types=1);

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

describe('GET /api/site/settings', function () {
    it('returns settings as flat keys with decoded JSON values', function () {
        SiteSetting::query()->create([
            'key' => 'home.hero',
            'value' => ['title' => 'T', 'subtitle' => 'S'],
        ]);

        $response = $this->getJson('/api/site/settings')->assertOk();
        $payload = $response->json('data');
        expect($payload['home.hero']['title'])->toBe('T');
        expect($payload['home.hero']['subtitle'])->toBe('S');
    });

    it('returns empty object when no settings configured', function () {
        $this->getJson('/api/site/settings')
            ->assertOk()
            ->assertJsonStructure(['success', 'message', 'data']);
    });
});

describe('PUT /api/admin/site/settings', function () {
    it('requires authentication', function () {
        $this->putJson('/api/admin/site/settings', [])
            ->assertUnauthorized();
    });

    it('updates the home.hero settings as a group', function () {
        Sanctum::actingAs(User::factory()->create(), ['api:access']);

        $this->putJson('/api/admin/site/settings', [
            'home' => [
                'hero' => [
                    'title' => 'New Hero Title',
                    'subtitle' => 'New Subtitle',
                ],
            ],
        ])
            ->assertOk();

        $row = SiteSetting::query()->where('key', 'home.hero')->first();
        expect($row->value)->toMatchArray([
            'title' => 'New Hero Title',
            'subtitle' => 'New Subtitle',
        ]);
    });

    it('updates section titles from a nested array', function () {
        Sanctum::actingAs(User::factory()->create(), ['api:access']);

        $this->putJson('/api/admin/site/settings', [
            'home' => [
                'section_titles' => [
                    'destinations' => 'Wisata Andalan',
                    'accommodations' => 'Penginapan Rekomendasi',
                ],
            ],
        ])
            ->assertOk();

        $row = SiteSetting::query()->where('key', 'home.section_titles')->first();
        expect($row->value)->toMatchArray([
            'destinations' => 'Wisata Andalan',
            'accommodations' => 'Penginapan Rekomendasi',
        ]);
    });

    it('stores uploaded hero image to disk', function () {
        Storage::fake('public');
        Sanctum::actingAs(User::factory()->create(), ['api:access']);

        $response = $this->post('/api/admin/site/settings', [
            '_method' => 'PUT',
            'home' => [
                'hero' => [
                    'title' => 'Hero with Image',
                    'image' => UploadedFile::fake()->image('hero.jpg'),
                ],
            ],
        ]);

        $response->assertOk();

        $row = SiteSetting::query()->where('key', 'home.hero')->first();
        expect($row)->not->toBeNull();
        expect($row->value)->toHaveKey('image');
        Storage::disk('public')->assertExists($row->value['image']);
    });

    it('updates features as an array', function () {
        Sanctum::actingAs(User::factory()->create(), ['api:access']);

        $this->putJson('/api/admin/site/settings', [
            'home' => [
                'features' => [
                    ['title' => 'Feature One', 'body' => 'Body one', 'icon' => 'Map'],
                    ['title' => 'Feature Two', 'body' => 'Body two', 'icon' => 'Camera'],
                ],
            ],
        ])->assertOk();

        $row = SiteSetting::query()->where('key', 'home.features')->first();
        expect($row->value)->toBeArray();
        expect($row->value[0]['title'])->toBe('Feature One');
        expect($row->value[1]['icon'])->toBe('Camera');
    });

    it('updates transport_cta as an object', function () {
        Sanctum::actingAs(User::factory()->create(), ['api:access']);

        $this->putJson('/api/admin/site/settings', [
            'home' => [
                'transport_cta' => [
                    'title' => 'Panduan Transportasi',
                    'body' => 'Info lengkap transportasi.',
                    'button_text' => 'Lihat Panduan',
                    'button_link' => '/transportasi',
                ],
            ],
        ])->assertOk();

        $row = SiteSetting::query()->where('key', 'home.transport_cta')->first();
        expect($row->value)->toMatchArray([
            'title' => 'Panduan Transportasi',
            'button_link' => '/transportasi',
        ]);
    });
});
