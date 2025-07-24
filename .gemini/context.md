# IDEP Information System - Root Context

## Project Overview
IDEP (Institut untuk Demokrasi dan Pemberdayaan) Information System is a Laravel-based training management system designed for non-profit organizations to manage training programs efficiently.

## Core Purpose
Sistem Informasi IDEP dirancang untuk:
- Mengelola informasi tentang berbagai program pelatihan yang ditawarkan
- Menyimpan rincian peserta, jadwal, dan lokasi pelatihan
- Memungkinkan pemantauan kemajuan peserta selama pelatihan
- Menyediakan laporan tentang kehadiran dan pencapaian peserta
- Menghasilkan laporan berkala mengenai kegiatan pelatihan
- Menyediakan analisis dan statistik untuk evaluasi program

## Technology Stack
- **Backend**: Laravel Framework (PHP)
- **Database**: MySQL
- **Frontend**: AdminLTE Template
- **JavaScript**: jQuery + AJAX for dynamic interactions
- **Styling**: AdminLTE CSS Framework (Bootstrap-based)
- **Package Manager**: Composer for PHP dependencies
- **Authentication**: Laravel built-in authentication system

## Key Features
- **Program Management**: Manage various training programs offered
- **Participant Management**: Store participant details, schedules, and locations
- **Progress Monitoring**: Track participant progress during training
- **Attendance Tracking**: Monitor and report attendance
- **Achievement Reports**: Generate reports on participant achievements
- **Periodic Reporting**: Generate regular reports on training activities
- **Analytics**: Provide analysis and statistics for program evaluation
- **AdminLTE Interface**: Intuitive and responsive user interface
- **Authentication System**: Secure access control for sensitive data
- **Integration Capability**: Ability to integrate with other applications/systems

## Development Principles
- Follow Laravel MVC architecture strictly
- Use AdminLTE components consistently
- Implement proper authentication and authorization
- Use jQuery/AJAX for seamless user experience
- Generate comprehensive reports for training management
- Ensure data security and privacy
- Maintain responsive design for all devices

## Code Style Guidelines
- PSR-12 coding standards for PHP
- Laravel naming conventions for models, controllers, migrations
- AdminLTE component structure for views
- jQuery best practices for frontend interactions
- Consistent indentation and commenting
- Meaningful variable and function names

## Security Requirements
- Laravel authentication for user access
- Role-based permissions for different user types
- CSRF protection on all forms
- Input validation and sanitization
- Secure password handling
- Data encryption where needed

## Performance Considerations
- Efficient database queries using Eloquent
- Proper indexing for frequently queried data
- Lazy loading for large datasets
- AdminLTE's optimized CSS/JS loading
- Image optimization for better performance
- Caching strategies for reports and analytics

## User Roles & Permissions
- **Super Admin**: Full system access
- **Admin**: Program and participant management
- **Staff**: Limited access to assigned programs
- **Trainer**: Access to assigned training sessions
- **Participant**: View own training progress and materials

## Reporting Requirements
- Attendance reports per program/session
- Participant achievement summaries
- Training program effectiveness analysis
- Monthly/quarterly activity reports
- Custom date-range reporting
- Export capabilities (PDF, Excel)

## AdminLTE Integration Notes
- Use AdminLTE's card components for content sections
- Implement AdminLTE's form styling consistently
- Utilize AdminLTE's navigation patterns
- Follow AdminLTE's color scheme and theming
- Use AdminLTE's plugin system for additional functionality

## Database Design Principles
- Normalize database structure for training management
- Implement proper foreign key relationships
- Use soft deletes for important records
- Create indexes for performance-critical queries
- Store audit trails for important changes

## jQuery/AJAX Patterns
- Use consistent error handling across all AJAX calls
- Implement loading states for better UX
- Validate forms on both client and server side
- Update UI dynamically without page reloads
- Handle CSRF tokens properly in AJAX requests

## Deployment Requirements
- PHP 8.1+ support
- MySQL 8.0+ database
- Web server (Apache/Nginx) configuration
- SSL certificate for secure data transmission
- Regular backup procedures for training data
- Environment-specific configuration management