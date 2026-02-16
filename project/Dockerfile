FROM php:8.3-fpm

# Set working directory inside container
WORKDIR /var/www/html

# Install system dependencies & Node.js
# KITA MENAMBAHKAN 'libzip-dev' DI SINI
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    libzip-dev \
    zip unzip nano \
    default-mysql-client \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip 
    

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Setup permissions for www-data user
RUN chown -R www-data:www-data /var/www/html

# Allow git to operate on mounted directory owned by different user
RUN git config --global --add safe.directory /var/www/html

RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini
RUN echo "upload_max_filesize=100M" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "post_max_size=100M" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/99-memory.ini

EXPOSE 9000
CMD ["php-fpm"]