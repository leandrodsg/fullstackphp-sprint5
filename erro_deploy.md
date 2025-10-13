==> Deploying...
Starting Laravel application initialization...
=== DEBUG ENVIRONMENT ===
Current directory: /var/www/html
Files in current directory:
total 640
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:48 .
drwxr-xr-x    1 root     root          4096 Oct 11 18:00 ..
-rwxr-xr-x    1 www-data www-data       898 Oct 11 19:46 .dockerignore
-rwxr-xr-x    1 www-data www-data       258 Oct 11 19:46 .editorconfig
-rwxr-xr-x    1 www-data www-data      1256 Oct 11 19:46 .env
-rwxr-xr-x    1 www-data www-data      1256 Oct 11 19:46 .env.docker
-rwxr-xr-x    1 www-data www-data       186 Oct 11 19:46 .gitattributes
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 .github
-rwxr-xr-x    1 www-data www-data      1024 Oct 11 19:46 .rnd
-rwxr-xr-x    1 www-data www-data       120 Oct 11 19:46 .styleci.yml
-rwxr-xr-x    1 www-data www-data      5785 Oct 11 19:46 CHANGELOG.md
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 app
-rwxr-xr-x    1 www-data www-data       425 Oct 11 19:46 artisan
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 bootstrap
-rwxr-xr-x    1 www-data www-data      2504 Oct 11 19:46 composer.json
-rwxr-xr-x    1 www-data www-data    347370 Oct 11 19:46 composer.lock
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 config
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 database
-rwxr-xr-x    1 www-data www-data      5607 Oct 11 19:46 docker-entrypoint.sh
drwxr-xr-x    4 www-data www-data      4096 Oct 11 18:02 logs
-rwxr-xr-x    1 www-data www-data      2732 Oct 11 19:46 nginx.conf
-rwxr-xr-x    1 www-data www-data    157783 Oct 11 19:46 package-lock.json
-rwxr-xr-x    1 www-data www-data       882 Oct 11 19:46 package.json
-rwxr-xr-x    1 www-data www-data        93 Oct 11 19:46 postcss.config.js
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:48 public
-rwxr-xr-x    1 www-data www-data      7063 Oct 11 19:46 render.yaml
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 resources
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 routes
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 scripts
drwxrwxr-x    1 www-data www-data      4096 Oct 11 19:46 storage
-rwxr-xr-x    1 www-data www-data       513 Oct 11 19:46 supervisord.conf
-rwxr-xr-x    1 www-data www-data       541 Oct 11 19:46 tailwind.config.js
drwxr-xr-x    1 www-data www-data      4096 Oct 11 18:02 tmp
drwxr-xr-x    1 www-data www-data      4096 Oct 11 18:01 vendor
-rwxr-xr-x    1 www-data www-data       516 Oct 11 19:46 vite.config.js
=== ENV FILE CONTENT ===
.env file exists:
APP_NAME="TechSubs API"
APP_ENV=production
APP_KEY=base64:M5qp4JA4shYaH36xXqpAoVKT1iQOzqKWAVHQ4ytMXcM=
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=${APP_URL}
ASSET_URL=https://fullstackphp-sprint5-api.onrender.com
# Database Configuration - Use environment variables for security
DB_CONNECTION=pgsql
=== ENVIRONMENT VARIABLES ===
DB_CONNECTION: pgsql
DB_HOST: ep-frosty-mountain-aglbvim1-pooler.c-2.eu-central-1.aws.neon.tech
DB_DATABASE: neondb
DB_USERNAME: neondb_owner
DATABASE_URL: 
==========================
Waiting for PostgreSQL...
Testing connection to ep-frosty-mountain-aglbvim1-pooler.c-2.eu-central-1.aws.neon.tech:5432...
Connection to ep-frosty-mountain-aglbvim1-pooler.c-2.eu-central-1.aws.neon.tech (3.69.34.233) 5432 port [tcp/postgresql] succeeded!
PostgreSQL is ready!
Testing database connection...
Database connection successful!
Setting up Passport keys...
Setting up Passport keys from environment variables...
base64: truncated input
Passport keys configured successfully!
Running migrations...
=== LARAVEL CONFIG DEBUG ===
  database.default ..................................................... pgsql  
  database.connections.pgsql.host  ep-frosty-mountain-aglbvim1-pooler.c-2.eu-central-1.aws.neon.tech  
