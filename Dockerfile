FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libicu-dev libzip-dev curl \
    && docker-php-ext-install pdo pdo_pgsql intl zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
