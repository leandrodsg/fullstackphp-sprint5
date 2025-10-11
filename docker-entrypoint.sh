#!/bin/sh

echo "Starting Laravel application initialization..."
echo "=== DEBUG ENVIRONMENT ==="
echo "Current directory: $(pwd)"
echo "Files in current directory:"
ls -la
echo "=== ENV FILE CONTENT ==="
if [ -f ".env" ]; then
    echo ".env file exists:"
    head -10 .env
else
    echo ".env file NOT found!"
fi
echo "=== ENVIRONMENT VARIABLES ==="
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_HOST: $DB_HOST" 
echo "DB_DATABASE: $DB_DATABASE"
echo "DB_USERNAME: $DB_USERNAME"
echo "DATABASE_URL: $DATABASE_URL"
echo "=========================="

# Check if we're using PostgreSQL and wait for it
if [ "$DB_CONNECTION" = "pgsql" ] && [ -n "$DB_HOST" ]; then
    echo "Waiting for PostgreSQL..."
    echo "Testing connection to $DB_HOST:5432..."
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
    
    # Test database connection
    echo "Testing database connection..."
    php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection successful!';"
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
echo "=== LARAVEL CONFIG DEBUG ==="
php artisan config:show database.default
php artisan config:show database.connections.pgsql.host
echo "=========================="
php artisan migrate:fresh --force

# Check if migrations succeeded
if [ $? -eq 0 ]; then
    echo "Migrations completed successfully!"
else
    echo "Migrations failed!"
    exit 1
fi

# Run seeders if needed
echo "Running seeders for initial data..."
php artisan db:seed --force

echo "Database setup completed!"

# Clear any existing cache...
echo "Clearing existing cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Fix Vite manifest issue if needed
echo "Checking Vite manifest..."
if [ -f "public/build/.vite/manifest.json" ] && [ ! -f "public/build/manifest.json" ]; then
    echo "Copying Vite manifest to correct location..."
    cp public/build/.vite/manifest.json public/build/manifest.json
    echo "Manifest copied successfully!"
fi

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