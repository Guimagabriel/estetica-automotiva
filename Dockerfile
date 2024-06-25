FROM php:8.2-fpm-alpine

WORKDIR /app

COPY composer.phar /usr/local/bin/composer

RUN apk add --no-cache \
    curl \
    git \
    libxml2-dev \
    libpq-dev \
    openssl \
    postgresql-dev

RUN docker-php-ext-install bcmath gd json mysqli pdo_pgsql mbstring sockets xml zip

COPY . .

RUN composer install

EXPOSE 80
