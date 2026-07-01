<?php

declare(strict_types=1);

describe('ForceJsonResponse Middleware', function () {
    it('returns JSON response for API request without JSON Accept header', function () {
        $this->get('/api/user')
            ->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Anda belum terautentikasi.',
            ])
            ->assertJsonPath('errors', null);
    });

    it('returns JSON validation errors for non-JSON API request', function () {
        $this->post('/api/auth/login', [])
            ->assertStatus(422)
            ->assertJsonStructure(['success', 'message', 'errors'])
            ->assertJsonPath('message', 'Data yang diberikan tidak valid.');
    });
});
