#!/usr/bin/env sh
set -eu

render_port="${PORT:-10000}"

sed -ri "s/Listen 80/Listen ${render_port}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:10000>/<VirtualHost *:${render_port}>/" /etc/apache2/sites-available/000-default.conf

mkdir -p \
    storage/app/private \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache

php artisan config:clear
php artisan migrate --force --no-interaction
php artisan storage:link --force
php artisan config:cache

exec "$@"

