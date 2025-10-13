#!/bin/sh

echo "==> Starting Laravel Application Initialization"

# Wait for MySQL
if [ "$DB_CONNECTION" = "mysql" ] && [ -n "$DB_HOST" ]; then
    echo "==> Waiting for MySQL..."
    timeout=60
    while ! nc -z "$DB_HOST" 3306; do
        sleep 1
        timeout=$((timeout - 1))
        if [ $timeout -eq 0 ]; then
            echo "ERROR: Timeout waiting for MySQL"
            exit 1
        fi
    done
    echo "==> MySQL is ready!"
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

# Clear any cached config first
php artisan config:clear

# Check if force clean is requested
if [ "$FORCE_CLEAN_DB" = "true" ]; then
    echo "⚠ FORCE_CLEAN_DB enabled - dropping all tables..."
    php artisan db:wipe --force 2>&1 || {
        echo "ERROR: Failed to wipe database."
        exit 1
    }
    echo "✓ Database cleaned"
fi

# Run migrations (without transactions due to Neon pooling)
echo "==> Running migrations..."
php artisan migrate --force --no-interaction 2>&1 || {
    echo "ERROR: Migration failed"
    php artisan migrate:status 2>&1 || true
    echo "==> Showing last Laravel log..."
    tail -n 50 storage/logs/laravel.log 2>/dev/null || echo "No log file found"
    exit 1
}

echo "✓ Migrations completed!"
php artisan migrate:status

# Install Passport clients (skip - handled manually due to Passport 13 breaking changes)
# echo "==> Installing Passport clients..."
# php artisan passport:install --force 2>&1 || {
#     echo "WARNING: Passport install failed (may already exist)"
# }

# Run seeders for initial data (includes Passport client creation)
echo "==> Running database seeders..."
php artisan db:seed --force 2>&1 || {
    echo "WARNING: Seeders failed"
    echo "==> Showing error details..."
    tail -n 20 storage/logs/laravel.log 2>/dev/null || echo "No log file found"
}

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
