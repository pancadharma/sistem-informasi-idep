FROM php:8.3-fpm

# Set working directory inside container
WORKDIR /var/www/html

# Install system dependencies & Node.js
# KITA MENAMBAHKAN 'libzip-dev' DI SINI
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    libzip-dev \
    zip unzip nano \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
# KITA MENAMBAHKAN 'zip' DI SINI
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Setup permissions for www-data user
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
CMD ["php-fpm"]