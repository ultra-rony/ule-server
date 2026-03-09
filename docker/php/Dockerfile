FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    unzip \
 && docker-php-ext-install mysqli pdo pdo_mysql mbstring zip curl \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY php.ini /usr/local/etc/php/php.ini