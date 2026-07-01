<?php

declare(strict_types=1);

use App\Models\User;
use Laravel\Sanctum\Sanctum;

describe('GET /api/user', function () {
    it('returns authenticated user profile', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['api:access']);

        $response = $this->getJson('/api/user');

        $response->assertOk()
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['id', 'username', 'role', 'created_at', 'updated_at'],
            ])
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.username', $user->username)
            ->assertJsonPath('data.role', $user->role)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Berhasil.');
    });

    it('does not expose password', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user, ['api:access']);

        $this->getJson('/api/user')
            ->assertOk()
            ->assertJsonMissingPath('data.password');
    });

    it('rejects unauthenticated request', function () {
        $this->getJson('/api/user')
            ->assertStatus(401)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Anda belum terautentikasi.');
    });

    it('rejects request with invalid token', function () {
        $this->withHeader('Authorization', 'Bearer invalid-token')
            ->getJson('/api/user')
            ->assertStatus(401);
    });

    it('rejects token without required ability', function () {
        $user = User::factory()->create();

        $token = $user->createToken('limited', ['some:other:ability']);

        $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->getJson('/api/user')
            ->assertStatus(403)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Anda tidak memiliki izin untuk mengakses resource ini.');
    });

});
