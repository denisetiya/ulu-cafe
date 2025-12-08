#!/bin/sh
set -e

# Wait for database if needed
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database..."
    while ! nc -z "$DB_HOST" "${DB_PORT:-3306}" 2>/dev/null; do
        sleep 1
    done
    echo "Database is ready!"
fi

# Run migrations if AUTO_MIGRATE is set
if [ "$AUTO_MIGRATE" = "true" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

# Cache Laravel config for production
if [ "$APP_ENV" = "production" ]; then
    echo "Caching configuration..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
fi

# Run queue worker in background if QUEUE_WORKER is set
if [ "$QUEUE_WORKER" = "true" ]; then
    echo "Starting queue worker..."
    php artisan queue:work --daemon &
fi

# Execute the main command
exec "$@"