=== MIGRATION STATUS CHECK ===
  Migration name .............................................. Batch / Status  
  0001_01_01_000000_create_users_table ............................... Pending  
  0001_01_01_000001_create_cache_table ............................... Pending  
  0001_01_01_000002_create_jobs_table ................................ Pending  
  2025_08_21_174746_create_services_table ............................ Pending  
  2025_08_21_174758_create_subscriptions_table ....................... Pending  
  2025_08_24_172700_add_user_id_to_services_table .................... Pending  
  2025_09_16_183545_create_oauth_auth_codes_table .................... Pending  
  2025_09_16_183546_create_oauth_access_tokens_table ................. Pending  
  2025_09_16_183547_create_oauth_refresh_tokens_table ................ Pending  
  2025_09_16_183548_create_oauth_clients_table ....................... Pending  
  2025_09_16_183549_create_oauth_device_codes_table .................. Pending  
  2025_09_16_183550_create_oauth_personal_access_clients_table ....... Pending  
  2025_09_16_185325_add_role_to_users_table .......................... Pending  
=== AVAILABLE MIGRATIONS ===
total 60
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 .
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 ..
-rwxr-xr-x    1 www-data www-data      1473 Oct 11 19:46 0001_01_01_000000_create_users_table.php
-rwxr-xr-x    1 www-data www-data       849 Oct 11 19:46 0001_01_01_000001_create_cache_table.php
==========================
   INFO  Running migrations.  
  0001_01_01_000000_create_users_table .......................... 90.09ms FAIL
