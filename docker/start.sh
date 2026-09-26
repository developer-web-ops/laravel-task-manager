#!/bin/sh
set -e

echo "==> Starting Laravel deployment bootstrap..."

# Required production secrets
: "${PASSPORT_PRIVATE_KEY:?PASSPORT_PRIVATE_KEY is required}"
: "${PASSPORT_PUBLIC_KEY:?PASSPORT_PUBLIC_KEY is required}"

# Create Supervisor log directory
mkdir -p /var/log/supervisor

echo "==> Caching config and views..."
php artisan config:cache
php artisan view:cache

echo "==> Running database migrations..."
php artisan migrate --force

echo "==> Ensuring Passport personal access client exists..."

if ! php artisan tinker --execute="
    exit(
        \DB::table('oauth_clients')
            ->where('grant_types', 'like', '%personal_access%')
            ->exists()
        ? 0
        : 1
    );
"; then
    php artisan passport:client \
        --personal \
        --name="TaskManager" \
        --no-interaction
fi

echo "==> Linking Laravel storage..."
php artisan storage:link 2>/dev/null || true

echo "==> Setting permissions..."
chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache

echo "==> Starting Supervisor..."
exec /usr/bin/supervisord \
    -c /etc/supervisor/conf.d/supervisord.conf