FROM php:8.2-fpm

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    nginx \
    && docker-php-ext-install zip pdo pdo_pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . /app

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Ensure necessary directories exist
RUN mkdir -p /var/log/nginx /var/cache/nginx /run/php

# Copy Nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Expose the application's port
EXPOSE 80

# Start services
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