In Connection.php line 824:
                                                                               
  [Illuminate\Database\QueryException (25P02)]                                 
  SQLSTATE[25P02]: In failed sql transaction: 7 ERROR:  current transaction i  
  s aborted, commands ignored until end of transaction block (Connection: pgs  
  ql, SQL: alter table "users" add constraint "users_email_unique" unique ("e  
  mail"))                                                                      
                                                                               
Exception trace:
  at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:824
 Illuminate\Database\Connection->runQueryCallback() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:778
 Illuminate\Database\Connection->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:559
 Illuminate\Database\Connection->statement() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Schema/Blueprint.php:121
 Illuminate\Database\Schema\Blueprint->build() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Schema/Builder.php:618
 Illuminate\Database\Schema\Builder->build() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Schema/Builder.php:472
 Illuminate\Database\Schema\Builder->create() at /var/www/html/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php:363
 Illuminate\Support\Facades\Facade::__callStatic() at /var/www/html/database/migrations/0001_01_01_000000_create_users_table.php:14
 Illuminate\Database\Migrations\Migration@anonymous\/var/www/html/database/migrations/0001_01_01_000000_create_users_table.php:7$f5->up() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:514
 Illuminate\Database\Migrations\Migrator->runMethod() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:439
 Illuminate\Database\Migrations\Migrator->Illuminate\Database\Migrations\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Concerns/ManagesTransactions.php:32
 Illuminate\Database\Connection->transaction() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:447
 Illuminate\Database\Migrations\Migrator->runMigration() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:250
 Illuminate\Database\Migrations\Migrator->Illuminate\Database\Migrations\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Console/View/Components/Task.php:41
 Illuminate\Console\View\Components\Task->render() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:809
 Illuminate\Database\Migrations\Migrator->write() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:250
 Illuminate\Database\Migrations\Migrator->runUp() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:210
 Illuminate\Database\Migrations\Migrator->runPending() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:137
 Illuminate\Database\Migrations\Migrator->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/MigrateCommand.php:116
 Illuminate\Database\Console\Migrations\MigrateCommand->Illuminate\Database\Console\Migrations\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:666
 Illuminate\Database\Migrations\Migrator->usingConnection() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/MigrateCommand.php:109
 Illuminate\Database\Console\Migrations\MigrateCommand->runMigrations() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/MigrateCommand.php:88
 Illuminate\Database\Console\Migrations\MigrateCommand->handle() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:36
 Illuminate\Container\BoundMethod::Illuminate\Container\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/Util.php:43
 Illuminate\Container\Util::unwrapIfClosure() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:96
 Illuminate\Container\BoundMethod::callBoundMethod() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:35
 Illuminate\Container\BoundMethod::call() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/Container.php:835
 Illuminate\Container\Container->call() at /var/www/html/vendor/laravel/framework/src/Illuminate/Console/Command.php:211
 Illuminate\Console\Command->execute() at /var/www/html/vendor/symfony/console/Command/Command.php:318
 Symfony\Component\Console\Command\Command->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Console/Command.php:180
 Illuminate\Console\Command->run() at /var/www/html/vendor/symfony/console/Application.php:1092
 Symfony\Component\Console\Application->doRunCommand() at /var/www/html/vendor/symfony/console/Application.php:341
 Symfony\Component\Console\Application->doRun() at /var/www/html/vendor/symfony/console/Application.php:192
 Symfony\Component\Console\Application->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php:197
 Illuminate\Foundation\Console\Kernel->handle() at /var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1234
 Illuminate\Foundation\Application->handleCommand() at /var/www/html/artisan:16
In Connection.php line 570:
                                                                               
  [PDOException (25P02)]                                                       
  SQLSTATE[25P02]: In failed sql transaction: 7 ERROR:  current transaction i  
  s aborted, commands ignored until end of transaction block                   
                                                                               
Exception trace:
  at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:570
 PDOStatement->execute() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:570
 Illuminate\Database\Connection->Illuminate\Database\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:811
 Illuminate\Database\Connection->runQueryCallback() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:778
 Illuminate\Database\Connection->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:559
 Illuminate\Database\Connection->statement() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Schema/Blueprint.php:121
 Illuminate\Database\Schema\Blueprint->build() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Schema/Builder.php:618
 Illuminate\Database\Schema\Builder->build() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Schema/Builder.php:472
 Illuminate\Database\Schema\Builder->create() at /var/www/html/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php:363
 Illuminate\Support\Facades\Facade::__callStatic() at /var/www/html/database/migrations/0001_01_01_000000_create_users_table.php:14
 Illuminate\Database\Migrations\Migration@anonymous\/var/www/html/database/migrations/0001_01_01_000000_create_users_table.php:7$f5->up() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:514
 Illuminate\Database\Migrations\Migrator->runMethod() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:439
 Illuminate\Database\Migrations\Migrator->Illuminate\Database\Migrations\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Concerns/ManagesTransactions.php:32
 Illuminate\Database\Connection->transaction() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:447
 Illuminate\Database\Migrations\Migrator->runMigration() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:250
 Illuminate\Database\Migrations\Migrator->Illuminate\Database\Migrations\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Console/View/Components/Task.php:41
 Illuminate\Console\View\Components\Task->render() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:809
 Illuminate\Database\Migrations\Migrator->write() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:250
 Illuminate\Database\Migrations\Migrator->runUp() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:210
 Illuminate\Database\Migrations\Migrator->runPending() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:137
 Illuminate\Database\Migrations\Migrator->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/MigrateCommand.php:116
 Illuminate\Database\Console\Migrations\MigrateCommand->Illuminate\Database\Console\Migrations\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:666
 Illuminate\Database\Migrations\Migrator->usingConnection() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/MigrateCommand.php:109
 Illuminate\Database\Console\Migrations\MigrateCommand->runMigrations() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/MigrateCommand.php:88
 Illuminate\Database\Console\Migrations\MigrateCommand->handle() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:36
 Illuminate\Container\BoundMethod::Illuminate\Container\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/Util.php:43
 Illuminate\Container\Util::unwrapIfClosure() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:96
 Illuminate\Container\BoundMethod::callBoundMethod() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:35
 Illuminate\Container\BoundMethod::call() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/Container.php:835
 Illuminate\Container\Container->call() at /var/www/html/vendor/laravel/framework/src/Illuminate/Console/Command.php:211
 Illuminate\Console\Command->execute() at /var/www/html/vendor/symfony/console/Command/Command.php:318
 Symfony\Component\Console\Command\Command->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Console/Command.php:180
 Illuminate\Console\Command->run() at /var/www/html/vendor/symfony/console/Application.php:1092
 Symfony\Component\Console\Application->doRunCommand() at /var/www/html/vendor/symfony/console/Application.php:341
 Symfony\Component\Console\Application->doRun() at /var/www/html/vendor/symfony/console/Application.php:192
 Symfony\Component\Console\Application->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php:197
 Illuminate\Foundation\Console\Kernel->handle() at /var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1234
 Illuminate\Foundation\Application->handleCommand() at /var/www/html/artisan:16
=== MIGRATIONS FAILED ===
Migration failed with exit code: 0
=== DATABASE STATE DEBUG ===
PDO Connection successful
Database name: neondb
==> Exited with status 1
==> Common ways to troubleshoot your deploy: https://render.com/docs/troubleshooting-deploys
Starting Laravel application initialization...
=== DEBUG ENVIRONMENT ===
Current directory: /var/www/html
Files in current directory:
total 640
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:48 .
drwxr-xr-x    1 root     root          4096 Oct 11 18:00 ..
-rwxr-xr-x    1 www-data www-data       898 Oct 11 19:46 .dockerignore
-rwxr-xr-x    1 www-data www-data       258 Oct 11 19:46 .editorconfig
-rwxr-xr-x    1 www-data www-data      1256 Oct 11 19:46 .env
-rwxr-xr-x    1 www-data www-data      1256 Oct 11 19:46 .env.docker
-rwxr-xr-x    1 www-data www-data       186 Oct 11 19:46 .gitattributes
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 .github
-rwxr-xr-x    1 www-data www-data      1024 Oct 11 19:46 .rnd
-rwxr-xr-x    1 www-data www-data       120 Oct 11 19:46 .styleci.yml
-rwxr-xr-x    1 www-data www-data      5785 Oct 11 19:46 CHANGELOG.md
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 app
-rwxr-xr-x    1 www-data www-data       425 Oct 11 19:46 artisan
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 bootstrap
-rwxr-xr-x    1 www-data www-data      2504 Oct 11 19:46 composer.json
-rwxr-xr-x    1 www-data www-data    347370 Oct 11 19:46 composer.lock
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 config
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 database
-rwxr-xr-x    1 www-data www-data      5607 Oct 11 19:46 docker-entrypoint.sh
drwxr-xr-x    4 www-data www-data      4096 Oct 11 18:02 logs
-rwxr-xr-x    1 www-data www-data      2732 Oct 11 19:46 nginx.conf
-rwxr-xr-x    1 www-data www-data    157783 Oct 11 19:46 package-lock.json
-rwxr-xr-x    1 www-data www-data       882 Oct 11 19:46 package.json
-rwxr-xr-x    1 www-data www-data        93 Oct 11 19:46 postcss.config.js
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:48 public
-rwxr-xr-x    1 www-data www-data      7063 Oct 11 19:46 render.yaml
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 resources
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 routes
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 scripts
drwxrwxr-x    1 www-data www-data      4096 Oct 11 19:46 storage
-rwxr-xr-x    1 www-data www-data       513 Oct 11 19:46 supervisord.conf
-rwxr-xr-x    1 www-data www-data       541 Oct 11 19:46 tailwind.config.js
drwxr-xr-x    1 www-data www-data      4096 Oct 11 18:02 tmp
drwxr-xr-x    1 www-data www-data      4096 Oct 11 18:01 vendor
-rwxr-xr-x    1 www-data www-data       516 Oct 11 19:46 vite.config.js
=== ENV FILE CONTENT ===
.env file exists:
APP_NAME="TechSubs API"
APP_ENV=production
APP_KEY=base64:M5qp4JA4shYaH36xXqpAoVKT1iQOzqKWAVHQ4ytMXcM=
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=${APP_URL}
ASSET_URL=https://fullstackphp-sprint5-api.onrender.com
# Database Configuration - Use environment variables for security
DB_CONNECTION=pgsql
=== ENVIRONMENT VARIABLES ===
DB_CONNECTION: pgsql
DB_HOST: ep-frosty-mountain-aglbvim1-pooler.c-2.eu-central-1.aws.neon.tech
DB_DATABASE: neondb
DB_USERNAME: neondb_owner
DATABASE_URL: 
==========================
Waiting for PostgreSQL...
Testing connection to ep-frosty-mountain-aglbvim1-pooler.c-2.eu-central-1.aws.neon.tech:5432...
Connection to ep-frosty-mountain-aglbvim1-pooler.c-2.eu-central-1.aws.neon.tech (3.69.34.233) 5432 port [tcp/postgresql] succeeded!
PostgreSQL is ready!
Testing database connection...
Database connection successful!
Setting up Passport keys...
Setting up Passport keys from environment variables...
base64: truncated input
Passport keys configured successfully!
Running migrations...
=== LARAVEL CONFIG DEBUG ===
  database.default ..................................................... pgsql  
  database.connections.pgsql.host  ep-frosty-mountain-aglbvim1-pooler.c-2.eu-central-1.aws.neon.tech  
=== MIGRATION STATUS CHECK ===
  Migration name .............................................. Batch / Status  
  0001_01_01_000000_create_users_table ............................... Pending  
  0001_01_01_000001_create_cache_table ............................... Pending  
  0001_01_01_000002_create_jobs_table ................................ Pending  
  2025_08_21_174746_create_services_table ............................ Pending  
  2025_08_21_174758_create_subscriptions_table ....................... Pending  
  2025_08_24_172700_add_user_id_to_services_table .................... Pending  
  2025_09_16_183545_create_oauth_auth_codes_table .................... Pending  
  2025_09_16_183546_create_oauth_access_tokens_table ................. Pending  
  2025_09_16_183547_create_oauth_refresh_tokens_table ................ Pending  
  2025_09_16_183548_create_oauth_clients_table ....................... Pending  
  2025_09_16_183549_create_oauth_device_codes_table .................. Pending  
  2025_09_16_183550_create_oauth_personal_access_clients_table ....... Pending  
  2025_09_16_185325_add_role_to_users_table .......................... Pending  
=== AVAILABLE MIGRATIONS ===
total 60
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 .
drwxr-xr-x    1 www-data www-data      4096 Oct 11 19:46 ..
-rwxr-xr-x    1 www-data www-data      1473 Oct 11 19:46 0001_01_01_000000_create_users_table.php
-rwxr-xr-x    1 www-data www-data       849 Oct 11 19:46 0001_01_01_000001_create_cache_table.php
==========================
   INFO  Running migrations.  
  0001_01_01_000000_create_users_table .......................... 82.86ms FAIL
In Connection.php line 824:
                                                                               
  [Illuminate\Database\QueryException (25P02)]                                 
  SQLSTATE[25P02]: In failed sql transaction: 7 ERROR:  current transaction i  
  s aborted, commands ignored until end of transaction block (Connection: pgs  
  ql, SQL: alter table "users" add constraint "users_email_unique" unique ("e  
  mail"))                                                                      
                                                                               
Exception trace:
  at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:824
 Illuminate\Database\Connection->runQueryCallback() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:778
 Illuminate\Database\Connection->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:559
 Illuminate\Database\Connection->statement() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Schema/Blueprint.php:121
 Illuminate\Database\Schema\Blueprint->build() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Schema/Builder.php:618
 Illuminate\Database\Schema\Builder->build() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Schema/Builder.php:472
 Illuminate\Database\Schema\Builder->create() at /var/www/html/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php:363
 Illuminate\Support\Facades\Facade::__callStatic() at /var/www/html/database/migrations/0001_01_01_000000_create_users_table.php:14
 Illuminate\Database\Migrations\Migration@anonymous\/var/www/html/database/migrations/0001_01_01_000000_create_users_table.php:7$f5->up() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:514
 Illuminate\Database\Migrations\Migrator->runMethod() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:439
 Illuminate\Database\Migrations\Migrator->Illuminate\Database\Migrations\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Concerns/ManagesTransactions.php:32
 Illuminate\Database\Connection->transaction() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:447
 Illuminate\Database\Migrations\Migrator->runMigration() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:250
 Illuminate\Database\Migrations\Migrator->Illuminate\Database\Migrations\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Console/View/Components/Task.php:41
 Illuminate\Console\View\Components\Task->render() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:809
 Illuminate\Database\Migrations\Migrator->write() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:250
 Illuminate\Database\Migrations\Migrator->runUp() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:210
 Illuminate\Database\Migrations\Migrator->runPending() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:137
 Illuminate\Database\Migrations\Migrator->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/MigrateCommand.php:116
 Illuminate\Database\Console\Migrations\MigrateCommand->Illuminate\Database\Console\Migrations\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:666
 Illuminate\Database\Migrations\Migrator->usingConnection() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/MigrateCommand.php:109
 Illuminate\Database\Console\Migrations\MigrateCommand->runMigrations() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/MigrateCommand.php:88
 Illuminate\Database\Console\Migrations\MigrateCommand->handle() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:36
 Illuminate\Container\BoundMethod::Illuminate\Container\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/Util.php:43
 Illuminate\Container\Util::unwrapIfClosure() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:96
 Illuminate\Container\BoundMethod::callBoundMethod() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:35
 Illuminate\Container\BoundMethod::call() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/Container.php:835
 Illuminate\Container\Container->call() at /var/www/html/vendor/laravel/framework/src/Illuminate/Console/Command.php:211
 Illuminate\Console\Command->execute() at /var/www/html/vendor/symfony/console/Command/Command.php:318
 Symfony\Component\Console\Command\Command->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Console/Command.php:180
 Illuminate\Console\Command->run() at /var/www/html/vendor/symfony/console/Application.php:1092
 Symfony\Component\Console\Application->doRunCommand() at /var/www/html/vendor/symfony/console/Application.php:341
 Symfony\Component\Console\Application->doRun() at /var/www/html/vendor/symfony/console/Application.php:192
 Symfony\Component\Console\Application->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php:197
 Illuminate\Foundation\Console\Kernel->handle() at /var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1234
 Illuminate\Foundation\Application->handleCommand() at /var/www/html/artisan:16
In Connection.php line 570:
                                                                               
  [PDOException (25P02)]                                                       
  SQLSTATE[25P02]: In failed sql transaction: 7 ERROR:  current transaction i  
  s aborted, commands ignored until end of transaction block                   
                                                                               
Exception trace:
  at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:570
 PDOStatement->execute() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:570
 Illuminate\Database\Connection->Illuminate\Database\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:811
 Illuminate\Database\Connection->runQueryCallback() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:778
 Illuminate\Database\Connection->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connection.php:559
 Illuminate\Database\Connection->statement() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Schema/Blueprint.php:121
 Illuminate\Database\Schema\Blueprint->build() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Schema/Builder.php:618
 Illuminate\Database\Schema\Builder->build() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Schema/Builder.php:472
 Illuminate\Database\Schema\Builder->create() at /var/www/html/vendor/laravel/framework/src/Illuminate/Support/Facades/Facade.php:363
 Illuminate\Support\Facades\Facade::__callStatic() at /var/www/html/database/migrations/0001_01_01_000000_create_users_table.php:14
 Illuminate\Database\Migrations\Migration@anonymous\/var/www/html/database/migrations/0001_01_01_000000_create_users_table.php:7$f5->up() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:514
 Illuminate\Database\Migrations\Migrator->runMethod() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:439
 Illuminate\Database\Migrations\Migrator->Illuminate\Database\Migrations\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Concerns/ManagesTransactions.php:32
 Illuminate\Database\Connection->transaction() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:447
 Illuminate\Database\Migrations\Migrator->runMigration() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:250
 Illuminate\Database\Migrations\Migrator->Illuminate\Database\Migrations\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Console/View/Components/Task.php:41
 Illuminate\Console\View\Components\Task->render() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:809
 Illuminate\Database\Migrations\Migrator->write() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:250
 Illuminate\Database\Migrations\Migrator->runUp() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:210
 Illuminate\Database\Migrations\Migrator->runPending() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:137
 Illuminate\Database\Migrations\Migrator->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/MigrateCommand.php:116
 Illuminate\Database\Console\Migrations\MigrateCommand->Illuminate\Database\Console\Migrations\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Migrations/Migrator.php:666
 Illuminate\Database\Migrations\Migrator->usingConnection() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/MigrateCommand.php:109
 Illuminate\Database\Console\Migrations\MigrateCommand->runMigrations() at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Console/Migrations/MigrateCommand.php:88
 Illuminate\Database\Console\Migrations\MigrateCommand->handle() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:36
 Illuminate\Container\BoundMethod::Illuminate\Container\{closure}() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/Util.php:43
 Illuminate\Container\Util::unwrapIfClosure() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:96
 Illuminate\Container\BoundMethod::callBoundMethod() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php:35
 Illuminate\Container\BoundMethod::call() at /var/www/html/vendor/laravel/framework/src/Illuminate/Container/Container.php:835
 Illuminate\Container\Container->call() at /var/www/html/vendor/laravel/framework/src/Illuminate/Console/Command.php:211
 Illuminate\Console\Command->execute() at /var/www/html/vendor/symfony/console/Command/Command.php:318
 Symfony\Component\Console\Command\Command->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Console/Command.php:180
 Illuminate\Console\Command->run() at /var/www/html/vendor/symfony/console/Application.php:1092
 Symfony\Component\Console\Application->doRunCommand() at /var/www/html/vendor/symfony/console/Application.php:341
 Symfony\Component\Console\Application->doRun() at /var/www/html/vendor/symfony/console/Application.php:192
 Symfony\Component\Console\Application->run() at /var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php:197
 Illuminate\Foundation\Console\Kernel->handle() at /var/www/html/vendor/laravel/framework/src/Illuminate/Foundation/Application.php:1234
 Illuminate\Foundation\Application->handleCommand() at /var/www/html/artisan:16
=== MIGRATIONS FAILED ===
Migration failed with exit code: 0
=== DATABASE STATE DEBUG ===
PDO Connection successful
Database name: neondb