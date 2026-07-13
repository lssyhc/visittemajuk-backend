<?php

declare(strict_types=1);

describe('CORS configuration', function () {
    it('allows the Vite development frontend by default', function () {
        $frontendUrls = getenv('FRONTEND_URLS');

        putenv('FRONTEND_URLS');
        unset($_ENV['FRONTEND_URLS'], $_SERVER['FRONTEND_URLS']);

        try {
            $cors = require config_path('cors.php');

            expect($cors['allowed_origins'])->toContain('http://localhost:5173')
                ->and($cors['allowed_origins'])->toContain('http://127.0.0.1:5173');
        } finally {
            if (is_string($frontendUrls)) {
                putenv('FRONTEND_URLS='.$frontendUrls);
            }
        }
    });

    it('documents both localhost and 127 vite origins in the environment example', function () {
        $environmentExample = file_get_contents(base_path('.env.example'));

        expect($environmentExample)->toContain('http://localhost:5173')
            ->and($environmentExample)->toContain('http://127.0.0.1:5173')
            ->and($environmentExample)->not->toContain("\nFRONTEND_URL=");
    });
});
