<?php

declare(strict_types=1);

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

describe('DatabaseSeeder', function () {
    it('does not configure password reset brokers', function () {
        expect(config('auth.defaults.passwords'))->toBeNull()
            ->and(config('auth.passwords'))->toBe([]);
    });

    it('creates the reference admin account', function () {
        $this->seed(DatabaseSeeder::class);

        $admin = User::query()
            ->where('username', 'admin')
            ->sole();

        expect($admin->role)->toBe('admin')
            ->and($admin->password)->not->toBeEmpty()
            ->and(Hash::needsRehash($admin->password))->toBeFalse()
            ->and(Schema::hasColumn('users', 'email'))->toBeFalse()
            ->and(Schema::hasColumn('users', 'email_verified_at'))->toBeFalse();
    });
});
