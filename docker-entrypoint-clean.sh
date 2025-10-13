#!/bin/sh

echo "==> Starting Laravel Application Initialization"

# Wait for PostgreSQL
if [ "$DB_CONNECTION" = "pgsql" ] && [ -n "$DB_HOST" ]; then
    echo "==> Waiting for PostgreSQL..."
    timeout=60
    while ! nc -z "$DB_HOST" 5432; do
        sleep 1
        timeout=$((timeout - 1))
        if [ $timeout -eq 0 ]; then
            echo "ERROR: Timeout waiting for PostgreSQL"
            exit 1
        fi
    done
    echo "==> PostgreSQL is ready!"
fi

# Generate APP_KEY if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "==> Generating application key..."
    php artisan key:generate --force
fi

# Setup Passport keys from environment
echo "==> Setting up Passport keys..."
if [ -n "$PASSPORT_PRIVATE_KEY" ] && [ -n "$PASSPORT_PUBLIC_KEY" ]; then
    echo "$PASSPORT_PRIVATE_KEY" | base64 -d > storage/oauth-private.key 2>/dev/null || {
        echo "WARNING: Failed to decode PASSPORT_PRIVATE_KEY, generating new keys..."
        php artisan passport:keys --force
    }
    echo "$PASSPORT_PUBLIC_KEY" | base64 -d > storage/oauth-public.key 2>/dev/null || {
        echo "WARNING: Failed to decode PASSPORT_PUBLIC_KEY, generating new keys..."
        php artisan passport:keys --force
    }
    chmod 600 storage/oauth-private.key storage/oauth-public.key 2>/dev/null
else
    if [ ! -f "storage/oauth-private.key" ] || [ ! -f "storage/oauth-public.key" ]; then
        echo "==> Generating new Passport keys..."
        php artisan passport:keys --force
    fi
    chmod 600 storage/oauth-private.key storage/oauth-public.key 2>/dev/null
fi

# Run migrations with error handling
echo "==> Running database migrations..."
php artisan migrate:status 2>&1 | grep -q "Migration table not found" && {
    echo "==> Creating migrations table..."
}

# Try to run migrations
if ! php artisan migrate --force 2>&1; then
    echo "WARNING: Migration failed, attempting to reset..."
    # Try fresh migration only in development/staging
    if [ "$APP_ENV" != "production" ]; then
        php artisan migrate:fresh --force --seed 2>&1 || {
            echo "ERROR: Fresh migration also failed"
            exit 1
        }
    else
        echo "ERROR: Migration failed in production"
        exit 1
    fi
fi

echo "==> Migrations completed successfully!"

# Cache optimization
echo "==> Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "==> Setting permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chmod 600 storage/oauth-private.key storage/oauth-public.key 2>/dev/null || true

echo "==> Initialization completed!"

# Execute command or start supervisor
if [ "$1" = "init" ]; then
    exit 0
else
    exec "$@"
fi
