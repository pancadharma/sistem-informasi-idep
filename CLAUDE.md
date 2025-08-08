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
- **Program** → **Program_Goal** → **Program_Objektif** → **Program_Outcome** → **Program_Outcome_Output** → **Program_Outcome_Output_Activity** → **Kegiatan**

### Key Models & Relationships
- **Program**: Central entity with file uploads, donor relationships, geographic coverage
- **Kegiatan**: Activities with multiple specialized types (Training, Assessment, etc.)
- **User**: Role-based access control with permissions
- **Geographic Hierarchy**: Country → Province → District → Sub-district → Village → Hamlet

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

## Development Notes

### Branch Structure
- Main development branch: `main`
- Feature branches should follow naming conventions
- Current active development: `#100-v3-dashboard`

### Testing Approach
- PHPUnit for unit and feature tests
- Test files in `tests/Feature/` and `tests/Unit/`
- Database transactions for test isolation

### Frontend Development
- Vite for asset compilation
- Bootstrap 5 with AdminLTE theme
- jQuery for DOM manipulation and AJAX
- Chart.js for data visualization

### Security Considerations
- Input validation using Form Request classes
- File type restrictions for uploads
- Role-based access control throughout
- Audit logging for sensitive operations