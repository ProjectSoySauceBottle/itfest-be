#!/bin/sh
set -e

# The container image is read-only at runtime; only /tmp is writable.
mkdir -p /tmp/storage/framework/cache/data
mkdir -p /tmp/storage/framework/sessions
mkdir -p /tmp/storage/framework/views
mkdir -p /tmp/storage/logs
mkdir -p /tmp/storage/app/public
mkdir -p /tmp/bootstrap-cache

rm -rf /var/www/html/storage/framework/cache/data \
       /var/www/html/storage/framework/sessions \
       /var/www/html/storage/framework/views \
       /var/www/html/storage/logs \
       /var/www/html/storage/app/public \
       /var/www/html/bootstrap/cache
ln -sfn /tmp/storage/framework/cache/data   /var/www/html/storage/framework/cache/data
ln -sfn /tmp/storage/framework/sessions     /var/www/html/storage/framework/sessions
ln -sfn /tmp/storage/framework/views        /var/www/html/storage/framework/views
ln -sfn /tmp/storage/logs                   /var/www/html/storage/logs
ln -sfn /tmp/storage/app/public             /var/www/html/storage/app/public
ln -sfn /tmp/bootstrap-cache                /var/www/html/bootstrap/cache

# php-fpm runs as www-data, so everything under these trees must be writable by
# it. A failed chown (containers that refuse it) must not break boot: the chmod
# is a fallback, and both are best-effort on purpose.
chown -R www-data:www-data /tmp/storage /tmp/bootstrap-cache 2>/dev/null || true
chmod -R 0777 /tmp/storage /tmp/bootstrap-cache

cd /var/www/html

# Warm the caches AS www-data. Running them as root creates root-owned files
# that php-fpm then cannot lock, which 500s every request with
# "fopen(...): Permission denied".
as_wwwdata() {
    if command -v su-exec >/dev/null 2>&1; then
        su-exec www-data "$@"
    else
        "$@"
    fi
}

as_wwwdata php artisan config:cache || echo "config:cache failed, continuing"
as_wwwdata php artisan route:cache  || echo "route:cache failed, continuing"
chmod -R 0777 /tmp/storage /tmp/bootstrap-cache

# Migrations are opt-in: concurrent instances racing the same migration is a
# real failure mode. Run them from a one-off command instead.
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    as_wwwdata php artisan migrate --force || echo "migrate failed, continuing"
    chmod -R 0777 /tmp/storage /tmp/bootstrap-cache
fi

exec "$@"
