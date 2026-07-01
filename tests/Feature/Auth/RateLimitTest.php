<?php

declare(strict_types=1);

use App\Models\User;

describe('Rate Limiting', function () {
    it('throttles login after 5 attempts', function () {
        User::factory()->create([
            'username' => 'admin',
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/auth/login', [
                'username' => 'admin',
                'password' => 'wrong-password',
            ]);
        }

        $this->postJson('/api/auth/login', [
            'username' => 'admin',
            'password' => 'wrong-password',
        ])->assertStatus(429)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Terlalu banyak percobaan. Silakan coba lagi nanti.');
    });
});
