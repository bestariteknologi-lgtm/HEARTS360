# Menggunakan PHP 8.2 CLI sebagai base (cocok untuk skrip integrasi/cron)
FROM php:8.2-cli-alpine

# Instal dependensi sistem dan ekstensi PHP yang dibutuhkan
RUN apk add --no-cache \
    libzip-dev \
    zip \
    bash \
    openssh-client \
    postgresql-dev \
    && docker-php-ext-install zip bcmath pdo_mysql pdo_pgsql

# Instal Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy kode project
COPY . .

# Instal dependensi Laravel
RUN composer install --no-dev --optimize-autoloader

# Set izin folder storage
RUN chmod -R 777 storage bootstrap/cache

# Jalankan perintah awal
CMD ["php", "artisan", "hearts360:sync"]
