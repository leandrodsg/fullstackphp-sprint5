# TechSubs - Digital Subscription Management System

TechSubs is a comprehensive web application built with Laravel 12 for managing digital service subscriptions.

## Project Overview

This application enables users to manage their digital subscriptions for services like Netflix, Spotify, GitHub, AWS, and other online platforms.

### Key Features

- User Authentication: Complete registration and login system with email verification
- Subscription Management: Full CRUD operations for services and subscriptions
- Billing Cycles: Support for monthly and annual billing periods
- User Data Isolation: Each user can only access their own data
- Form Validation: Validation using Laravel Form Requests
- Event System: Automated logging and business logic through events and listeners

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
   git clone https://github.com/leandrodsg/fullstackphp-sprint4.git
   cd fullstackphp-sprint4/TechSubs
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

7. **Seed Database (Optional)**
   ```bash
   php artisan db:seed
   ```

8. **Start Development Server**
   ```bash
   php artisan serve
   ```

The application will be available at `http://localhost:8000`

## System Architecture

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

Data modeling and business logic:

**User Model**
- Implements Laravel Breeze authentication with email verification
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

The application implements clean controller architecture with proper separation of concerns:

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

### Frontend Architecture and User Experience

The application provides a modern, responsive interface built with Laravel Blade and Tailwind CSS:

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

The application implements comprehensive security measures across all layers:

**Authentication Infrastructure**
- Laravel Breeze providing secure authentication foundation
- Email verification system preventing unauthorized account creation
- Strong password policies with custom validation rules
- Secure password reset functionality using time-limited tokens
- Route protection via middleware ensuring authenticated access

**Security Implementation**
- Multi-level user data isolation (database, model, and controller layers)
- CSRF protection on all form submissions
- Mass assignment protection in all Eloquent models
- Comprehensive input validation and sanitization
- Security by obscurity approach (404 responses instead of 403 for unauthorized access)
- Session management with secure cookie handling

### Testing and Quality Assurance

The application includes a comprehensive testing suite ensuring reliability and code quality:

**Testing Strategy**
- Complete test coverage with 27 tests and 104 assertions
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

The application implements sophisticated business logic for subscription management:

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

**Core Framework and Languages**
- Laravel 12 (PHP 8.2+) with modern framework features
- SQLite for development, MySQL for production deployment
- Blade templating engine with component-based architecture
- Tailwind CSS for utility-first responsive styling
- JavaScript with Alpine.js for interactive functionality

**Development and Build Tools**
- Composer for PHP dependency management
- NPM with Vite for modern asset bundling and hot reload
- Laravel Breeze for authentication scaffolding
- PHPUnit for comprehensive testing suite
- Artisan CLI for database migrations and custom commands