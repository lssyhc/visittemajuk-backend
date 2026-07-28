<?php

declare(strict_types=1);

use App\Models\PhotographyTip;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

describe('GET /api/photography-tips', function () {
    it('returns all tips ordered for public endpoint', function () {
        PhotographyTip::query()->create([
            'title' => 'Golden Hour', 'description' => 'Shoot during golden hour.', 'order' => 2,
        ]);
        PhotographyTip::query()->create([
            'title' => 'Rule of Thirds', 'description' => 'Use the rule of thirds.', 'order' => 1,
        ]);

        $this->getJson('/api/photography-tips')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.title', 'Rule of Thirds')
            ->assertJsonPath('data.1.title', 'Golden Hour');
    });

    it('returns empty array when no tips exist', function () {
        $this->getJson('/api/photography-tips')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    });
});

describe('PhotographyTip admin CRUD', function () {
    it('admin can list tips', function () {
        Sanctum::actingAs(User::factory()->create(), ['api:access']);

        PhotographyTip::query()->create([
            'title' => 'Tip A', 'description' => 'Desc A', 'order' => 1,
        ]);

        $this->getJson('/api/admin/photography-tips')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Tip A');
    });

    it('admin can create tip', function () {
        Sanctum::actingAs(User::factory()->create(), ['api:access']);

        $this->postJson('/api/admin/photography-tips', [
            'title' => 'Composition',
            'description' => 'Learn about composition techniques.',
            'order' => 1,
        ])
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Tips fotografi berhasil dibuat.')
            ->assertJsonPath('data.title', 'Composition');

        $this->assertDatabaseHas('photography_tips', ['title' => 'Composition']);
    });

    it('admin can create tip with image', function () {
        Storage::fake('public');
        Sanctum::actingAs(User::factory()->create(), ['api:access']);

        $response = $this->post('/api/admin/photography-tips', [
            'title' => 'With Image',
            'description' => 'Tip with an image.',
            'image' => UploadedFile::fake()->image('tip.jpg'),
        ]);

        $response->assertCreated();
        Storage::disk('public')->assertExists($response->json('data.image'));
    });

    it('admin can update tip', function () {
        Sanctum::actingAs(User::factory()->create(), ['api:access']);
        $tip = PhotographyTip::query()->create([
            'title' => 'Old Title', 'description' => 'Old desc.', 'order' => 1,
        ]);

        $this->putJson("/api/admin/photography-tips/{$tip->id}", [
            'title' => 'New Title',
            'description' => 'New description.',
        ])
            ->assertOk()
            ->assertJsonPath('data.title', 'New Title');
    });

    it('admin can delete tip and cleanup image', function () {
        Storage::fake('public');
        Sanctum::actingAs(User::factory()->create(), ['api:access']);

        $tip = PhotographyTip::query()->create([
            'title' => 'To Delete', 'description' => 'Will be deleted.', 'order' => 1,
        ]);
        Storage::disk('public')->put('photography-tips/test.jpg', 'content');
        $tip->update(['image' => 'photography-tips/test.jpg']);

        $this->deleteJson("/api/admin/photography-tips/{$tip->id}")
            ->assertOk()
            ->assertJsonPath('message', 'Tips fotografi berhasil dihapus.');

        $this->assertDatabaseMissing('photography_tips', ['id' => $tip->id]);
        Storage::disk('public')->assertMissing('photography-tips/test.jpg');
    });

    it('rejects invalid image format', function () {
        Storage::fake('public');
        Sanctum::actingAs(User::factory()->create(), ['api:access']);

        $this->post('/api/admin/photography-tips', [
            'title' => 'Bad Image',
            'description' => 'Has a PNG image.',
            'image' => UploadedFile::fake()->create('bad.png', 100, 'image/png'),
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    });

    it('rejects oversized image', function () {
        Storage::fake('public');
        Sanctum::actingAs(User::factory()->create(), ['api:access']);

        $this->post('/api/admin/photography-tips', [
            'title' => 'Large Image',
            'description' => 'Image too large.',
            'image' => UploadedFile::fake()->image('big.jpg')->size(1025),
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['image']);
    });

    it('requires authentication', function () {
        $this->postJson('/api/admin/photography-tips', [
            'title' => 'No Auth', 'description' => 'Should fail.',
        ])
            ->assertUnauthorized();
    });
});
