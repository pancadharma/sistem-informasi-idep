# Gemini CLI Configuration for sistem-informasi-idep

## Project Overview
- Framework: Laravel 10
- Purpose: IDEP NGO Training Management System
- Database: MySQL
- Packages: spatie/laravel-permission for role-based access control

## Directory Structure
- Controllers: `/project/app/Http/Controllers/Admin/` for admin-related controllers
- Models: `/project/app/Models/`
- Views: `/project/resources/views/admin/` for admin interfaces
- Routes: `/project/routes/web.php` for web routes
- Coding Style: PSR-12
- View Style: Blade templates with Bootstrap 5 for styling

## Instructions for Gemini CLI
- Generate controllers in `project/app/Http/Controllers/Admin/` with namespace `App\Http\Controllers\Admin`.
- Place models in `project/app/Models/` with namespace `App\Models`.
- Create Blade views in `project/resources/views/admin/` with lowercase folder names (e.g., `resources/views/admin/roles/`).
- Use Spatie Laravel Permission for role and permission management.
- Include middleware `Spatie\Permission\Middlewares\RoleMiddleware` for access control.
- Follow Laravel naming conventions (e.g., singular `Role` for model, plural `roles` for routes/views).
- See if the table structure here at `project\database\idep\sql-idep-structure.sql` to find the matches table used by the package spatie (there is possible modification already using this https://github.com/gedeeinstein/laravel-permission based on spatie/laravel-permission) 


