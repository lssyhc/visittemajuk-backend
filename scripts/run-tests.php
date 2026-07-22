<?php

declare(strict_types=1);

// Test runner used by `composer test`. Ensures `.env.testing` is in place, then
// unsets any host-shell env vars that would override the testing configuration
// before invoking the artisan test runner. Without this, a polluted dev shell
// (e.g. one that exports APP_ENV=local) silently wins against the immutable
// Dotenv loader and tests fail for environment reasons rather than code ones.

$root = dirname(__DIR__);
chdir($root);

$envFile = $root.'/.env.testing';
$template = $root.'/.env.testing.example';

if (! is_file($envFile) && is_file($template)) {
    copy($template, $envFile);
    fwrite(STDERR, '[test] copied .env.testing.example to .env.testing'.PHP_EOL);
}

// Drop every env var Laravel reads from so a polluted host shell can't
// silently override the testing configuration that .env.testing declares.
$wipe = [
    'APP_ENV', 'APP_KEY', 'APP_DEBUG', 'APP_URL',
    'APP_LOCALE', 'APP_FALLBACK_LOCALE', 'APP_FAKER_LOCALE', 'APP_MAINTENANCE_DRIVER',
    'DB_CONNECTION', 'DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD', 'DB_URL',
    'CACHE_STORE', 'QUEUE_CONNECTION', 'SESSION_DRIVER', 'BROADCAST_CONNECTION',
    'MAIL_MAILER', 'LOG_CHANNEL', 'LOG_STACK', 'LOG_LEVEL',
    'BCRYPT_ROUNDS', 'FILESYSTEM_DISK',
    'SANCTUM_STATEFUL_DOMAINS', 'SANCTUM_TOKEN_EXPIRATION',
    'FRONTEND_URLS', 'APP_NAME',
];

foreach ($wipe as $key) {
    unset($GLOBALS['_ENV'][$key], $GLOBALS['_SERVER'][$key]);
    putenv($key);
}

passthru(escapeshellcmd(PHP_BINARY).' artisan config:clear --ansi', $clearStatus);

if ($clearStatus !== 0) {
    exit($clearStatus);
}

$args = array_slice($argv, 1);
$cmd = escapeshellcmd(PHP_BINARY).' artisan test'.($args === [] ? '' : ' '.implode(' ', array_map('escapeshellarg', $args)));

passthru($cmd, $testStatus);

exit($testStatus);
