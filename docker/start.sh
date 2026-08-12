#!/bin/sh
set -e

echo "==> Starting TaskFlow deployment bootstrap..."

# Create supervisor log directory
mkdir -p /var/log/supervisor

# Run Laravel production bootstrap
echo "==> Caching config, routes, views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Running database migrations..."
php artisan migrate --force

echo "==> Installing Passport keys and client..."
php artisan passport:keys --force 2>/dev/null || true

# Create personal access client only if none exists
php artisan passport:client --personal --no-interaction 2>/dev/null || true

echo "==> Linking storage..."
php artisan storage:link 2>/dev/null || true

echo "==> Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

echo "==> Starting supervisord (nginx + php-fpm + scheduler)..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
