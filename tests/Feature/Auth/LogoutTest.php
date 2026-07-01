<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Sanctum\Sanctum;

describe('POST /api/auth/logout', function () {
    it('logs out an authenticated user', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->postJson('/api/auth/logout')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Berhasil keluar.')
            ->assertJsonPath('data', null);
    });

    it('revokes the token after logout', function () {
        $user = User::factory()->create();
        $token = $user->createToken('auth');

        $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->postJson('/api/auth/logout')
            ->assertOk();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id,
        ]);
    });

    it('rejects unauthenticated user', function () {
        $this->postJson('/api/auth/logout')
            ->assertStatus(401)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Anda belum terautentikasi.');
    });

    it('rejects request with invalid token', function () {
        $this->withHeader('Authorization', 'Bearer invalid-token')
            ->postJson('/api/auth/logout')
            ->assertStatus(401);
    });

});
