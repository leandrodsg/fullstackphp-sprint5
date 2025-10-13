# TechSubs API - Subscription Management System

TechSubs is a REST API built with Laravel for managing digital service subscriptions.

## Project Overview

TechSubs allows users to manage their digital service subscriptions through a REST API. The system provides basic CRUD functionality for services and subscriptions, with authentication via Laravel Passport.

### Implemented Features

- API Authentication: Authentication system with Laravel Passport
- Service Management: Complete CRUD for digital services
- Subscription Management: Complete CRUD for user subscriptions
- Automatic Billing Cycle Detection: System that automatically detects if a subscription is monthly or annual based on price
- Calculated Fields: Automatic fields like days until next billing, expiration status, and formatted price
- Input Validation: Input data validation
- Date Formatting: ISO 8601 standardization for all API dates
- Reports: Basic endpoints for expense reports
- Export: CSV data export functionality

## Implementation Details

This README provides a high-level overview of the system and installation. For a detailed technical breakdown of the implementation, including branch-by-branch decisions, improvements, and architectural changes, see:

Implementation details:
- [docs/Sprint 5/README_implementation.md](docs/Sprint%205/README_implementation.md) 

API endpoints documentation:
- [docs/Sprint 5/endpoints/ENDPOINTS_API.md](docs/Sprint%205/endpoints/ENDPOINTS_API.md) 

The `docs/` folder contains additional documentation for each feature, API endpoint, and development process.

## Installation Instructions

### Prerequisites

Before installing TechSubs, choose your preferred development environment:

Option 1: Docker (Recommended)
- Docker Desktop 4.0+

Option 2: Traditional Setup
- PHP 8.2+ with required extensions:
  - OpenSSL PHP Extension
  - PDO PHP Extension
  - Mbstring PHP Extension
  - Tokenizer PHP Extension
  - XML PHP Extension
  - Ctype PHP Extension
  - JSON PHP Extension
- Composer 2.8+ (dependency manager for PHP)
- Node.js 22+ and npm (for asset compilation)
- Database: MySQL 8.0+ or SQLite 3.8.8+
- Web Server: Apache, Nginx, or PHP built-in server

### Installed Dependencies

Backend (PHP/Laravel):
- Laravel Framework 12.25.0
- Laravel Passport 13.0+ (OAuth2 authentication)
- Laravel Tinker 2.10+ (REPL)
- Laravel Breeze 2.3+ (authentication scaffolding)
- PHPUnit 11.5+ (testing framework)

Frontend (Node.js):
- Vite 7.0+ (build tool)
- Tailwind CSS 3.1+ (CSS framework)
- Alpine.js 3.4+ (JavaScript framework)
- Axios 1.11+ (HTTP client)

## Quick Start

For immediate setup with minimal configuration, use Docker:

```bash
git clone https://github.com/leandrodsg/fullstackphp-sprint5.git
cd fullstackphp-sprint5/TechSubs_API
cp .env.example .env
docker-compose up -d
docker-compose exec app composer install
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan passport:install
# When prompted about migrations, select NO
docker-compose exec app php artisan passport:keys --force
docker-compose exec app npm install
docker-compose exec app npm run build
# If you get "Vite manifest not found" error, run:
# docker-compose exec app cp public/build/.vite/manifest.json public/build/manifest.json
```

Access the application at `http://localhost:8001` and login with:
- Email: `user@example.com` 
- Password: `UserPassword@123`

### Installation

#### Option 1: Docker Setup (Recommended)

Prerequisites: Docker and Docker Compose installed on your system.

1. Clone the Repository
   ```bash
   git clone https://github.com/leandrodsg/fullstackphp-sprint5.git
   cd fullstackphp-sprint5/TechSubs_API
   ```

2. Environment Configuration
   ```bash
   cp .env.example .env
   ```
   The `.env.example` file is pre-configured for Docker with SQLite database.

3. Start Docker Services
   ```bash
   docker-compose up -d
   ```
   This starts the web server, database, and other services in the background.

4. Install PHP Dependencies
   ```bash
   docker-compose exec app composer install
   ```
   Downloads and installs all Laravel packages and dependencies.

5. Generate Application Key
   ```bash
   docker-compose exec app php artisan key:generate
   ```
   Creates a unique encryption key for your Laravel application.

6. Setup Database
   ```bash
   docker-compose exec app php artisan migrate
   docker-compose exec app php artisan db:seed
   ```
   Creates database tables and populates them with sample data.

