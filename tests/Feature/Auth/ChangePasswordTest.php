<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

describe('PUT /api/auth/password', function () {
    it('changes password when current password is correct', function () {
        $user = User::factory()->create([
            'password' => Hash::make('old-secret-1234'),
        ]);

        Sanctum::actingAs($user, ['api:access']);

        $response = $this->putJson('/api/auth/password', [
            'current_password' => 'old-secret-1234',
            'password' => 'new-secret-1234',
            'password_confirmation' => 'new-secret-1234',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Password berhasil diperbarui.');

        $this->assertTrue(Hash::check('new-secret-1234', $user->fresh()->password));
    });

    it('rejects wrong current password with 422', function () {
        $user = User::factory()->create([
            'password' => Hash::make('correct-old-password'),
        ]);

        Sanctum::actingAs($user, ['api:access']);

        $this->putJson('/api/auth/password', [
            'current_password' => 'WRONG-OLD-PASSWORD',
            'password' => 'new-password-1234',
            'password_confirmation' => 'new-password-1234',
        ])
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('errors.current_password.0', 'Password lama salah.');
    });

    it('rejects mismatched password confirmation', function () {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        Sanctum::actingAs($user, ['api:access']);

        $this->putJson('/api/auth/password', [
            'current_password' => 'old-password',
            'password' => 'new-password-1234',
            'password_confirmation' => 'different-password-5678',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    });

    it('rejects password shorter than 8 characters', function () {
        $user = User::factory()->create([
            'password' => Hash::make('old-password'),
        ]);

        Sanctum::actingAs($user, ['api:access']);

        $this->putJson('/api/auth/password', [
            'current_password' => 'old-password',
            'password' => 'short',
            'password_confirmation' => 'short',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    });

    it('requires authentication', function () {
        $this->putJson('/api/auth/password', [
            'current_password' => 'whatever',
            'password' => 'whatever1234',
            'password_confirmation' => 'whatever1234',
        ])->assertUnauthorized();
    });

    it('keeps the current token valid after password change', function () {
        $user = User::factory()->create([
            'password' => Hash::make('original-password'),
        ]);

        $token = $user->createToken('api-token', ['api:access']);
        $plainText = $token->plainTextToken;

        $this->withHeader('Authorization', 'Bearer '.$plainText)
            ->putJson('/api/auth/password', [
                'current_password' => 'original-password',
                'password' => 'updated-password',
                'password_confirmation' => 'updated-password',
            ])
            ->assertOk();

        // Token should still work for authenticated endpoints
        $this->withHeader('Authorization', 'Bearer '.$plainText)
            ->getJson('/api/user')
            ->assertOk();
    });
});
