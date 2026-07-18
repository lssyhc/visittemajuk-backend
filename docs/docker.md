# Docker Toolchain

How to use the `Dockerfile` and `docker-compose.yml` at the root. The goal is the same environment on every machine (Linux, macOS, Windows/WSL2) and reproducible CI locally.

## References

- Docker Engine: <https://docs.docker.com/engine/>
- Docker Compose: <https://docs.docker.com/compose/>
- Dockerfile reference: <https://docs.docker.com/reference/dockerfile/>
- Base images:
    - [php](https://hub.docker.com/_/php)
    - [composer](https://hub.docker.com/_/composer)
    - [node](https://hub.docker.com/_/node)
    - [mysql](https://hub.docker.com/_/mysql)
- Dockerfile best practices: <https://docs.docker.com/build/building/best-practices/>
- Healthcheck: <https://docs.docker.com/reference/compose-file/services/#healthcheck>

## Prerequisites

- Docker Engine 24+
- Docker Compose v2 (`docker compose`, no dash)
- Ports 3306 (MySQL) and 8000 (Laravel) free on the host. Change the mappings in `docker-compose.yml` if they collide.

## Services

`docker-compose.yml` defines five services:

| Service | What it does                                                                                                                                                           | Base image                                        |
| ------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------------------------------------------------- |
| `app`   | Runs `php artisan serve` on port 8000                                                                                                                                  | `php:8.3-cli-bookworm` + `composer:2` + `node:24` |
| `queue` | Runs `php artisan queue:listen`. The app does not dispatch jobs today, so this container stays idle; it stays in compose so adding jobs later needs no compose change. | Same as `app`                                     |
| `tools` | Ad-hoc runner for `composer quality`, `npm run ...`. On start it runs `php artisan migrate --env=testing --force`, then drops into a bash shell.                       | Same as `app`                                     |
| `node`  | Standalone Node container for assets and scripts. On start it runs `npm install`, then drops into a bash shell.                                                        | `node:24-bookworm-slim`                           |
| `db`    | MySQL 8 with the `visittemajuk` database and a user                                                                                                                    | `mysql:8.4`                                       |

Port mappings, environment variables, and volumes are in [`docker-compose.yml`](../docker-compose.yml).

## First build

```bash
docker compose build
```

Builds the `visittemajuk/app:local` image from the `Dockerfile`. Composer and Node are pulled in as separate base images via `COPY --from=`, so the final image stays small.

The `composer-cache` and `npm-cache` named volumes attach at runtime, not at build time. They speed up package installs across container restarts and `docker compose up` cycles, but every `docker compose build` re-downloads dependencies. Layer cache still helps when only source files change: the lockfiles (`composer.json`, `composer.lock`, `package.json`, `package-lock.json`) are copied in their own layer before `COPY . .`, so a no-lockfile change keeps the install layer cached.

## Running the stack

```bash
# Start everything in the background
docker compose up -d

# Tail logs
docker compose logs -f app

# Stop everything
docker compose down

# Stop and wipe volumes (resets the database)
docker compose down -v
```

Wait for `db` to pass its healthcheck before `app` and `tools` are ready. Check with `docker compose ps`.

## Getting into a container

```bash
# Interactive shell in app
docker compose exec app bash

# Run artisan from the host
docker compose exec app php artisan migrate

# Run tooling from the host
docker compose exec tools composer quality
docker compose exec tools npm run format:check
```

The entrypoint (`docker/entrypoint.sh`) takes care of:

1. Waiting for `db` with `mysqladmin ping`.
2. Copying `.env.example` to `.env` if it's missing.
3. Running `key:generate` if `APP_KEY` is empty.
4. Creating the `public/storage` symlink via `storage:link`.

## Running the quality gate inside the container

```bash
# Pint check
docker compose exec tools composer format:check

# PHPStan / Larastan
docker compose exec tools composer analyse

# Pest
docker compose exec tools composer test

# All of the above
docker compose exec tools composer quality

# Prettier for MD/JSON/JS
docker compose exec tools npm run format:check
```

`tools` reads `DB_HOST=db`, so PHPUnit/Pest connect to the same MySQL container as the app. They use a separate `visittemajuk_test` database (matching `phpunit.xml`), which is created and migrated automatically when `tools` starts (see the Services table).

## Development without Docker

If you'd rather run PHP, Composer, and Node directly on the host, follow the [README](../README.md) from the _Setup_ section. Docker is optional; the GitHub Actions CI uses `ubuntu-latest` directly.

## Docker vs non-Docker: what actually changes

The only difference is the initial project setup. After the stack is running, the day-to-day flow is identical regardless of whether commands are typed on the host or inside a container.

| Step                    | Docker path                                      | Non-Docker path                         |
| ----------------------- | ------------------------------------------------ | --------------------------------------- |
| Bring up the stack      | `docker compose up -d`                           | Start MySQL and run `composer install`  |
| Run the app             | `docker compose up -d app` (port 8000)           | `composer dev` (or `php artisan serve`) |
| Run quality tools       | `docker compose exec tools composer quality`     | `composer quality` on the host          |
| Format Markdown/JSON/JS | `docker compose exec tools npm run format:check` | `npm run format:check` on the host      |
| Apply migrations        | `docker compose exec app php artisan migrate`    | `php artisan migrate`                   |

Once the stack is up, you edit files on the host either way. The `app` and `node` containers read through the bind mount `./:/workspace`, so file changes are picked up without rebuilding.

## Daily workflow with Docker

1. `git pull --rebase` on the host.
2. `docker compose build` only when `Dockerfile`, `composer.json`, or `package.json` changed. The bind mount covers source files; `package.json` changes also need a `docker compose restart node` (or rerun `npm install` inside it) for the `node` container to pick up the new deps, since `npm install` only runs once at container start.
3. `docker compose up -d` to start the stack.
4. Edit files on the host. The `app` and `node` containers read through the bind mount `./:/workspace`.
5. Run tests and formatters inside `tools`. The first time `tools` starts on a fresh database, it runs `php artisan migrate --env=testing --force` automatically.
6. `docker compose down` when you're done.

## Cleaning up

```bash
# Stop and remove containers
docker compose down

# Remove the local image
docker image rm visittemajuk/app:local

# Clear build cache
docker builder prune

# Remove the MySQL volume (wipes the database)
docker volume rm visittemajuk-backend_db-data
```

## Troubleshooting

- **Port 3306 collides with a host MySQL.** Stop the host service, or change `ports: - "3306:3306"` to `"3307:3306"` in `docker-compose.yml`.
- **`APP_KEY` is empty in the container.** The entrypoint will run `key:generate` automatically. To force a regeneration, delete `APP_KEY` from `.env` and restart: `docker compose restart app`.
- **Storage permissions.** The Dockerfile runs `chown -R www-data:www-data storage bootstrap/cache` at build time, so a freshly built image has the right ownership baked in. If you delete and recreate only the container (without rebuilding), those directories keep their in-image permissions and writes still work. To run as your host UID instead, override the user at compose time: `docker compose run --user "$(id -u):$(id -g)" app bash`.
- **Composer cache not shared across image rebuilds.** The `composer-cache` volume mounts at `/tmp/composer` at container start, so within a long-lived container (or across `docker compose up` cycles of the same image) packages aren't re-downloaded. But every `docker compose build` re-runs `composer install` and downloads fresh, because named volumes don't attach during image build. To speed up rebuilds, keep lockfiles (`composer.lock`, `package-lock.json`) committed so the install is deterministic.
- **Can't pull images because of a proxy.** Set `HTTP_PROXY`/`HTTPS_PROXY` on each service in `docker-compose.yml`, or in `~/.docker/config.json`.

## Caveats

- This image is meant for dev and local CI. For production, use a FPM + Nginx image or a managed platform like Render / Fly.io that's already set up for PHP 8.3.
- The whole repo (including `storage/` and `bootstrap/cache/`) is bind-mounted from the host into `/workspace`, so file edits show up immediately. For a pure image build with no bind mount, the Dockerfile would need an extra `COPY` step for `storage/` (currently the bind mount covers it).
- **Dev-only credentials.** `docker-compose.yml` hardcodes `MYSQL_ROOT_PASSWORD: root`, `MYSQL_USER/PASSWORD: visittemajuk`, and passes `-proot` to the healthcheck. These are local-only and must never be reused in any deployed or shared environment. Generate real secrets before going anywhere near production.
