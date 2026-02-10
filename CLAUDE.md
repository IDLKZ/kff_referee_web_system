# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

KFF Referee Web System is a Laravel 12 application for managing football (soccer) referees, matches, tournaments, and related logistics in Kazakhstan. It features role-based access control, multi-language support (Russian primary, Kazakh and English), and comprehensive match/protocol management.

## Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+), Livewire 4.1
- **Frontend**: Vite, Tailwind CSS 4, Alpine.js (via Livewire)
- **Database**: SQLite (default, configurable to MySQL)
- **ORM**: Eloquent with Reliese Laravel models

## Development Commands

### Initial Setup
```bash
composer setup    # Install dependencies, generate key, migrate, build assets
```

### Development
```bash
composer run dev   # Start all services: Laravel server, queue worker, pail logs, Vite dev server
```

### Individual Services
```bash
php artisan serve              # Laravel server
php artisan queue:listen       # Queue worker
php artisan pail               # Log viewer
npm run dev                    # Vite dev server with HMR
```

### Testing & Quality
```bash
composer test          # Run PHPUnit tests (equivalent to php artisan test)
./vendor/bin/pint      # Laravel Pint code formatter
```

### Production Build
```bash
npm run build          # Build assets for production
```

### Database Models
```bash
php artisan code:models --table=table_name  # Generate Eloquent models using Reliese
```

## Architecture

### Role-Based Access Control (RBAC)

The system has three main user groups (defined in `RoleConstants`):

| Group | Route Prefix | Roles |
|-------|--------------|-------|
| `administrator_group` | `/admin` | administrator, refereeing_department_head, finance_department_head |
| `kff_pflk_group` | `/kff` | refereeing_department_employee, finance_department_specialist, refereeing_department_logistician, refereeing_department_accountant |
| `judge_group` | `/referee` | soccer_referee |

Route protection uses middleware:
- `auth.active` - Requires authenticated, active user
- `role:{group}` - Requires user's role to belong to specified group
- `permission:{permission}` - Requires specific permission (see `PermissionConstants`)

### Authentication & Authorization

- **Login field detection**: Users can log in with email, phone, or username (auto-detected in `Login` Livewire component)
- **Permission checking**: Use `$user->hasPermission('resource.action')` method on User model
- **Role groups**: Access role group via `$user->role->group`
- **Dashboard routing**: After login, users are redirected to their group's dashboard

### Multi-Language System

- **Locales**: `ru` (Russian, default), `kk` (Kazakh), `en` (English)
- **Locale files**: `lang/{locale}/{domain}.php` (domains: auth, crud, ui, validation)
- **Switching**: Route `locale.switch` accepts locale parameter
- **Database translations**: Models with `title_ru`, `title_kk`, `title_en` columns (e.g., Role, Tournament)
- **Permission translations**: `PermissionConstants::tables()` and `PermissionConstants::actionTranslations()` provide localized strings

### Key Domain Models

Core entities (all in `app/Models/`):
- **Users**: `User` - with role, image (File), IIN (tax ID), birth_date
- **RBAC**: `Role`, `Permission`, `RolePermission`
- **Geography**: `Country`, `City`, `Stadium`
- **Football**: `Tournament`, `Season`, `Club`, `ClubType`, `ClubStadium`
- **Matches**: `MatchModel`, `MatchJudge`, `MatchLogist`, `MatchReport`, `MatchReportDocument`
- **Referees**: `JudgeType`, `JudgeCity`, `JudgeRequirement`
- **Logistics**: `Hotel`, `HotelRoom`, `Facility`, `Trip`, `TripHotel`, `TripMigration`, `TripDocument`
- **Operations**: `CategoryOperation`, `Operation`, `RoleOperation`, `MatchOperationLog`
- **Other**: `File`, `Notification`

### Constants

All constants are defined in `app/Constants/`:
- `RoleConstants` - Role values and groups
- `PermissionConstants` - All permissions with translations
- `CountryConstants`, `JudgeTypeConstants`, `TournamentConstants`, etc.

### Middleware (app/Http/Middleware)

- `CheckPermission` - Validates user has specific permission (403 if not)
- `EnsureAuthenticated` - Custom auth middleware
- `RedirectByRole` - Redirects based on role group
- `SetLocale` - Sets application locale from session/request

### Livewire Components

Located in `app/Livewire/`:
- `Auth/Login` - Login page with multi-field authentication
- `Admin/RoleManagement` - Admin role management interface

### Routes Structure

- `/login` - Guest login page
- `/` - Redirects to role-specific dashboard
- `/admin/**` - Administrator group routes
- `/kff/**` - KFF/PFLK group routes
- `/referee/**` - Referee group routes
- `/locale/{locale}` - Switch language

## Frontend

- **Styling**: Tailwind CSS 4 with Vite plugin
- **Entry points**: `resources/css/app.css`, `resources/js/app.js`
- **Livewire**: Provides reactive components without writing JavaScript
- **Toastr**: Flash notifications via `yoeunes/toastr` package
- **Views**: `resources/views/{admin,kff,referee,shared,auth}/`

## Database

- **Default**: SQLite (`database/database.sqlite`)
- **Migrations**: `database/migrations/`
- **Seeding**: Run via `php artisan db:seed`
- **Models**: Auto-generated by Reliese Laravel from database schema

## Important Notes

- All models were generated using Reliese Laravel - check existing patterns before modifying
- Permission constants follow pattern: `{resource}.{action}` (e.g., `roles.index`, `users.create`)
- Russian is the primary language - new UI text should include Russian translations
- User activation status (`is_active`) must be checked for authentication
- Soft deletes are enabled on User model
- Session storage uses database (check sessions table)
