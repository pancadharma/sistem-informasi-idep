# FROM dunglas/frankenphp:1.1-php8.3 
# switch to alpine linux
FROM dunglas/frankenphp:1.1-php8.3-alpine

# Set working directory inside container
WORKDIR /var/www/html

# Install system dependencies & Node.js
RUN apk add --no-cache \
    bash \
    git \
    curl \
    unzip \
    icu-dev \
    oniguruma-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    mysql-client \
    mariadb-client \
    nodejs \
    npm \
    nano

# Install PHP extensions
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        intl \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
    && apk del .build-deps

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

# Serving from public directory

EXPOSE 80 443 9000

CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]