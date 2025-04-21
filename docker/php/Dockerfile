FROM php:8.3-fpm-alpine

# Update and upgrade the Alpine packages
RUN apk update && apk upgrade --no-cache

# Install required dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    libxpm-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    npm \
    nodejs \
    oniguruma-dev \
    bash \
    build-base \
    autoconf \
    g++ \
    make

# Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-xpm && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Copy Composer from the official Composer image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www

RUN mkdir -p /var/www/storage /var/www/bootstrap/cache && \
    chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache
