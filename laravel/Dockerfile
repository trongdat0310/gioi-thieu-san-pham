# Use the official PHP 8.2-FPM base image
FROM php:8.2-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl dirmngr apt-transport-https lsb-release ca-certificates

# Add Node.js repository and install Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_16.x -o nodesource_setup.sh
RUN ["sh",  "./nodesource_setup.sh"]
RUN apt-get install -y nodejs && apt-get install -y nodejs npm
# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/laravel

# Copy the rest of the application code
COPY . .

# Expose port 9000 and start PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
# USER $user
