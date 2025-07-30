FROM php:8.2-fpm

# 1. Установка системных зависимостей
RUN set -eux; \
    sed -i 's/deb.debian.org/mirror.yandex.ru/g' /etc/apt/sources.list.d/debian.sources; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        git curl unzip libzip-dev libpng-dev libjpeg-dev \
        libfreetype-dev zlib1g-dev libsqlite3-dev libonig-dev; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd zip opcache; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# 2. Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Рабочая директория
WORKDIR /var/www

# 4. Копируем ВСЕ файлы проекта (включая .env.example)
COPY . .

# 5. Создаем .env если отсутствует (теперь .env.example точно есть)
RUN if [ ! -f .env ]; then \
    cp .env.example .env && \
    chown www-data:www-data .env && \
    chmod 644 .env; \
fi

# 6. Установка зависимостей Composer
RUN composer install --no-interaction --no-scripts --optimize-autoloader

# 7. Генерация ключа приложения
RUN php artisan key:generate

# 8. Настройка прав
RUN chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache \
    && touch database/database.sqlite \
    && chmod 666 database/database.sqlite

# 9. Оптимизация Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 9000
CMD ["php-fpm"]