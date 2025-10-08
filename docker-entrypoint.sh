#!/bin/sh

echo "Starting Laravel application initialization..."

# Check if we're using PostgreSQL and wait for it
if [ "$DB_CONNECTION" = "pgsql" ] && [ -n "$DB_HOST" ]; then
    echo "Waiting for PostgreSQL..."
    timeout=60
    while ! nc -z "$DB_HOST" 5432; do
        sleep 1
        timeout=$((timeout - 1))
        if [ $timeout -eq 0 ]; then
            echo "Timeout waiting for PostgreSQL"
            exit 1
        fi
    done
    echo "PostgreSQL is ready!"
fi

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Setup Passport keys
echo "Setting up Passport keys..."
if [ -n "$PASSPORT_PRIVATE_KEY" ] && [ -n "$PASSPORT_PUBLIC_KEY" ]; then
    echo "Setting up Passport keys from environment variables..."
    echo "$PASSPORT_PRIVATE_KEY" | base64 -d > storage/oauth-private.key
    echo "$PASSPORT_PUBLIC_KEY" | base64 -d > storage/oauth-public.key
    chmod 600 storage/oauth-private.key
    chmod 600 storage/oauth-public.key
    echo "Passport keys configured successfully!"
else
    echo "Environment variables not found. Checking for existing keys..."
    if [ ! -f "storage/oauth-private.key" ] || [ ! -f "storage/oauth-public.key" ]; then
        echo "Generating new Passport keys..."
        php artisan passport:keys --force
        chmod 600 storage/oauth-private.key
        chmod 600 storage/oauth-public.key
    else
        echo "Using existing Passport keys..."
        chmod 600 storage/oauth-private.key
        chmod 600 storage/oauth-public.key
    fi
fi

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Run seeders if development environment or if FORCE_SEED is set
if [ "$APP_ENV" = "local" ] || [ "$FORCE_SEED" = "true" ]; then
    echo "Running seeders..."
    php artisan db:seed --force
fi

# Clear any existing cache
echo "Clearing existing cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache configurations for better performance
echo "Generating optimized cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader (only if composer is available)
if command -v composer >/dev/null 2>&1; then
    echo "Optimizing autoloader..."
    composer dump-autoload --optimize --no-dev
else
    echo "Composer not available, skipping autoloader optimization..."
fi

# Set proper permissions for storage and cache
echo "Setting proper permissions..."
chmod -R 775 storage/app storage/framework storage/logs bootstrap/cache
chmod 755 storage

# Ensure Passport keys have secure permissions (must be after general permissions)
if [ -f "storage/oauth-private.key" ] && [ -f "storage/oauth-public.key" ]; then
    echo "Securing Passport key permissions..."
    chmod 600 storage/oauth-private.key
    chmod 600 storage/oauth-public.key
fi

echo "Laravel application initialization completed!"

# If running as init script, exit successfully
# Otherwise, execute command passed as argument
if [ "$1" = "init" ]; then
    exit 0
else
    exec "$@"
fi