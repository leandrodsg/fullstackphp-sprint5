# TechSubs - Digital Subscription Management System

TechSubs is a web application built with Laravel for managing digital service subscriptions. This project was developed as part of a learning exercise to understand Laravel fundamentals, database relationships, and user authentication.

## About This Project

This application allows users to track their digital subscriptions like GitHub, AWS, and other online services. Each user can manage their own list of services and subscriptions after creating an account.

### Laravel

The first step was setting up a new Laravel project and connecting it to a MySQL database. This involved learning about Laravel's directory structure, environment configuration, and how migrations work to create database tables.

The basic Laravel installation came with user authentication tables, which became useful later when adding login functionality.

### Building the Database Structure

Two main tables were needed for this application:
- A `services` table to store information about different digital services (like Netflix, Spotify, etc.)
- A `subscriptions` table to connect users with their chosen services and track pricing

Each subscription belongs to one user and one service, while users and services can have many subscriptions.

The database uses foreign keys to maintain data integrity, meaning if a user is deleted, their subscriptions are automatically removed too.

### Creating Models and Relationships

The project implements Laravel's Eloquent ORM to create models that represent the database tables. The implementation includes:
- Service and Subscription models with proper `fillable` properties to control mass assignment
- Relationship definitions using `hasMany` and `belongsTo` methods to connect User, Service, and Subscription entities
- User model extended to include relationships with services and subscriptions
- Foreign key constraints implemented at both database and model levels for data integrity

### Setting Up Routes and Controllers

The application uses Laravel's resource routing system to handle CRUD operations:

Routes Implementation:
- Added resource routes for services: `Route::resource('services', ServiceController::class)`
- Added resource routes for subscriptions: `Route::resource('subscriptions', SubscriptionController::class)`
- Routes are defined in `routes/web.php`

Controllers Created:
- `ServiceController` with seven standard methods:
- `index()` - displays list of services
- `create()` - shows form to create new service
- `store()` - processes form submission and saves new service
- `show()` - displays individual service details
- `edit()` - shows form to edit existing service
- `update()` - processes edit form and updates service
- `destroy()` - deletes a service

- `SubscriptionController` with the same seven methods for subscription management

### Building the User Interface

The frontend uses Laravel's Blade templating system with Tailwind CSS for styling:
- Creating a master layout that all pages can extend
- Building forms for creating and editing services and subscriptions
- CSRF protection
- Displaying data in tables and handling user feedback with flash messages

### Adding User Authentication

Laravel Breeze was added to handle user registration and login:
- Ready-made login and registration forms
- Password reset functionality
- Middleware to protect routes that require authentication

### Implementing User Data Separation

To ensure each user can only access their own data, several modifications were made to the application:

Database Changes:
- Created a new migration to add a `user_id` column to the services table
- Added foreign key constraint linking services to users
- Set up cascade deletion so removing a user also removes their services

Model Updates:
- Added `user_id` to the Service model's fillable array
- Created a `belongsTo` relationship between Service and User models
- Updated the User model to include a `hasMany` relationship with services

Controller Modifications:
- Modified all ServiceController methods to filter results by authenticated user ID
- Updated `index()` to show only the current user's services
- Changed `store()` to automatically assign new services to the logged-in user
- Modified `show()`, `edit()`, `update()`, and `destroy()` methods to verify ownership before allowing access
- Added `Auth::id()` checks throughout the controller

Security Implementation:
- All service operations now require user authentication
- Attempting to access another user's service returns a 404 error
- Direct URL manipulation cannot bypass the ownership checks
- CSRF protection remains active on all forms

## Technical Details

### Built With
- Laravel 12 (PHP framework)
- MySQL (database)
- Blade templates (for HTML generation)
- Tailwind CSS (for styling)
- Laravel Breeze (for authentication)
