#!/bin/sh

# Clean database script - USE WITH CAUTION
# This will drop ALL tables in the database

echo "==> Database Cleanup Script"
echo "WARNING: This will drop all tables!"
echo ""

if [ "$APP_ENV" = "production" ]; then
    echo "ERROR: Cannot run cleanup in production!"
    exit 1
fi

echo "==> Dropping all tables..."

php artisan tinker --execute="
    \$tables = DB::select('SELECT tablename FROM pg_tables WHERE schemaname = \'public\'');
    DB::statement('SET session_replication_role = \'replica\';');
    foreach (\$tables as \$table) {
        echo 'Dropping table: ' . \$table->tablename . PHP_EOL;
        DB::statement('DROP TABLE IF EXISTS \"' . \$table->tablename . '\" CASCADE');
    }
    DB::statement('SET session_replication_role = \'origin\';');
    echo 'All tables dropped successfully!' . PHP_EOL;
" || {
    echo "ERROR: Failed to drop tables"
    exit 1
}

echo "==> Running fresh migrations..."
php artisan migrate --force

echo "==> Running seeders..."
php artisan db:seed --force

echo "==> Cleanup completed!"
