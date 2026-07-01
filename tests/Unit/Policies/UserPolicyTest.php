<?php

declare(strict_types=1);

use App\Models\User;

describe('UserPolicy', function () {

    it('allows a user to view their own profile', function () {
        $user = User::factory()->create();

        expect($user->can('view', $user))->toBeTrue();
    });

    it('denies a user from viewing another user profile', function () {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        expect($user->can('view', $otherUser))->toBeFalse();
    });

});
