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
docker-compose exec app php artisan passport:keys --force
docker-compose exec app npm install
docker-compose exec app npm run build
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
   docker-compose exec app php artisan passport:keys --force
   ```
   Sets up OAuth2 authentication keys and clients for API access.

8. Install and Build Frontend Assets
   ```bash
   docker-compose exec app npm install
   docker-compose exec app npm run build
   ```
   Installs JavaScript dependencies and compiles CSS/JS assets.

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

   Option C: MySQL (Alternative)
   
   For MySQL databases, update your `.env` file:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=techsubs
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
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
   php artisan passport:keys --force
   ```
   Sets up OAuth2 authentication keys and clients for API access.

9. Build Frontend Assets
   ```bash
   npm run build
   ```
   Compiles and optimizes CSS and JavaScript files for production.

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

### Login and obtain access token
```bash
curl -X POST http://localhost:8001/api/v1/login \
-H "Content-Type: application/json" \
-d "{\"email\":\"user@example.com\",\"password\":\"UserPassword@123\"}"
```

### Create a service using the token
```bash
curl -X POST http://localhost:8001/api/v1/services \
-H "Authorization: Bearer YOUR_TOKEN_HERE" \
-H "Content-Type: application/json" \
-d "{\"name\":\"Netflix\",\"category\":\"Streaming\",\"website_url\":\"https://netflix.com\",\"description\":\"Video streaming service\"}"
```

### Create a subscription using the token
```bash
curl -X POST http://localhost:8001/api/v1/subscriptions \
-H "Authorization: Bearer YOUR_TOKEN_HERE" \
-H "Content-Type: application/json" \
-d "{\"service_id\":1,\"plan\":\"Premium\",\"price\":15.99,\"currency\":\"USD\",\"next_billing_date\":\"2024-02-01\"}"
```

### List user services
```bash
curl -X GET http://localhost:8001/api/v1/services \
-H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### List user subscriptions
```bash
curl -X GET http://localhost:8001/api/v1/subscriptions \
-H "Authorization: Bearer YOUR_TOKEN_HERE"
```

For Traditional Setup: Replace `8001` with `8000` in all URLs above.

Copy the `access_token` from the login response for subsequent requests.

## Docker Environment

### Docker Architecture

- Laravel Application: Runs on port 8001
- PostgreSQL Database: Runs on port 5432 with persistent data storage
- Nginx: Web server handling PHP-FPM requests
- PHP-FPM: PHP FastCGI Process Manager for optimal performance

## Troubleshooting

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
- `POST /api/register` — User registration
- `POST /api/login` — User login
- `POST /api/logout` — User logout
- `GET /api/user` — Get authenticated user profile

### Services Management
- `GET /api/services` — List user services
- `POST /api/services` — Create new service
- `GET /api/services/{id}` — Get specific service
- `PUT /api/services/{id}` — Update service
- `DELETE /api/services/{id}` — Delete service

### Subscriptions Management
- `GET /api/subscriptions` — List user subscriptions
- `POST /api/subscriptions` — Create new subscription
- `GET /api/subscriptions/{id}` — Get specific subscription
- `PUT /api/subscriptions/{id}` — Update subscription
- `DELETE /api/subscriptions/{id}` — Delete subscription

### Reports
- `GET /api/reports/my-expenses` — Get expense reports
- `GET /api/reports/my-expenses/export` — Export expenses as CSV

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