7. Configure Laravel Passport (OAuth2)
   ```bash
   docker-compose exec app php artisan passport:install
   ```
   **Important:** When prompted "Would you like to run all pending database migrations?", select **NO** since we already ran migrations in step 6.
   
   Then generate the encryption keys:
   ```bash
   docker-compose exec app php artisan passport:keys --force
   ```
   Sets up OAuth2 authentication keys and clients for API access.

8. Install and Build Frontend Assets
   ```bash
   docker-compose exec app npm install
   docker-compose exec app npm run build
   ```
   Installs JavaScript dependencies and compiles CSS/JS assets.

   **Note:** If you encounter a "Vite manifest not found" error after building, run:
   ```bash
   docker-compose exec app cp public/build/.vite/manifest.json public/build/manifest.json
   ```
   This issue occurs with Vite 7.0.4+ where the manifest file is placed in a subfolder.

The application will be available at `http://localhost:8001`

#### Option 2: Traditional Setup

Prerequisites: PHP 8.2+, Composer, Node.js 18+, and a database system (SQLite/PostgreSQL/MySQL).

1. Clone the Repository
   ```bash
   git clone https://github.com/leandrodsg/fullstackphp-sprint5.git
   cd fullstackphp-sprint5/TechSubs_API
   ```

2. Install PHP Dependencies
   ```bash
   composer install
   ```
   Downloads and installs all Laravel packages and dependencies.

3. Install Frontend Dependencies
   ```bash
   npm install
   ```
   Downloads JavaScript packages and development tools.

4. Environment Configuration
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Creates your environment file and generates a unique application encryption key.

5. Database Setup

   Option A: SQLite (Recommended for Local Development)
   
   SQLite requires no additional configuration and works out of the box:
   ```bash
   touch database/database.sqlite
   ```
   Your `.env` should already have `DB_CONNECTION=sqlite` from the example file.

   Option B: PostgreSQL (Production/Neon Hosting)
   
   For PostgreSQL databases (like Neon.tech used in production), update your `.env` file:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=your-neon-host.neon.tech
   DB_PORT=5432
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   DB_SSLMODE=require
   ```

   Option C: MySQL/XAMPP (Alternative)
   
   For MySQL databases with XAMPP, update your `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=techsubs_api
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. Run Database Migrations
   ```bash
   php artisan migrate
   ```
   Creates all necessary database tables based on Laravel migration files.

7. Seed Database (Recommended)
   ```bash
   php artisan db:seed
   ```
   Populates the database with sample users, services, and subscriptions for testing.

8. Configure Laravel Passport (OAuth2)
   ```bash
   php artisan passport:install
   ```
   **Important:** When prompted "Would you like to run all pending database migrations?", select **NO** since we already ran migrations in step 6.
   
   Then generate the encryption keys:
   ```bash
   php artisan passport:keys --force
   ```
   Sets up OAuth2 authentication keys and clients for API access.

9. Build Frontend Assets
   ```bash
   npm run build
   ```
   Compiles and optimizes CSS and JavaScript files for production.

   **Note:** If you encounter a "Vite manifest not found" error after building, run:
   ```bash
   Copy-Item "public/build/.vite/manifest.json" "public/build/manifest.json"
   ```
   This issue occurs with Vite 7.0.4+ where the manifest file is placed in a subfolder.

10. Start Development Server
    ```bash
    php artisan serve
    ```
    Starts the Laravel development server on your local machine.

The application will be available at `http://localhost:8000`

## Test Accounts

The system comes with two pre-configured accounts for different testing scenarios:

### ADMIN Account (Empty Profile - Testing from Scratch)
- Email: `admin@example.com`
- Password: `AdminPassword@123`
- Role: Administrator
- Clean slate for testing all functionalities

### USER Account (Complete Test Data)
- Email: `user@example.com`
- Password: `UserPassword@123`
- Role: Regular User
- Immediate demonstration of system capabilities

### Web Interface Access

1. Login Page: `http://localhost:8001/login`
2. Dashboard: `http://localhost:8001/dashboard` (after login)
3. Services: `http://localhost:8001/services`
4. Subscriptions: `http://localhost:8001/subscriptions`

Note: Traditional setup uses port 8000 instead of 8001.

### API Testing

The API endpoints vary depending on your setup method:

**Docker Setup:**
- Base URL: `http://localhost:8001/api/v1`
- Used when following Option 1 (Docker) installation

**Traditional Setup (XAMPP/Local):**
- Base URL: `http://localhost:8000/api/v1`  
- Used when following Option 2 (Traditional) installation

