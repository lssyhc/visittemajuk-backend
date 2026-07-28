#!/usr/bin/env bash
# Container entrypoint: wait for MySQL, set up .env, run the requested command.
# Bash reference: https://www.gnu.org/software/bash/manual/

set -euo pipefail

wait_for_db() {
    if [[ -z "${DB_HOST:-}" ]]; then
        return 0
    fi

    local host="${DB_HOST}"
    local port="${DB_PORT:-3306}"
    local user="${DB_USERNAME:-root}"
    local pass="${DB_PASSWORD:-}"
    local attempts=30
    local delay=2

    echo "[entrypoint] waiting for MySQL at ${host}:${port} ..."
    for ((i = 1; i <= attempts; i++)); do
        if mysqladmin ping -h "${host}" -P "${port}" -u "${user}" -p"${pass}" --silent >/dev/null 2>&1; then
            echo "[entrypoint] MySQL is up."
            return 0
        fi
        sleep "${delay}"
    done

    echo "[entrypoint] MySQL didn't come up after ${attempts} tries." >&2
    return 1
}

prepare_app() {
    if [[ ! -f .env && -f .env.example ]]; then
        echo "[entrypoint] no .env, copying from .env.example ..."
        cp .env.example .env
    fi

    if [[ -z "${APP_KEY:-}" && -f .env ]]; then
        if ! grep -q "^APP_KEY=base64:" .env; then
            echo "[entrypoint] APP_KEY missing, running key:generate ..."
            php artisan key:generate --ansi || true
        fi
    fi

    if [[ -d public ]]; then
        php artisan storage:link >/dev/null 2>&1 || true
    fi
}

wait_for_db
prepare_app

exec "$@"
