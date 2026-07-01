<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

describe('POST /api/auth/login', function () {
    it('logs in with valid username credentials', function () {
        User::factory()->create([
            'username' => 'admin',
            'role' => 'admin',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'username' => 'admin',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'username', 'role', 'created_at', 'updated_at'],
                    'token',
                ],
            ])
            ->assertJsonPath('data.user.username', 'admin')
            ->assertJsonPath('data.user.role', 'admin')
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Berhasil masuk.')
            ->assertJsonMissingPath('data.user.email')
            ->assertJsonMissingPath('data.user.email_verified_at');
    });

    it('returns a non-empty token upon login', function () {
        User::factory()->create([
            'username' => 'admin',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'username' => 'admin',
            'password' => 'password',
        ]);

        $response->assertOk();
        expect($response->json('data.token'))->not->toBeEmpty();
    });

    it('issues tokens with only the API access ability', function () {
        User::factory()->create([
            'username' => 'admin',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'username' => 'admin',
            'password' => 'password',
        ]);

        $accessToken = PersonalAccessToken::findToken((string) $response->json('data.token'));

        expect($accessToken?->abilities)->toBe(['api:access']);
    });

    it('does not expose password in response', function () {
        User::factory()->create([
            'username' => 'admin',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'username' => 'admin',
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonMissingPath('data.user.password');
    });

    it('fails with wrong password', function () {
        User::factory()->create([
            'username' => 'admin',
        ]);

        $this->postJson('/api/auth/login', [
            'username' => 'admin',
            'password' => 'wrong-password',
        ])->assertStatus(401)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Username atau password salah.');
    });

    it('fails with nonexistent username', function () {
        $this->postJson('/api/auth/login', [
            'username' => 'missing-admin',
            'password' => 'password',
        ])->assertStatus(401)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Username atau password salah.');
    });

    it('fails without username', function () {
        $this->postJson('/api/auth/login', [
            'password' => 'password',
        ])->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Data yang diberikan tidak valid.')
            ->assertJsonPath('errors.username.0', 'username wajib diisi.')
            ->assertJsonValidationErrors(['username']);
    });

    it('fails without password', function () {
        $this->postJson('/api/auth/login', [
            'username' => 'admin',
        ])->assertStatus(422)->assertJsonValidationErrors(['password']);
    });

    it('fails when username is not a string', function () {
        $this->postJson('/api/auth/login', [
            'username' => ['admin'],
            'password' => 'password',
        ])->assertStatus(422)
            ->assertJsonPath('errors.username.0', 'username harus berupa teks.')
            ->assertJsonValidationErrors(['username']);
    });

    it('fails when all fields are empty', function () {
        $this->postJson('/api/auth/login', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['username', 'password']);
    });
});
