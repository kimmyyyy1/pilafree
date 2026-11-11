FROM php:8.2-cli-alpine

# Install essential system packages, sqlite, and build tools
RUN apk add --no-cache \
    curl \
    git \
    nodejs \
    npm \
    sqlite \
    sqlite-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip

# Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo pdo_sqlite bcmath

# Install Composer from official image
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy all application files
COPY . .

# Install PHP production dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install npm dependencies and build Vite frontend assets
RUN npm install && npm run build

# Copy entrypoint script and make executable
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENV PORT=10000
EXPOSE 10000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
