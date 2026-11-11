#!/bin/sh
set -e

# Ensure storage, cache, and database directories exist with write permissions
mkdir -p /var/www/html/storage/framework/cache/data
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache
mkdir -p /var/www/html/database

touch /var/www/html/database/database.sqlite
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Check if .env exists, if not copy from .env.example
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Ensure APP_KEY exists
php artisan key:generate --force

# Run database migrations and seeders
php artisan migrate --force
php artisan db:seed --force

PORT="${PORT:-10000}"
echo "=================================================="
echo " PilaFree PH is now starting on port: $PORT"
echo "=================================================="

exec php artisan serve --host=0.0.0.0 --port="$PORT"