**Production (Render):**
- Base URL: `https://techsubs-api.onrender.com/api/v1`
- Live production environment

**Note:** The Postman Collection is configured for Traditional Setup (port 8000). If using Docker, change the `base_url` variable to `http://localhost:8001/api/v1` in Postman.

### Login and obtain access token
```bash
curl -X POST http://localhost:8000/api/v1/login \
-H "Content-Type: application/json" \
-d "{\"email\":\"user@example.com\",\"password\":\"UserPassword@123\"}"
```

### Create a service using the token
```bash
curl -X POST http://localhost:8000/api/v1/services \
-H "Authorization: Bearer YOUR_TOKEN_HERE" \
-H "Content-Type: application/json" \
-d "{\"name\":\"Netflix\",\"category\":\"Streaming\",\"website_url\":\"https://netflix.com\",\"description\":\"Video streaming service\"}"
```

### Create a subscription using the token
```bash
curl -X POST http://localhost:8000/api/v1/subscriptions \
-H "Authorization: Bearer YOUR_TOKEN_HERE" \
-H "Content-Type: application/json" \
-d "{\"service_id\":1,\"plan\":\"Premium\",\"price\":15.99,\"currency\":\"USD\",\"next_billing_date\":\"2024-12-01\",\"status\":\"active\"}"
```

### List user services
```bash
curl -X GET http://localhost:8000/api/v1/services \
-H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### List user subscriptions
```bash
curl -X GET http://localhost:8000/api/v1/subscriptions \
-H "Authorization: Bearer YOUR_TOKEN_HERE"
```

For Docker Setup: Replace `8000` with `8001` in all URLs above.

Copy the `token` from the login response data field for subsequent requests.

Example login response:
```json
{
  "success": true,
  "message": "Login successful", 
  "data": {
    "user": {...},
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
  }
}
```

## Postman Collection

The file `docs/Sprint 5/endpoints/POSTMAN_COLLECTION.json` contains a ready-to-import collection for testing all endpoints.

### Using Postman Collection with Different Setups

The collection is pre-configured for Traditional Setup (localhost:8000). To use with other setups:

**For Docker Setup:**
1. Import the collection into Postman
2. Go to Variables tab in the collection
3. Change `base_url` from `http://localhost:8000/api/v1` to `http://localhost:8001/api/v1`

**For Production Testing:**
1. Change `base_url` to `https://techsubs-api.onrender.com/api/v1`
2. Use real production credentials or test accounts

**Testing Workflow:**
1. Run "Login" request first to get access token
2. Token is automatically saved to collection variables
3. All other requests will use the token automatically

**API Response Format:**
All API endpoints return responses in this consistent format:
```json
{
  "success": true/false,
  "message": "Description of the result",
  "data": {} // The actual response data
}
```

## Docker Environment

### Docker Architecture

- Laravel Application: Runs on port 8001
- PostgreSQL Database: Runs on port 5432 with persistent data storage
- Nginx: Web server handling PHP-FPM requests
- PHP-FPM: PHP FastCGI Process Manager for optimal performance

## Troubleshooting

### Passport Installation Issues

Error: "Base table or view already exists: oauth_auth_codes"

This occurs when `passport:install` tries to run migrations that were already executed. This happens because:
1. You ran `php artisan migrate` (which includes Passport tables from our custom migrations)
2. Then `passport:install` tries to run the same migrations again

Solution:
When `passport:install` asks "Would you like to run all pending database migrations?", always answer **NO**.
Then run `php artisan passport:keys --force` separately.

**Correct sequence:**
```bash
php artisan migrate          # Creates all tables including Passport tables
php artisan db:seed         # Populates with sample data
php artisan passport:install # Select NO when asked about migrations
php artisan passport:keys --force  # Generate encryption keys
```

### Laravel 12 + Passport 13.9 Issues

Token generation fails or API returns "Unauthenticated":
```bash
# Clear all caches
php artisan optimize:clear

# Regenerate Passport keys
php artisan passport:keys --force

# Verify keys exist
ls storage/oauth-*.key
```

Personal Access Client not found:
```bash
# Check if personal access client exists
php artisan tinker
>>> \DB::table('oauth_personal_access_clients')->get();

# If empty, ensure passport:install was run
php artisan passport:install
```

Environment inconsistency between Docker and local:
- Docker setup automatically handles Passport keys via docker-entrypoint.sh
- Local setup requires manual execution of passport:keys --force after passport:install
- Both environments need PASSPORT_PRIVATE_KEY and PASSPORT_PUBLIC_KEY in .env for production

