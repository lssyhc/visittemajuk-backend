<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\RateLimiter;

describe('AppServiceProvider', function () {

    it('enables model strictness in non-production', function () {
        expect(Model::preventsLazyLoading())->toBeTrue();
    });

    it('registers api rate limiter', function () {
        $limiter = RateLimiter::limiter('api');

        expect($limiter)->toBeCallable();
    });

});
