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
- Input Validation: Robust input data validation
- Date Formatting: ISO 8601 standardization for all API dates
- Reports: Basic endpoints for expense reports
- Export: CSV data export functionality

## Documentation and Implementation Details

This README provides a high-level overview of the system and installation. For a detailed technical breakdown of the implementation, including branch-by-branch decisions, improvements, and architectural changes, see:

Implementation details:
- [docs/Sprint 5/README_implementation.md](docs/Sprint%205/README_implementation.md) 

API endpoints documentation:
- [docs/Sprint 5/endpoints/ENDPOINTS_API.md](docs/Sprint%205/endpoints/ENDPOINTS_API.md) 

The `docs/` folder contains additional documentation for each feature, API endpoint, and development process.

## Installation Instructions

### Prerequisites

Before installing TechSubs, ensure your development environment meets these requirements:

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

### Step-by-Step Installation

1. Clone the Repository
   ```bash
   git clone https://github.com/leandrodsg/fullstackphp-sprint5.git
   cd fullstackphp-sprint5/TechSubs_API
   ```

2. Install PHP Dependencies
   ```bash
   composer install
   ```

3. Install and Build Frontend Assets
   ```bash
   npm install
   npm run build
   ```

4. Environment Configuration
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Database Setup
   
   For SQLite (recommended for development):
   ```bash
   touch database/database.sqlite
   ```
   
   For MySQL, update `.env` file:
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

7. Seed Database (Recommended)
   ```bash
   php artisan db:seed
   ```

8. Install Passport and Generate Keys
   ```bash
   php artisan passport:install
   ```

9. Start Development Server
   ```bash
   php artisan serve
   ```

The application will be available at `http://localhost:8000`

## Quick Start

After installation, test the API functionality with these commands:

### Register a new user
```bash
curl -X POST http://localhost:8000/api/v1/register \
-H "Content-Type: application/json" \
-d "{\"name\":\"Test User\",\"email\":\"test@test.com\",\"password\":\"TestPassword@123\",\"password_confirmation\":\"TestPassword@123\"}"
```

### Login and obtain access token
```bash
curl -X POST http://localhost:8000/api/v1/login \
-H "Content-Type: application/json" \
-d "{\"email\":\"test@test.com\",\"password\":\"TestPassword@123\"}"
```

Copy the `access_token` from the response for subsequent requests.

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
-d "{\"service_id\":1,\"plan\":\"Premium\",\"price\":15.99,\"currency\":\"USD\",\"next_billing_date\":\"2024-02-01\"}"
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

## Troubleshooting

### Internal Server Error (500)
```bash
php artisan key:generate
php artisan config:clear
php artisan cache:clear
```

### Passport authentication issues
```bash
php artisan passport:install --force
php artisan passport:keys --force
```

### Database connection problems
1. Ensure `.env` file exists (copy from `.env.example`)
2. For SQLite: verify `database/database.sqlite` file exists
3. For MySQL: check database credentials in `.env`

### Permission errors (Windows/XAMPP)
```bash
chmod -R 775 storage bootstrap/cache
```

### Migration failures
```bash
php artisan migrate:fresh --seed
```

## Testing Data

### Example service data
```json
{
  "name": "Netflix",
  "category": "Streaming",
  "website_url": "https://netflix.com",
  "description": "Video streaming service"
}
```

### Example subscription data
```json
{
  "service_id": 1,
  "plan": "Premium",
  "price": 15.99,
  "currency": "USD",
  "next_billing_date": "2024-02-01"
}
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

The system provides RESTful API endpoints for all core functionality:

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