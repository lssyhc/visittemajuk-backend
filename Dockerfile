# syntax=docker/dockerfile:1.7
# Base image: official PHP 8.3 CLI on Debian Bookworm.
#   https://hub.docker.com/_/php
#   https://github.com/docker-library/docs/blob/master/php/README.md
FROM php:8.3-cli-bookworm AS base

LABEL org.opencontainers.image.title="visittemajuk-backend" \
      org.opencontainers.image.description="Visit Temajuk Laravel 13 API toolchain (PHP, Composer, Node, PHPStan, Pint, Husky)" \
      org.opencontainers.image.source="https://github.com/lssyhc/visittemajuk-backend"

ENV DEBIAN_FRONTEND=noninteractive \
    LANG=C.UTF-8 \
    LC_ALL=C.UTF-8 \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/tmp/composer \
    COMPOSER_NO_INTERACTION=1 \
    COMPOSER_MEMORY_LIMIT=-1

# System packages the PHP extensions and Node tooling need.
# Recipe follows the official php image: https://github.com/docker-library/php/blob/master/8.3/bookworm/cli/Dockerfile
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        git \
        gnupg \
        unzip \
        zip \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        libxml2-dev \
        libssl-dev \
        libcurl4-openssl-dev \
        libpng-dev \
        libjpeg-dev \
        libwebp-dev \
        libfreetype6-dev \
        default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions Laravel 13 and PHPStan need.
RUN docker-php-ext-install -j"$(nproc)" \
        bcmath \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo_mysql \
        zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" gd

# Composer 2 from the official image.
#   https://hub.docker.com/_/composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Node.js 24 + npm from the official image.
#   https://hub.docker.com/_/node
COPY --from=node:24-bookworm-slim /usr/local/bin/node /usr/local/bin/node
COPY --from=node:24-bookworm-slim /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -s /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx \
    && npm --version \
    && node --version

WORKDIR /workspace

# Copy lockfiles first so this layer is cached when source changes.
COPY composer.json composer.lock ./
RUN composer install --no-interaction --no-progress --prefer-dist --no-scripts

COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund

COPY . .

# Now install with scripts, since vendor and node_modules are in place.
RUN composer install --no-interaction --no-progress --prefer-dist \
    && npm run prepare || true

RUN mkdir -p storage/framework/{cache,sessions,views} \
    && chown -R www-data:www-data storage bootstrap/cache || true

# Copy the entrypoint explicitly and ensure it is executable.
# COPY . . alone preserves the git +x bit, which is fragile; this makes it deterministic.
COPY docker/entrypoint.sh ./docker/entrypoint.sh
RUN chmod +x ./docker/entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["docker/entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
