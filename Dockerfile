FROM php:8.2-apache

# 1. Установка системных зависимостей
RUN set -eux; \
    sed -i 's/deb.debian.org/mirror.yandex.ru/g' /etc/apt/sources.list.d/debian.sources; \
    apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd dom xml

# Устанавливаем Node.js 20.x
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Настраиваем Apache
RUN a2enmod rewrite
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Создаем рабочую директорию
WORKDIR /var/www/html

# Копируем файлы проекта
COPY . .

# Устанавливаем права
RUN chown -R www-data:www-data /var/www/html \
    && mkdir -p bootstrap/cache storage/framework/views storage/framework/cache storage/framework/sessions storage/logs \
    && chmod -R 775 bootstrap/cache storage \
    && chmod -R 777 storage/framework/views

# Устанавливаем зависимости PHP и Node.js
RUN composer install --no-interaction --optimize-autoloader --no-dev \
    && npm install \
    && npm run build

# Копируем .env.example в .env и генерируем ключ
RUN cp .env.example .env \
    && php artisan key:generate \
    && php artisan storage:link

# Если используется SQLite, создаем файл базы данных
RUN touch database/database.sqlite \
    && chmod 666 database/database.sqlite \
    && php artisan migrate --force

# Порт, который будет слушать Apache
EXPOSE 80

RUN chmod 664 /var/www/html/database/database.sqlite && \
    chown www-data:www-data /var/www/html/database/database.sqlite

# Команда для запуска Apache
CMD ["apache2-foreground"]