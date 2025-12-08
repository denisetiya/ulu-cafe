#!/bin/sh
set -e

# Wait for database if needed (for external databases)
if [ -n "$DB_HOST" ]; then
    echo "Waiting for database..."
    while ! nc -z "$DB_HOST" "${DB_PORT:-3306}" 2>/dev/null; do
        sleep 1
    done
    echo "Database is ready!"
fi

# Create SQLite database if it doesn't exist
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    if [ ! -f /app/database/database.sqlite ]; then
        echo "Creating SQLite database..."
        touch /app/database/database.sqlite
        chown www-data:www-data /app/database/database.sqlite
    fi
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
    # Only start queue worker if cache table exists
    if php artisan tinker --execute="try { DB::table('cache')->count(); echo 'ok'; } catch (Exception \$e) { echo 'no'; }" 2>/dev/null | grep -q 'ok'; then
        echo "Starting queue worker..."
        php artisan queue:work --daemon &
    else
        echo "⚠️ Queue worker skipped: cache table not found. Run migrations first."
    fi
fi

# Execute the main command
exec "$@"
