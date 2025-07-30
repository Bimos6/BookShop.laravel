FROM php:8.2-fpm

# 1. Установка системных зависимостей + Node.js
RUN set -eux; \
    sed -i 's/deb.debian.org/mirror.yandex.ru/g' /etc/apt/sources.list.d/debian.sources; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        git curl unzip libzip-dev libpng-dev libjpeg-dev \
        libfreetype-dev zlib1g-dev libsqlite3-dev libonig-dev; \
    apt-get clean; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Установка Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm

# 2. Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Рабочая директория
WORKDIR /var/www

# 4. Копируем ВСЕ файлы проекта (включая package.json)
COPY . .

# 5. Установка Node.js зависимостей и сборка фронтенда
RUN npm install && npm run build

# 6. Создаем .env если отсутствует
RUN if [ ! -f .env ]; then \
    cp .env.example .env && \
    chown www-data:www-data .env && \
    chmod 644 .env; \
fi

# 7. Установка PHP зависимостей
RUN composer install --no-interaction --no-scripts --optimize-autoloader

# 8. Генерация ключа приложения
RUN php artisan key:generate

# 9. Настройка прав
RUN chown -R www-data:www-data storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache \
    && touch database/database.sqlite \
    && chmod 664 database/database.sqlite

# 10. Оптимизация Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 9000
CMD ["php-fpm"]