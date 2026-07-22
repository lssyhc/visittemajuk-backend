<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;

describe('Testing database configuration', function () {
    it('uses MySQL as the testing database connection', function () {
        $dbName = DB::scalar('SELECT DATABASE()');

        expect(config('database.default'))->toBe('mysql')
            ->and($dbName)->toBe('visittemajuk_test');
    });
});
