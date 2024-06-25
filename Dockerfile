FROM php:8.2-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    openssl \
    nano \
    libpq-dev \
    libz-dev \
    libjpeg-dev \
    libpng12-dev \
    libfreetype6-dev

RUN docker-php-ext-install -o /usr/local/lib/php/extensions pdo_pgsql gd

WORKDIR /app

COPY composer.phar /usr/local/bin/composer

RUN composer install

COPY . .