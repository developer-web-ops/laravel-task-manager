#!/bin/sh
set -e

echo "==> Starting Laravel deployment bootstrap..."

# Create Supervisor log directory
mkdir -p /var/log/supervisor

# Cache Laravel configuration, routes, and views
echo "==> Caching config, routes, views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "==> Running database migrations..."
php artisan migrate --force

# Install Laravel Passport keys
echo "==> Installing Passport keys and client..."
php artisan passport:keys --force 2>/dev/null || true

# Create personal access client if needed
php artisan passport:client --personal --no-interaction 2>/dev/null || true

# Link Laravel storage
echo "==> Linking storage..."
php artisan storage:link 2>/dev/null || true

# Set permissions
echo "==> Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

echo "==> Starting Supervisor (Nginx + PHP-FPM + Laravel scheduler)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
