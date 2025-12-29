# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

IDEP Information System is a Laravel 10.48.12 application for managing training programs and activities for a non-profit organization. The system handles program management, activity tracking, beneficiary management, and reporting with geographic coverage mapping.

## Development Commands

### Environment Setup
```bash
cd project
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

### Development Server
```bash
php artisan serve
npm run dev          # Frontend development with Vite
```

### Build & Deploy
```bash
npm run build        # Production build
composer dump-autoload
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Testing
```bash
php artisan test     # Run all tests
./vendor/bin/phpunit # Alternative test runner
```

### Database Operations
```bash
php artisan migrate:fresh --seed  # Reset database with seed data
php artisan db:seed               # Seed database only
php artisan make:migration        # Create new migration
```

### Code Quality
```bash
./vendor/bin/phpunit --coverage-html  # Generate coverage report
php artisan queue:work           # Process queued jobs
```

## Architecture Overview

### Core Business Logic
The application follows a hierarchical program structure:
- **Program** � **Program_Goal** � **Program_Objektif** � **Program_Outcome** � **Program_Outcome_Output** � **Program_Outcome_Output_Activity** � **Kegiatan**

### Key Models & Relationships
- **Program**: Central entity with file uploads, donor relationships, geographic coverage
- **Kegiatan**: Activities with multiple specialized types (Training, Assessment, etc.)
- **User**: Role-based access control with permissions
- **Geographic Hierarchy**: Country � Province � District � Sub-district � Village � Hamlet

### File Management
- Uses Spatie Media Library with custom disks
- Files stored in `public/uploads/` with organized structure
- Custom path generators for different entity types
- Maximum file size: 50MB for most uploads

### Authentication & Authorization
- Custom Spatie Permission fork (gedeadisurya/laravel-permission)
- Role-based access control with granular permissions
- Activity logging for audit trail

## Important Code Patterns

### File Uploads
File uploads use the `MediaUploadingTrait` and follow this pattern:
```php
// Upload to program-specific collection
->toMediaCollection('program_' . $program->id, 'program_uploads');

// With custom properties
->withCustomProperties([
    'keterangan' => $caption,
    'user_id' => auth()->user()->id,
    'original_name' => $originalName,
    'extension' => $extension
])
```

### Controller Structure
Controllers follow these patterns:
- **Admin controllers** in `app/Http/Controllers/Admin/`
- **API controllers** in `app/Http/Controllers/API/`
- Permission checks using `can()` middleware
- Consistent error handling with try-catch blocks

### View Organization
- **AdminLTE** based admin interface
- Views organized by feature: `tr/` (transactional), `master/` (master data)
- Tab-based interfaces for complex forms
- Bootstrap 5 with custom Sass compilation

### Database Naming
- Tables prefixed with `tr` for transactional data
- Master data tables prefixed with `m`
- Pivot tables follow Laravel conventions

### Auditing and Activity Tracking
- Uses `Auditable` trait for automatic model change logging
- Custom `AuditLog` model tracks all CRUD operations
- Logs include description, subject_id, subject_type, user_id, and properties

## Key Configuration

### Filesystem Disks
- `program_uploads`: `/uploads/program/`
- `dokumen_pendukung`: `/uploads/kegiatan/dokumen_pendukung/`
- `media_pendukung`: `/uploads/kegiatan/media_pendukung/`

### Media Collections
- `program_{id}`: Program-specific files
- `file_pendukung_program`: Legacy program files
- `dokumen_pendukung`: Supporting documents
- `media_pendukung`: Supporting media

### Permissions
- Custom permission system with role inheritance
- Permissions follow `feature_action` naming convention
- Super admin bypass with user ID 1

### Panel Configuration
- Default date format: `Y-m-d`
- Default time format: `H:i:s`
- Primary language: Indonesian (`id`)
- Available languages: English (`en`), Indonesian (`id`)

## Development Notes

### Branch Structure
- Main development branch: `main`
- Feature branches should follow naming conventions
- Current active development: `dashboard-v4`

### Testing Approach
- PHPUnit for unit and feature tests
- Test files in `tests/Feature/` and `tests/Unit/`
- Database transactions for test isolation
- SQLite for testing database

### Known Issues & Solutions
- **Property Access in show2.blade.php**: Using `property_exists()` with Eloquent models returns `false` even when attributes exist. Replace with proper Laravel attribute existence checks.
- **Dynamic Form Field Mapping**: Form uses JavaScript formFieldMap to map jenis kegiatan IDs to field prefixes. Controller uses getTypeSpecificFields() to validate and filter update fields.
- **Field Validation**: Controller properly updates jenis kegiatan specific tables using model mapping and updateOrCreate() method.

### Frontend Development
- Vite for asset compilation
- Bootstrap 5 with AdminLTE theme
- jQuery for DOM manipulation and AJAX
- Chart.js for data visualization
- Material Icons for UI components

### Security Considerations
- Input validation using Form Request classes
- File type restrictions for uploads
- Role-based access control throughout
- Audit logging for sensitive operations
- Custom permission system with role inheritance

### Database Architecture
- Hierarchical structure for program management
- Geographic relationships from country to hamlet level
- Beneficiary tracking with demographic breakdowns
- Activity types with specialized models for each type
- Progress tracking for program targets

### Special Features
- Media library with image conversions (thumbnail, preview)
- Custom export functionality for various data formats
- Geographic coverage mapping capabilities
- Beneficiary demographics tracking by gender and age group
- Dynamic activity types with associated specialized models

## Important Dependencies

### Core Laravel Packages
- `laravel/framework`: ^10.10
- `laravel/sanctum`: ^3.3
- `spatie/laravel-medialibrary`: ^11.7
- `spatie/laravel-activitylog`: ^4.9

### Custom Forks
- `gedeadisurya/laravel-permission`: Custom Spatie Permission fork
- `gedeadisurya/laravel-medialibrary-path-generators`: Custom path generators

### Reporting and Export
- `maatwebsite/excel`: ^3.1 (Excel exports)
- `barryvdh/laravel-dompdf`: ^3.1 (PDF generation)
- `phpoffice/phpword`: 1.1 (Word document generation)

### UI Components
- `jeroennoten/laravel-adminlte`: ^3.11 (AdminLTE theme)
- `bootstrap`: ^5.2.3 (Bootstrap 5)
- `chart.js`: ^4.4.9 (Data visualization)
- `tabulator-tables`: ^6.2.1 (Data tables)

### Development Tools
- `laravel/pint`: ^1.0 (Code styling)
- `fakerphp/faker`: ^1.9.1 (Test data generation)
- `laravel/sail`: ^1.18 (Docker support)

## File Upload Patterns

### Program Files
```php
->toMediaCollection('program_' . $program->id, 'program_uploads');
```

### Activity Files
```php
->toMediaCollection('dokumen_pendukung', 'kegiatan_uploads');
->toMediaCollection('media_pendukung', 'kegiatan_uploads');
```

### User Files
```php
->addMediaCollection($user->username)
    ->singleFile()
    ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif']);
```