# Use the official PHP image with Apache
FROM php:8.1-apache

# Install system dependencies and PHP extensions required for Symfony
RUN apt-get update && apt-get install -y \
    libicu-dev \
    git \
    unzip \
    && docker-php-ext-install intl pdo pdo_mysql opcache

# Enable mod_rewrite for Apache
RUN a2enmod rewrite

# Set the working directory in the container
WORKDIR /var/www/html

# Copy the local Symfony project into the container
COPY . .

# Install Composer (Symfony's package manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony dependencies (including Nelmio CORS)
# RUN composer install --no-interaction --optimize-autoloader

# Expose the port that the Apache server will listen on
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]
