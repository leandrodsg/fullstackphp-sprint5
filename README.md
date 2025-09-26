# TechSubs - Digital Subscription Management System

TechSubs is a web application built with Laravel 12 for managing digital service subscriptions, now evolved to a full-featured REST API and modern frontend in Sprint 5.

## Project Overview (Sprint 5)

TechSubs enables users to manage their digital subscriptions for services like Netflix, Spotify, GitHub, AWS, and more. In Sprint 5, the project was refactored and expanded to support a scalable API-first architecture, advanced authentication, role-based access, and comprehensive reporting and export features.

### Key Features (Sprint 5)

- OAuth2 API Authentication with Laravel Passport
- Role-based access control (user/admin) with comprehensive policies
- Full REST API for services and subscriptions management
- Monthly and annual billing cycle management with automated advancement
- User data isolation and security at all API layers
- Form Request validation with strong password policies and business rules
- Event-driven architecture for billing notifications and audit logging
- Expense reporting and CSV export endpoints with filtering capabilities
- Comprehensive test coverage for all API endpoints and business logic
- API Resources for consistent JSON response formatting
- Modern web interface with Blade templates and Tailwind CSS (secondary to API)

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

- PHP 8.2 or higher with required extensions:
  - OpenSSL PHP Extension
  - PDO PHP Extension
  - Mbstring PHP Extension
  - Tokenizer PHP Extension
  - XML PHP Extension
  - Ctype PHP Extension
  - JSON PHP Extension
- Composer (dependency manager for PHP)
- Node.js and npm (for asset compilation)
- Database: MySQL 8.0+ or SQLite 3.8.8+
- Web Server: Apache, Nginx, or PHP built-in server

### Step-by-Step Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/leandrodsg/fullstackphp-sprint5.git
   cd fullstackphp-sprint5/TechSubs_API
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Install and Build Frontend Assets**
   ```bash
   npm install
   npm run build
   ```

4. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Setup**
   
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

6. **Run Database Migrations**
   ```bash
   php artisan migrate
   ```

7. **Seed Database (Recommended)**
   ```bash
   php artisan db:seed
   ```

8. **Install Passport and Generate Keys**
   ```bash
   php artisan passport:install
   ```

9. **Start Development Server**
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
-d "{\"name\":\"Netflix\",\"category\":\"Streaming\",\"price\":15.99,\"billing_cycle\":\"monthly\"}"
```

### List user services
```bash
curl -X GET http://localhost:8000/api/v1/services \
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

### Test user accounts
- Email: `admin@example.com` | Password: `AdminPassword@123` (role: admin)
- Email: `user@example.com` | Password: `UserPassword@123` (role: user)

### Service categories
- Streaming (Netflix, Spotify, YouTube Premium)
- Cloud Storage (Google Drive, Dropbox, OneDrive)
- Productivity (Office 365, Adobe Creative, Notion)
- Development (GitHub Pro, Heroku, AWS)

### Example service data
```json
{
  "name": "Netflix",
  "category": "Streaming",
  "price": 15.99,
  "billing_cycle": "monthly"
}
```

```json
{
  "name": "Adobe Creative Cloud",
  "category": "Productivity", 
  "price": 239.88,
  "billing_cycle": "annual"
}
```


## System Architecture and Features

### Database Schema

The application uses three core entities:

**Users Table**
- Primary authentication entity with email verification
- Implements strong password policies and security features
- Supports user registration, login, and password reset functionality

**Services Table**
- Stores digital service information (Netflix, Spotify, GitHub, etc.)
- Links to users through foreign key constraints with cascade deletion
- Includes service categories, pricing, and billing cycle information

**Subscriptions Table**
- Junction table connecting users to their active services
- Manages billing cycles (monthly and annual)
- Tracks subscription dates and automatic billing advancement
- Implements event logging for billing cycle changes

**Database Relationships**
- User hasMany Services and Subscriptions (one-to-many)
- Service belongsTo User with ownership validation
- Subscription belongsTo both User and Service
- Foreign key constraints ensure data integrity and cascade operations

### Model Architecture

**User Model**
- Implements OAuth2 authentication with Laravel Passport for API access
- Enforces strong password policies and security validations
- Defines relationships with services and subscriptions
- Includes user data isolation methods

**Service Model**
- Manages digital service information with user ownership
- Implements category validation and business rules
- Enforces unique service names per user
- Handles service-specific billing configurations

**Subscription Model**
- Contains core business logic for billing cycle management
- `advanceOneCycle()` method handles automatic billing progression
- Supports multiple billing frequencies (monthly, annual)
- Integrates with event system for billing notifications

**Model Features**
- Mass assignment protection via `fillable` arrays
- Eloquent relationships for data integrity
- Custom scopes for user data isolation
- Business logic encapsulation within models
- Consistent naming conventions following Laravel standards

### Controller and Validation Implementation

