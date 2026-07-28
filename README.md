# Visit Temajuk Backend

Laravel API for the Visit Temajuk tourism site. It serves the React frontend and backs the admin workflows for managing tourism content.

Code stays in controllers, form requests, resources, models, policies, migrations, factories, and seeders — the usual Laravel layers. Only add a new layer (Actions, DTOs, Services, etc.) when a real duplication or complexity shows up.

## Requirements

- PHP 8.3+ with `mbstring`, `dom`, `fileinfo`, `pdo_mysql`, `bcmath`, `curl`, `openssl`, `tokenizer`, `xml`, `intl`, `gd` (the `Dockerfile` installs both — `intl` for Laravel's locale-aware date formatting, `gd` baked in for any future image processing)
- Composer 2 (https://getcomposer.org/download/)
- Node.js 24, npm 10+ (for Git hooks and formatting)
- MySQL 8.0+ (or a compatible fork)
- Git 2.30+ (https://git-scm.com/downloads)
- Docker 24+ and Compose v2 (optional, for the containerised toolchain — see `docs/docker.md`)

Laravel 13 needs PHP 8.3 minimum. The lock file is pinned to 8.3 via `config.platform.php`, so `composer install` won't pull in packages that need newer PHP.

More: [Laravel 13 requirements](https://laravel.com/docs/13.x/deployment#server-requirements), [Composer platform config](https://getcomposer.org/doc/06-config.md#platform).

## Stack

- [Laravel 13](https://laravel.com/docs/13.x)
- [Laravel Sanctum 4](https://laravel.com/docs/13.x/sanctum) for token auth
- MySQL 8
- [Pest 4](https://pestphp.com/docs)
- [Laravel Pint 1](https://laravel.com/docs/13.x/pint)
- [Larastan](https://larastan.laravelshift.com/) / [PHPStan](https://phpstan.org/) at level 5
- Husky 9, lint-staged 17, Commitlint 21 (Conventional Commits)
- Prettier 3 for Markdown, JSON, JS, and GitHub workflow files

## Setup

Run these in order. Skipping a step usually breaks the next one.

### 1. Clone

```bash
git clone <repository-url> visittemajuk-backend
cd visittemajuk-backend
```

### 2. Create the local database

```sql
CREATE DATABASE visittemajuk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'visittemajuk'@'127.0.0.1' IDENTIFIED BY 'change-me';
GRANT ALL PRIVILEGES ON visittemajuk.* TO 'visittemajuk'@'127.0.0.1';
FLUSH PRIVILEGES;
```

Default DB name is `visittemajuk` (see `.env.example`). The test DB is `visittemajuk_test` (see `phpunit.xml`).

### 3. Install PHP deps and bootstrap Laravel

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
```

`storage:link` exposes `storage/app/public` under `public/storage` so uploaded media is reachable from the web.

### 4. Bootstrap the testing environment

`.env.testing` is git-ignored because it carries a real `APP_KEY`, and that key differs across developers and CI runs. The committed template is `.env.testing.example`.

`composer test` copies the template into `.env.testing` automatically on first run when running natively on the host (see `scripts/run-tests.php`). Inside Docker, the `tools` container receives its environment from `docker-compose.yml` instead. The manual setup below is only needed if you want to run `php artisan` commands against the testing environment yourself:

```bash
cp .env.testing.example .env.testing
KEY="base64:$(openssl rand -base64 32)"
sed -i "s|^APP_KEY=.*|APP_KEY=${KEY}|" .env.testing
```

The first command copies the template (placeholder `APP_KEY=`) into your local `.env.testing`. The next two commands write a fresh `APP_KEY` into it via `openssl` + `sed` because `php artisan key:generate --env=testing --force` looks for `.env` (not `.env.testing`) and silently fails when it's missing.

`composer test` also needs the `visittemajuk_test` MySQL database. The Docker toolchain creates it on first run (see `docker/mysql/init/01-test-db.sql`). Outside Docker, create it manually. The test config (`phpunit.xml`) connects as MySQL `root`, so the database just needs to exist with default permissions:

```sql
CREATE DATABASE IF NOT EXISTS visittemajuk_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

If your local MySQL doesn't allow `root` to connect over TCP, override the test DB credentials in a new `.env.testing` block or in `phpunit.xml`.

### 5. Install Node deps (Git hooks and formatters)

```bash
npm install
```

This also triggers Husky to install `pre-commit`, `commit-msg`, and `pre-push` hooks (see `.husky/`).

### 6. (Optional) One-shot script

```bash
composer setup
```

Runs the Laravel setup sequence: install, copy `.env.example` if missing, generate key, migrate, link storage.

### 7. Verify

```bash
php artisan --version
php artisan route:list --path=api
composer quality
```

If `composer quality` passes, your local toolchain matches CI.

## Environment

`.env.example` is the dev template. Worth knowing:

| Variable          | Default                                       | What it does                  |
| ----------------- | --------------------------------------------- | ----------------------------- |
| `APP_NAME`        | `Visit Temajuk`                               | Display name                  |
| `APP_LOCALE`      | `id`                                          | Default locale                |
| `DB_CONNECTION`   | `mysql`                                       | Database driver               |
| `DB_DATABASE`     | `visittemajuk`                                | Local DB name                 |
| `FILESYSTEM_DISK` | `public`                                      | Public disk for tourism media |
| `FRONTEND_URLS`   | `http://localhost:5173,http://127.0.0.1:5173` | Allowed origins for CORS      |

`SANCTUM_STATEFUL_DOMAINS` and `SANCTUM_TOKEN_EXPIRATION` control SPA and bearer token behaviour. Don't commit real `.env` files, credentials, API keys, prod DB dumps, or any deployment secret.

`.env.testing` is git-ignored. The committed template is `.env.testing.example`; copy it to `.env.testing` and generate a fresh `APP_KEY` before running tests locally (see step 4 in [Setup](#setup)). The template points at `visittemajuk_test`.

## Development

```bash
composer dev
```

Starts the Laravel dev server, queue listener, and (on non-Windows) Laravel Pail for log tailing. `scripts/dev.mjs` supervises the children and forwards `SIGINT`/`SIGTERM`.

Handy commands:

| Command                       | What it does                     |
| ----------------------------- | -------------------------------- |
| `composer dev:server`         | Just the dev server              |
| `php artisan migrate`         | Apply migrations                 |
| `php artisan migrate:fresh`   | Rebuild the local schema         |
| `php artisan storage:link`    | Link public storage              |
| `php artisan route:list`      | List all registered routes       |
| `php artisan tinker`          | Open an interactive REPL         |
| `composer migrate:test`       | Migrate the test DB              |
| `composer migrate:fresh:test` | Rebuild the test DB from scratch |

## Quality

| Command                        | What it does                                                         |
| ------------------------------ | -------------------------------------------------------------------- |
| `composer test`                | Clear config cache, run Pest against MySQL                           |
| `composer format`              | Format PHP with Pint                                                 |
| `composer format:check`        | Check PHP formatting                                                 |
| `composer analyse`             | Run Larastan / PHPStan                                               |
| `composer quality`             | Pint check + PHPStan + Pest                                          |
| `composer audit`               | Run `composer audit` (security advisories + abandoned packages)      |
| `composer audit --locked`      | Audit `composer.lock` directly without writing to the lockfile first |
| `composer audit --format=json` | Same audit, machine-readable output                                  |
| `composer audit --no-dev`      | Audit only runtime dependencies                                      |
| `npm run format`               | Format MD/JSON/JS/Workflow files via Prettier                        |
| `npm run format:check`         | Check non-PHP formatting                                             |

Before opening a PR:

```bash
composer quality
npm run format:check
```

PHPStan config is in `phpstan.neon` (level 5, Larastan extension). PHP style is in `pint.json` (Laravel preset, alphabetical imports, `declare_strict_types=1`). See `docs/phpstan.md` for the level 5 rule set.

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
docs/                 Team docs
  docker.md           Docker toolchain
  git-workflow.md     Branching and PR guide
  phpstan.md          PHPStan level 5 rules
routes/
  api.php             API routes
  console.php         Console routes
tests/
  Arch/               Architecture rules
  Feature/            HTTP and integration tests
  Unit/               Focused unit tests
```

Skip `Actions`, `DTOs`, `Services`, `Repositories`, and similar layers until they pay for themselves. Add one only when it removes real duplication or isolates real complexity.

## API Authentication

All API routes are served under `/api`.

Public:

| Method | Path          | What it does                                 |
| ------ | ------------- | -------------------------------------------- |
| `POST` | `/auth/login` | Authenticate an admin, issue a Sanctum token |

Authenticated:

| Method | Path            | What it does                  |
| ------ | --------------- | ----------------------------- |
| `POST` | `/auth/logout`  | Revoke the current token      |
| `POST` | `/auth/refresh` | Rotate the current token      |
| `GET`  | `/user`         | Return the authenticated user |

Public registration is intentionally not exposed. Admin accounts are provisioned through a controlled operational workflow or an approved admin flow.

Login and refresh issue Sanctum personal access tokens with the `api:access` ability. Endpoints that return or rotate admin data also need that ability on top of `auth:sanctum`. Logout only needs a valid Sanctum token, so a limited token can still revoke itself.

## API Responses

Use `App\Http\Responses\ApiResponse` for framework-level rendering and the base controller helpers inside controller actions. Success:

```json
{
    "success": true,
    "message": "Berhasil.",
    "data": {}
}
```

Error:

```json
{
    "success": false,
    "message": "Data yang diberikan tidak valid.",
    "errors": {}
}
```

`errors` is `null` when there's no field or detail payload. `meta` is optional (for pagination, etc.). All external API `message` values stay in Indonesian. Validation errors keep Laravel's field-keyed bag inside `errors`, so frontend forms can bind messages to fields while every endpoint still shares the same top-level shape.

## Content Development

Visit Temajuk content is managed by admins and consumed by the React frontend. New content domains should follow the usual Laravel resource-oriented flow:

- migrations for the schema,
- models for relationships and casts,
- form requests for input validation,
- controllers for CRUD endpoints,
- resources for JSON shape,
- policies for authorisation,
- feature tests for the HTTP contract.

Skip seeding authored website content unless it's approved as system or fixture data.

## Git Hooks And CI

Husky hooks are installed by `npm install` (via the `prepare` script):

- `pre-commit`: runs lint-staged — Pint for `*.php`, Prettier for `*.json`, `*.md`, `*.js`, `scripts/**/*.mjs`, and `.github/**/*.{yml,yaml,md}`.
- `commit-msg`: validates Conventional Commit messages.
- `pre-push`: runs `composer quality` and `npm run format:check`. When Docker Compose is available (the `db` service is running), it auto-routes the checks through the `tools` container so every teammate runs the quality gate in the same environment regardless of their local PHP/MySQL setup.

See `docs/git-workflow.md` for the full branching model, commit format, and PR checklist.

GitHub Actions: `ci.yml` (quality), `commitlint.yml` (commit message validation on PRs), `dependency-review.yml` (Composer audit + dependency review), `code-scanning.yml` (Semgrep SAST).

## Documentation

- [docs/docker.md](docs/docker.md) — Containerised toolchain setup.
- [docs/git-workflow.md](docs/git-workflow.md) — Branching, commit format, PR process.
- [docs/phpstan.md](docs/phpstan.md) — PHPStan level 5 rules with examples.

## References

- Laravel 13: https://laravel.com/docs/13.x
- PHPStan: https://phpstan.org/user-guide/getting-started
- Larastan: https://larastan.laravelshift.com/
- Pest: https://pestphp.com/docs/installation
- Conventional Commits: https://www.conventionalcommits.org/en/v1.0.0/
- Docker: https://docs.docker.com/
