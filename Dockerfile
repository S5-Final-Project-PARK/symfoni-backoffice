# Use PHP-FPM
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy files
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port 9000 for PHP-FPM
EXPOSE 8080


# Start the Symfony server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]