**Controller Structure**
- Resource controllers for standard CRUD operations (ServiceController, SubscriptionController)
- Controllers handle HTTP logic while delegating business operations to models
- Consistent error handling and user feedback across all controller actions
- User authorization checks ensuring data isolation and security
- Security-first approach with user isolation integrated into every controller method

**Form Request Validation System**
- Dedicated Form Request classes (ServiceStoreRequest, ServiceUpdateRequest, SubscriptionStoreRequest)
- Centralized validation logic with custom rules and comprehensive error messages
- User-scoped validation ensuring data uniqueness per user rather than globally
- Business rule enforcement including minimum pricing and category constraints
- Security validations preventing cross-user data access attempts

### API-First Design

- All core features are exposed via RESTful API endpoints (see docs for details)
- OAuth2 authentication and role-based access for secure API usage
- Standardized JSON responses and error handling

#### Example Endpoints

- `POST /api/v1/register` — User registration
- `POST /api/v1/login` — User login
- `GET /api/v1/services` — List user services
- `POST /api/v1/services` — Create new service
- `GET /api/v1/subscriptions` — List user subscriptions
- `POST /api/v1/subscriptions` — Create new subscription
- `GET /api/v1/reports/my-expenses` — Expense report
- `GET /api/v1/reports/my-expenses/export` — Export expenses as CSV

See the `docs/` folder for full API documentation and request/response examples.

### Frontend Architecture and User Experience

**Template System**
- Master layout ensuring consistent navigation and authentication states
- Reusable Blade components for forms, data tables, and error displays
- Component-based architecture promoting code reusability
- Responsive design principles using Tailwind CSS utility classes

**User Experience Features**
- Real-time form validation with immediate feedback
- Contextual flash messages for all user actions
- Confirmation dialogs for destructive operations
- Accessible forms with proper ARIA labels and keyboard navigation
- Loading states and comprehensive error handling
- Mobile-first responsive design approach

### Security and Authentication System

OAuth2 Authentication with Laravel Passport
- Personal Access Tokens for API authentication
- Token-based authentication for all API endpoints
- Secure token management with automatic expiration
- Client credentials for machine-to-machine communication

Role-Based Access Control
- User roles: `user` and `admin` with distinct permissions
- Policy-based authorization for all resources
- Resource ownership validation ensuring users can only access their own data
- Admin privileges for system-wide operations and reporting

API Security Features
- Request validation through Form Request classes
- Strong password policies with custom validation rules
- Rate limiting on API endpoints
- CORS configuration for cross-origin requests
- Input sanitization and validation at all entry points

### Testing and Quality Assurance

**Testing Strategy**
- Complete test coverage with feature and unit tests
- Feature tests validating end-to-end user workflows
- Unit tests for business logic including billing cycle calculations
- Database factories and seeders providing consistent test data
- Test-driven development approach for new feature implementation

**Event-Driven Architecture**
- Events and listeners system for subscription billing advances
- Automatic logging of critical business operations
- Decoupled architecture enabling easy feature extensions
- Observer pattern implementation for model lifecycle management

**Code Quality Standards**
- Domain-driven design principles in model organization
- Service layer separation for complex business operations
- Consistent coding standards and comprehensive documentation
- Performance optimization for database queries and operations
- Error handling and user feedback systems throughout the application

### Business Logic and Advanced Features

**Billing Cycle Management**
- Support for monthly and annual billing periods
- Automatic billing cycle advancement with precise date calculations
- Subscription cost tracking and renewal automation
- Business rule enforcement for billing period constraints

**Advanced Database Features**
- Soft deletes enabling audit trails and data recovery
- Automatic timestamps for creation and modification tracking
- Model observers for automatic logging and event triggering
- Optimized database queries with proper indexing
- Database seeders and factories for development and testing environments

**Data Management**
- User data isolation ensuring complete privacy compliance
- Service categorization and advanced filtering capabilities
- Automated cleanup routines for expired subscriptions
- Foreign key constraints with cascade deletion for data integrity
- Scoped queries for enhanced performance and security

## Technical Implementation

### Technology Stack

Backend Framework
- Laravel 12 - Modern PHP framework with robust ecosystem
- PHP 8.3+ - Latest PHP version with performance improvements
- MySQL 8.0 - Relational database with JSON support
- Laravel Passport - OAuth2 server implementation for API authentication

API Architecture
- RESTful API design following industry standards
- JSON API responses with consistent formatting
- API Resources for data transformation and presentation
- Form Request validation for input validation and business rules
- Policy-based authorization for fine-grained access control

Development Tools
- Composer - PHP dependency management
- Artisan CLI - Laravel's command-line interface
- PHPUnit - Testing framework with comprehensive test coverage
- Laravel Factories - Database seeding and testing data generation
- Event-driven architecture - Decoupled system components

Frontend (Secondary)
- Blade Templates - Laravel's templating engine
- Tailwind CSS - Utility-first CSS framework
- Vite - Modern build tool for asset compilation
- Alpine.js - Lightweight JavaScript framework for interactivity