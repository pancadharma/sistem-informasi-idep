FROM php:8.2-fpm

# Set working directory
WORKDIR /workspace

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    nano \
    vim \
    wget \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install additional tools
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

# Install Laravel installer globally
RUN composer global require laravel/installer

# Add Composer global bin to PATH
ENV PATH="${PATH}:/root/.composer/vendor/bin"

# Install Node.js LTS
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash - \
    && apt-get install -y nodejs

# Set PHP configuration for development
RUN echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini \
    && echo "upload_max_filesize=100M" >> /usr/local/etc/php/conf.d/docker-php-uploads.ini \
    && echo "post_max_size=100M" >> /usr/local/etc/php/conf.d/docker-php-uploads.ini

# Create a non-root user
RUN useradd -m -s /bin/bash vscode \
    && usermod -aG www-data vscode

# Set proper permissions
RUN chown -R vscode:vscode /workspace

USER vscode

# Expose port 8000 for Laravel development server
EXPOSE 8000

CMD ["php-fpm"]