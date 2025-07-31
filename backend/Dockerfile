# Stage 1: Build frontend assets using Node.js
FROM node:22-alpine AS node-builder

WORKDIR /app

# Copy only frontend-related files (optional improvement)
COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# Stage 2: PHP app with Composer and extensions
FROM php:8.3-fpm-alpine3.19

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    unzip \
    autoconf \
    g++ \
    make \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Optional: Copy built assets (e.g., /public or /dist)
COPY --from=node-builder /app/public /var/www/html/public

COPY . /var/www/html

WORKDIR /var/www/html

# Install PHP dependencies
RUN composer install \
    && php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear \
    && php artisan key:generate \
    && php artisan optimize \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan migrate --force

# Set permissions (adjust as necessary)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]