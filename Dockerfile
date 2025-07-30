# Базовый образ с PHP-FPM 8.2
FROM php:8.2-fpm

# 1. Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype-dev \
    zlib1g-dev \
    libwebp-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install \
        pdo_sqlite \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache \
        intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Рабочая директория
WORKDIR /var/www

# 4. Копируем сначала только файлы, необходимые для установки зависимостей
COPY composer.json composer.lock ./

# 5. Установка зависимостей Composer
RUN composer install --no-dev --no-interaction --optimize-autoloader

# 6. Копируем остальные файлы проекта
COPY . .

# 7. Настройка прав
RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache \
    && chmod -R 775 \
    storage \
    bootstrap/cache

# 8. Оптимизация Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 9000
CMD ["php-fpm"]