<?php

declare(strict_types=1);

describe('Testing database configuration', function () {
    it('uses MySQL as the testing database connection', function () {
        expect(config('database.default'))->toBe('mysql')
            ->and(config('database.connections.mysql.database'))->toBe('visittemajuk_test');
    });
});
