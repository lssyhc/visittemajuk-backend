<?php

declare(strict_types=1);

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

describe('UserResource', function () {

    it('transforms user model to array with correct keys', function () {
        $user = User::factory()->create();

        $resource = new UserResource($user);
        $response = $resource->toArray(new Request);

        expect($response)
            ->toHaveKeys(['id', 'username', 'role', 'created_at', 'updated_at'])
            ->and($response['id'])->toBe($user->id)
            ->and($response['username'])->toBe($user->username)
            ->and($response['role'])->toBe($user->role);
    });

    it('does not include sensitive fields', function () {
        $user = User::factory()->create();

        $resource = new UserResource($user);
        $response = $resource->toArray(new Request);

        expect($response)->not->toHaveKeys(['password']);
    });

});
