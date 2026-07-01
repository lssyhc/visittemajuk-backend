# Visit Temajuk Backend

Visit Temajuk Backend is a Laravel API for the Visit Temajuk tourism website. The backend serves dynamic tourism content for a React frontend and provides authenticated administration workflows for managing that content.

The project follows standard Laravel MVC conventions. Keep domain code in controllers, form requests, resources, models, policies, migrations, factories, and seeders until repeated complexity proves that another layer is needed.

## Requirements

- PHP 8.3 or newer
- Composer 2
- Node.js 24 and npm for Git hooks and documentation/config formatting
- MySQL 8 or a compatible MySQL database
- Git

Laravel 13 requires PHP 8.3 or newer. Composer also resolves the lock file against PHP 8.3 through `config.platform.php` so dependency updates remain compatible with the minimum supported runtime.

## Stack

- Laravel 13
- Laravel Sanctum token authentication
- MySQL
- Pest
- Laravel Pint
- Larastan / PHPStan level 5
- Husky, lint-staged, Commitlint
- Prettier for Markdown, JSON, and workflow formatting

## Setup

Install PHP dependencies and prepare the Laravel application:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
```

Install Node dependencies for Git hooks and non-PHP formatting:

```bash
npm install
```

The `composer setup` script can run the Laravel setup sequence for a fresh local checkout.

## Environment

`.env.example` is the local development template. Important defaults:

| Variable          | Default                 | Purpose                                  |
| ----------------- | ----------------------- | ---------------------------------------- |
| `APP_NAME`        | `Visit Temajuk`         | Application display name                 |
| `APP_LOCALE`      | `id`                    | Indonesian localization                  |
| `DB_CONNECTION`   | `mysql`                 | Default database driver                  |
| `DB_DATABASE`     | `visittemajuk`          | Local development database name          |
| `FILESYSTEM_DISK` | `public`                | Public media storage for tourism content |
| `FRONTEND_URL`    | `http://localhost:3000` | React frontend origin for CORS           |

Never commit real `.env` files, credentials, API keys, production database dumps, or deployment secrets.

## Development

```bash
composer dev
```

This starts the Laravel development server and queue listener. On non-Windows systems it also starts Laravel Pail for log tailing.

Useful focused commands:

| Command                     | Purpose                                 |
| --------------------------- | --------------------------------------- |
| `composer dev:server`       | Run only the Laravel development server |
| `php artisan migrate`       | Apply migrations                        |
| `php artisan migrate:fresh` | Rebuild the local schema                |
| `php artisan storage:link`  | Link public storage for uploaded media  |

## Quality

| Command                 | Purpose                                          |
| ----------------------- | ------------------------------------------------ |
| `composer test`         | Clear config cache and run Pest against MySQL    |
| `composer format`       | Format PHP with Laravel Pint                     |
| `composer format:check` | Check PHP formatting                             |
| `composer analyse`      | Run Larastan / PHPStan                           |
| `composer quality`      | Run Pint check, PHPStan, and Pest                |
| `npm run format`        | Format Markdown, JSON, and GitHub workflow files |
| `npm run format:check`  | Check non-PHP formatting                         |

Run these before opening a pull request:

```bash
composer quality
npm run format:check
```

## Project Structure

```text
app/
  Http/
    Controllers/      API controllers
    Middleware/       HTTP middleware
    Requests/         Form request validation
    Resources/        JSON response resources
  Models/             Eloquent models
  Policies/           Authorization policies
  Providers/          Service providers
bootstrap/            Application bootstrap
config/               Laravel configuration
database/
  factories/          Model factories
  migrations/         Database schema
  seeders/            Seed entry points
routes/
  api.php             API routes
  console.php         Console routes
tests/
  Arch/               Architecture rules
  Feature/            HTTP and integration tests
  Unit/               Focused unit tests
```

Do not add `Actions`, `DTOs`, `Services`, `Repositories`, or similar architecture folders by default. Add a new layer only when it removes proven duplication or isolates real complexity.

## API Authentication

All API routes are served under `/api`.

Public endpoint:

| Method | Path          | Purpose                                                     |
| ------ | ------------- | ----------------------------------------------------------- |
| `POST` | `/auth/login` | Authenticate an admin user and issue a Sanctum bearer token |

Authenticated endpoints:

| Method | Path            | Purpose                               |
| ------ | --------------- | ------------------------------------- |
| `POST` | `/auth/logout`  | Revoke the current token              |
| `POST` | `/auth/refresh` | Rotate the current token              |
| `GET`  | `/user`         | Return the authenticated user profile |

Public account registration is intentionally not exposed. Admin account provisioning should stay under a controlled operational workflow or an approved authenticated admin flow.

Login and refresh responses issue Sanctum personal access tokens with the `api:access` ability. Protected API routes that return or rotate admin session data require that ability in addition to `auth:sanctum`. Logout only requires a valid Sanctum token so a limited token can still revoke itself without being upgraded.

## API Responses

Use `App\Http\Responses\ApiResponse` directly for framework-level rendering and the base controller helpers for controller actions. Success responses use this envelope:

```json
{
    "success": true,
    "message": "Berhasil.",
    "data": {}
}
```

Error responses use this envelope:

```json
{
    "success": false,
    "message": "Data yang diberikan tidak valid.",
    "errors": {}
}
```

`errors` is `null` when an error has no field or detail payload. `meta` is optional for pagination or response metadata. Keep all external API `message` values in Indonesian. Validation errors keep Laravel's field-keyed error bag inside `errors` so frontend forms can bind messages directly to fields while every endpoint still has the same top-level response shape.

## Content Development

Visit Temajuk content is managed by admins and consumed by the React frontend. New content domains should use Laravel resource-oriented conventions:

- migrations define the database schema,
- models own relationships and casts,
- form requests validate input,
- controllers expose CRUD endpoints,
- resources shape JSON responses,
- policies protect admin operations,
- feature tests cover the HTTP contract.

Avoid seeding authored website content unless the team has approved it as system data or fixture data.

## Git Hooks And CI

Husky hooks are installed by `npm install`.

- `pre-commit`: runs lint-staged.
- `commit-msg`: validates Conventional Commit messages.
- `pre-push`: runs backend quality checks and non-PHP formatting checks.

GitHub Actions run backend quality checks, commit message validation, Composer audit, and dependency review.
