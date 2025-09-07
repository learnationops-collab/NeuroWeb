# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

NeuroWeb is a Laravel 11 application that implements a role-based authentication system with three distinct user roles: estudiante (student), neuro_team, and admin. The application provides a dashboard and chat interface tailored to each user's role.

## Common Development Commands

### Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed the database (includes role setup)
php artisan db:seed --class=RolesTableSeeder
```

### Development Server
```bash
# Start Laravel development server
php artisan serve

# Start Vite development server (for frontend assets)
npm run dev

# Build production assets
npm run build
```

### Database Operations
```bash
# Create a new migration
php artisan make:migration create_table_name

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Seed specific seeder
php artisan db:seed --class=SeederName

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run tests with coverage
php artisan test --coverage
```

### Code Quality
```bash
# Format code using Laravel Pint
./vendor/bin/pint

# Run PHPUnit tests directly
./vendor/bin/phpunit
```

### Artisan Commands
```bash
# Create controller
php artisan make:controller ControllerName

# Create model with migration
php artisan make:model ModelName -m

# Create seeder
php artisan make:seeder SeederName

# Clear application cache
php artisan cache:clear

# Clear configuration cache
php artisan config:clear

# Clear view cache
php artisan view:clear
```

## Architecture Overview

### Authentication & Authorization System
The application implements a many-to-many relationship between Users and Roles:
- **User Model**: Located in `app/Models/User.php` with role relationship methods
- **Role Model**: Located in `app/Models/Role.php` for managing user permissions
- **Pivot Table**: `role_user` table connects users to their assigned roles
- **Default Roles**: estudiante, neuro_team, admin (seeded via `RolesTableSeeder`)

### Controller Structure
- **AuthController**: Handles registration and login (`app/Http/Controllers/Auth/`)
- **DashboardController**: Role-based dashboard rendering
- **ChatController**: Chat interface with role-aware functionality
- All controllers extend the base `Controller` class and use role-based view rendering

### Database Design
- Standard Laravel user authentication tables (users, password_reset_tokens, sessions)
- Custom roles table with unique name constraint
- Pivot table role_user for many-to-many relationship
- Migration files follow Laravel 11 anonymous class structure

### Frontend Architecture
- Blade templates in `resources/views/` with layout inheritance
- Vite for asset compilation (JavaScript/CSS)
- Role-based view rendering where userRole is passed to templates
- Auth views handle both login and registration functionality

### Route Structure
- Public routes: `/` (auth page)
- Authentication routes: `/login`, `/register`, `/logout`
- Protected routes (auth middleware): `/dashboard`, `/chat`
- Role-based content rendering within protected routes

### Key Business Logic
- New users are automatically assigned the 'estudiante' role during registration
- Authentication redirects to dashboard with role-based content
- User role determines interface and functionality available
- Session management with proper token regeneration for security

### Development Notes
- Uses Laravel 11 with PHP 8.2+ requirement
- Frontend build system uses Vite instead of Laravel Mix
- Role assignment happens during user creation in RegisterController
- All role checks use the User model's `hasRole()` method for consistency
