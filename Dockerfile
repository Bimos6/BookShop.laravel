FROM php:8.2-fpm

# 1. Установка системных зависимостей
RUN set -eux; \
    sed -i 's/deb.debian.org/mirror.yandex.ru/g' /etc/apt/sources.list.d/debian.sources; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        git curl unzip libzip-dev libpng-dev libjpeg-dev \
        libfreetype-dev zlib1g-dev libsqlite3-dev libonig-dev; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# 2. Установка Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm

# 3. Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Рабочая директория и структура папок
WORKDIR /var/www
RUN mkdir -p \
    storage/framework/{sessions,views,cache} \
    storage/logs \
    bootstrap/cache && \
    chown -R www-data:www-data /var/www && \
    chmod -R 775 storage bootstrap/cache

# 5. Копирование только файлов зависимостей (без .npmrc)
COPY composer.json composer.lock package.json package-lock.json ./

# 6. Установка зависимостей
RUN if [ -f package.json ]; then npm install; fi && \
    composer install --no-interaction --no-scripts --optimize-autoloader

# 7. Копирование остальных файлов
COPY . .

# 8. Настройка окружения Laravel
RUN if [ ! -f .env ]; then \
    cp .env.example .env && \
    chown www-data:www-data .env && \
    php artisan key:generate; \
    fi

# 9. Фиксация прав
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 storage bootstrap/cache && \
    touch database/database.sqlite && \
    chmod 664 database/database.sqlite

# 10. Сборка фронтенда (если нужно)
RUN if [ -f package.json ] && [ -f vite.config.js ]; then npm run build; fi

EXPOSE 9000
CMD ["php-fpm"]