<?php

declare(strict_types=1);

use App\Models\FooterSocial;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

describe('GET /api/footer/socials', function () {
    it('returns all socials ordered for public endpoint', function () {
        FooterSocial::query()->create([
            'platform' => 'instagram', 'url' => 'https://instagram.com/a', 'order' => 2,
        ]);
        FooterSocial::query()->create([
            'platform' => 'facebook', 'url' => 'https://facebook.com/b', 'order' => 1,
        ]);

        $this->getJson('/api/footer/socials')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.platform', 'facebook'); // order=1 first
    });
});

describe('FooterSocial admin CRUD', function () {
    it('admin can create social', function () {
        Sanctum::actingAs(User::factory()->create(), ['api:access']);

        $this->postJson('/api/admin/footer/socials', [
            'platform' => 'instagram',
            'url' => 'https://instagram.com/visit',
            'order' => 1,
        ])
            ->assertCreated()
            ->assertJsonPath('data.platform', 'instagram');

        $this->assertDatabaseHas('footer_socials', ['platform' => 'instagram']);
    });

    it('admin can update social', function () {
        Sanctum::actingAs(User::factory()->create(), ['api:access']);
        $social = FooterSocial::query()->create([
            'platform' => 'facebook', 'url' => 'https://facebook.com/x', 'order' => 1,
        ]);

        $this->putJson("/api/admin/footer/socials/{$social->id}", [
            'platform' => 'tiktok',
            'url' => 'https://tiktok.com/x',
        ])
            ->assertOk()
            ->assertJsonPath('data.platform', 'tiktok');
    });

    it('admin can delete social', function () {
        Sanctum::actingAs(User::factory()->create(), ['api:access']);
        $social = FooterSocial::query()->create([
            'platform' => 'twitter', 'url' => 'https://twitter.com/x', 'order' => 1,
        ]);

        $this->deleteJson("/api/admin/footer/socials/{$social->id}")
            ->assertOk();
        $this->assertDatabaseMissing('footer_socials', ['id' => $social->id]);
    });

    it('rejects invalid platform value', function () {
        Sanctum::actingAs(User::factory()->create(), ['api:access']);

        $this->postJson('/api/admin/footer/socials', [
            'platform' => 'invalid-platform',
            'url' => 'https://example.com',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['platform']);
    });
});
