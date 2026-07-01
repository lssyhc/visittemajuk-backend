<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

describe('Exception Rendering', function () {
    it('renders 404 for non-existent API route', function () {
        $this->getJson('/api/nonexistent')
            ->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Rute tidak ditemukan.',
            ]);
    });

    it('renders 401 for unauthenticated API request', function () {
        $this->getJson('/api/user')
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Anda belum terautentikasi.',
            ]);
    });

    it('renders 403 for token without required ability', function () {
        $user = User::factory()->create();
        $token = $user->createToken('limited', ['some:other:ability']);

        $this->withHeader('Authorization', 'Bearer '.$token->plainTextToken)
            ->getJson('/api/user')
            ->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengakses resource ini.',
            ]);
    });

    it('renders 422 with structured validation errors', function () {
        $this->postJson('/api/auth/login', [])
            ->assertStatus(422)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Data yang diberikan tidak valid.')
            ->assertJsonStructure([
                'success',
                'message',
                'errors' => ['username', 'password'],
            ]);
    });

    it('renders invalid credentials with the API error envelope', function () {
        $this->postJson('/api/auth/login', [
            'username' => 'missing-admin',
            'password' => 'password',
        ])->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Username atau password salah.',
            ]);
    });

    it('does not expose raw messages from unmapped HTTP exceptions', function () {
        Route::get('/api/http-exception-probe', fn () => throw new HttpException(418, 'Internal English detail'));

        $this->getJson('/api/http-exception-probe')
            ->assertStatus(418)
            ->assertJson([
                'success' => false,
                'message' => 'Terjadi kesalahan.',
                'errors' => null,
            ]);
    });
});
