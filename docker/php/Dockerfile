FROM php:7.4-fpm

# Зависимости + расширения
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    unzip \
 && docker-php-ext-install mysqli pdo pdo_mysql mbstring zip \
 && apt-get clean \
 && rm -rf /var/lib/apt/lists/*

# Копируем php.ini (если нужен именно из build-контекста)
COPY php.ini /usr/local/etc/php/php.ini
