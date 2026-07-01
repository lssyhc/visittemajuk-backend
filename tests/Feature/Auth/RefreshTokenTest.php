<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

describe('POST /api/auth/refresh', function () {
    it('refreshes the token for an authenticated user', function () {
        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access']);

        $response = $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->postJson('/api/auth/refresh');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'username', 'role', 'created_at', 'updated_at'],
                    'token',
                ],
            ])
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Token berhasil diperbarui.');

        expect($response->json('data.token'))->not->toBeEmpty();
    });

    it('issues refreshed tokens with only the API access ability', function () {
        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access']);

        $response = $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->postJson('/api/auth/refresh');

        $accessToken = PersonalAccessToken::findToken((string) $response->json('data.token'));

        expect($accessToken?->abilities)->toBe(['api:access']);
    });

    it('rejects refresh token without the API access ability', function () {
        $user = User::factory()->create();
        $token = $user->createToken('limited', ['some:other:ability']);

        $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->postJson('/api/auth/refresh')
            ->assertStatus(403)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Anda tidak memiliki izin untuk mengakses resource ini.');

        $this->assertDatabaseHas('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    });

    it('revokes the old token after refresh', function () {
        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access']);

        $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->postJson('/api/auth/refresh')
            ->assertOk();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    });

    it('returns a different token than the original', function () {
        $user = User::factory()->create();
        $token = $user->createToken('api-token', ['api:access']);

        $response = $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->postJson('/api/auth/refresh');

        $response->assertOk();
        expect($response->json('data.token'))->not->toBe($token->plainTextToken);
    });

    it('rejects unauthenticated user', function () {
        $this->postJson('/api/auth/refresh')
            ->assertStatus(401)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Anda belum terautentikasi.');
    });

    it('rejects request with invalid token', function () {
        $this->withHeader('Authorization', 'Bearer invalid-token')
            ->postJson('/api/auth/refresh')
            ->assertStatus(401);
    });

});
