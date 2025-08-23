FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev libpq-dev \
 && docker-php-ext-configure gd --with-jpeg \
 && docker-php-ext-install -j$(nproc) gd intl zip pdo_pgsql \
 && rm -rf /var/lib/apt/lists/*

RUN pecl install redis && docker-php-ext-enable redis

WORKDIR /var/www/html

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer