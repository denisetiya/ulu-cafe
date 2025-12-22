#!/bin/sh
set -e

echo "🚀 Starting Ulu-Cafe..."

# Create SQLite database if it doesn't exist
if [ "$DB_CONNECTION" = "sqlite" ] || [ -z "$DB_CONNECTION" ]; then
    if [ ! -f /app/database/database.sqlite ]; then
        echo "📦 Creating SQLite database..."
        touch /app/database/database.sqlite
        chown www-data:www-data /app/database/database.sqlite
    fi
fi

# Wait for database if using external database (MySQL/PostgreSQL)
if [ -n "$DB_HOST" ]; then
    echo "⏳ Waiting for database at $DB_HOST:${DB_PORT:-3306}..."
    timeout=30
    counter=0
    while ! nc -z "$DB_HOST" "${DB_PORT:-3306}" 2>/dev/null; do
        counter=$((counter + 1))
        if [ $counter -ge $timeout ]; then
            echo "❌ Database connection timeout!"
            exit 1
        fi
        sleep 1
    done
    echo "✅ Database is ready!"
fi

# Run migrations if AUTO_MIGRATE is set
if [ "$AUTO_MIGRATE" = "true" ]; then
    echo "🗄️ Running migrations..."
    php artisan migrate --force || {
        echo "❌ Migration failed!"
        exit 1
    }
    echo "✅ Migrations complete!"
fi

# Create storage symlink (required for uploaded files to be accessible)
echo "🔗 Creating storage symlink..."
php artisan storage:link --force || true

# Cache Laravel config for production (skip if migration failed)
if [ "$APP_ENV" = "production" ]; then
    echo "⚙️ Caching configuration..."
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Start queue worker in background if QUEUE_WORKER is set
if [ "$QUEUE_WORKER" = "true" ]; then
    echo "👷 Starting queue worker in background..."
    # Loop to auto-restart worker after processing 1000 jobs (prevents memory leaks)
    (
        while true; do
            php artisan queue:work --sleep=3 --tries=3 --max-jobs=1000
            echo "🔄 Queue worker restarting..."
            sleep 5
        done
    ) &
fi

echo "✅ Application ready!"

# Execute the main command (FrankenPHP)
exec "$@"
