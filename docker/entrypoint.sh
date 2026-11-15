#!/bin/sh
set -eu

cd /var/www/html

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

if [ -z "${APP_KEY:-}" ]; then
    export APP_KEY="$(php artisan key:generate --show)"
fi

php artisan config:clear
php artisan route:clear
php artisan view:clear

php artisan migrate --force
# Seed the demo catalogue (categories, sample products, gallery, settings and admin).
# The seeder uses firstOrCreate, so restarts do not duplicate existing records.
php artisan db:seed --force

php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
