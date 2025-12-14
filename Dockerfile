FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    curl \
    && docker-php-ext-install pdo_mysql mysqli zip \
    && docker-php-ext-enable pdo_mysql

# Install Composer (official & safe)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Cleanup
RUN apt-get clean && rm -rf /var/lib/apt/lists/*