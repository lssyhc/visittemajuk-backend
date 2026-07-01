<?php

declare(strict_types=1);

describe('POST /api/auth/register', function () {
    it('does not expose public account registration', function () {
        $this->postJson('/api/auth/register', [
            'username' => str_repeat('a', 256),
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertNotFound()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Rute tidak ditemukan.');
    });
});
