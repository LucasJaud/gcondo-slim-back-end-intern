# Yes, this image contains high vulnerabilities ☠️
# https://github.com/docker-library/php/issues/1571
FROM php:8.4-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    # And PHP extensions
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath zip

# Install Composer
COPY --from=composer:2.8.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www