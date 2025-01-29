FROM php:8.2-cli

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libzip-dev \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install zip pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /app

# Copy application files
COPY . /app/.

# Ensure necessary directories exist
RUN mkdir -p /var/log/nginx && mkdir -p /var/cache/nginx

# Install dependencies
#RUN composer install --ignore-platform-reqs

RUN composer require symfony/serializer

RUN composer require api


# Set the port Symfony will use
ENV PORT=8000

# Expose the application's port
EXPOSE 8000

# Start the Symfony server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
# CMD ["symfony", "server:start", "--no-tls", "--port=8080"]
