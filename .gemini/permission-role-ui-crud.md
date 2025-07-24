# Gemini CLI Configuration for sistem-informasi-idep

## Project Overview
- Framework: Laravel 10
- Purpose: Village information system for managing population, news, assets, and permissions
- Database: MySQL
- Packages: spatie/laravel-permission for role-based access control

## Directory Structure
- Controllers: `app/Http/Controllers/Admin/` for admin controllers (namespace `App\Http\Controllers\Admin`)
- Models: `app/Models/` (namespace `App\Models`)
- Views: `resources/views/admin/` for admin Blade views
- Routes: `routes/web.php` for web routes, `routes/api.php` for API routes
- JavaScript: `resources/js/` for frontend scripts
- Coding Style: PSR-12 for PHP, Bootstrap 5 for UI, jQuery for AJAX
- View Style: Blade templates with Bootstrap 5 and jQuery for dynamic interactions

## Instructions for Gemini CLI
- Generate controllers in `app/Http/Controllers/Admin/` with namespace `App\Http\Controllers\Admin`.
- Place models in `app/Models/` with namespace `App\Models`.
- Create Blade views in `resources/views/admin/` (e.g., `resources/views/admin/permissions/` for permissions).
- Use Spatie Laravel Permission for permission management.
- Include `role:admin` middleware for admin routes.
- Use jQuery AJAX for dynamic CRUD operations without page reloads.
- Follow Laravel naming conventions (singular `Permission` for model, plural `permissions` for routes/views).
- Ensure CSRF tokens are included in AJAX requests.
- See if the table structure here at `project\database\idep\sql-idep-structure.sql` to find the matches table used by the package spatie (there is possible modification already using this https://github.com/gedeeinstein/laravel-permission based on spatie/laravel-permission) 