### Vite Build Issues

"Vite manifest not found" error after running `npm run build`:

This error occurs because Vite 7.0.4+ places the manifest file in a subfolder, but Laravel looks for it in the build root directory.

```bash
# Copy manifest file to correct location
Copy-Item "public/build/.vite/manifest.json" "public/build/manifest.json"
```

For Docker environment:
```bash
# Copy manifest file within Docker container
docker-compose exec app cp public/build/.vite/manifest.json public/build/manifest.json
```

This step should be performed after running `npm run build` if you encounter the manifest error.

### Render Deployment Issues

CSS/JS assets not loading (404 errors for build files):

This can occur during Render deployment if the Vite build process doesn't complete properly or if there are caching issues.

```bash
# Force rebuild of assets during deployment
npm run build

# Ensure manifest is in correct location (if using Vite 7.0.4+)
if [ -f "public/build/.vite/manifest.json" ]; then
    cp public/build/.vite/manifest.json public/build/manifest.json
fi
```

The Dockerfile and docker-entrypoint.sh have been updated to handle this automatically, but manual intervention may be required if assets still don't load after deployment.

### Docker Issues

Container won't start:
```bash
# Check container status
docker-compose ps

# View detailed logs
docker-compose logs app
docker-compose logs db

# Restart services
docker-compose down && docker-compose up -d
```

Port conflicts:
- If port 8001 is in use, modify `docker-compose.yml` to use a different port
- Check what's using the port: `netstat -ano | findstr :8001`

Database connection issues:
```bash
# Verify database is running
docker-compose exec db psql -U techsubs_user -d techsubs_db -c "\dt"

# Reset database
docker-compose down -v
docker-compose up -d
```

## Database Schema

The application uses three core entities:

Users Table
- Primary authentication entity with Laravel Passport
- Standard user fields: name, email, password
- Timestamps for created_at and updated_at

Services Table
- Stores digital service information
- Fields: name, category, website_url, description
- Links to users through foreign key constraints

Subscriptions Table
- Connects users to their services
- Fields: plan, price, currency, next_billing_date, status
- Foreign keys to both users and services tables

Database Relationships
- User hasMany Services and Subscriptions
- Service belongsTo User
- Subscription belongsTo both User and Service

## API Endpoints

### Authentication
- `POST /api/v1/register` — User registration
- `POST /api/v1/login` — User login
- `POST /api/v1/logout` — User logout
- `GET /api/v1/profile` — Get authenticated user profile
- `GET /api/v1/user` — Get authenticated user profile (alias)
- `PUT /api/v1/change-password` — Change user password

### Services Management
- `GET /api/v1/services` — List user services
- `POST /api/v1/services` — Create new service
- `GET /api/v1/services/{id}` — Get specific service
- `PUT /api/v1/services/{id}` — Update service
- `PATCH /api/v1/services/{id}` — Partial update service
- `DELETE /api/v1/services/{id}` — Delete service

### Categories
- `GET /api/v1/categories` — List available categories

### Subscriptions Management
- `GET /api/v1/subscriptions` — List user subscriptions
- `POST /api/v1/subscriptions` — Create new subscription
- `GET /api/v1/subscriptions/{id}` — Get specific subscription
- `PUT /api/v1/subscriptions/{id}` — Update subscription
- `DELETE /api/v1/subscriptions/{id}` — Delete subscription
- `PATCH /api/v1/subscriptions/{id}/cancel` — Cancel subscription
- `PATCH /api/v1/subscriptions/{id}/reactivate` — Reactivate subscription

### Reports
- `GET /api/v1/reports/my-expenses` — Get expense reports
- `GET /api/v1/reports/my-expenses/export` — Export expenses as CSV

## Security Features

- OAuth2 Authentication: Laravel Passport for secure API access
- User Data Isolation: Users can only access their own data
- Input Validation: Comprehensive validation for all API endpoints
- CSRF Protection: Built-in Laravel CSRF protection for web routes

## Technology Stack

Backend:
- Laravel Framework 12.25.0
- PHP 8.2+
- Laravel Passport (OAuth2 authentication)
- MySQL/SQLite database

Frontend:
- Vite (build tool)
- Tailwind CSS (styling)
- Alpine.js (JavaScript framework)
- Axios (HTTP client)

Development Tools:
- Composer (PHP dependency management)
- PHPUnit (testing framework)
- Laravel Artisan CLI