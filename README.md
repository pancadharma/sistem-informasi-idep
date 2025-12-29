# IDEP Information System - Root Context

## Project Overview

IDEP Information System is a Laravel-based training management system designed for non-profit organizations to manage training programs efficiently.

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


## About IDEP-Training

Fitur utama dari Sistem Informasi IDEP meliputi:

### 1. **Manajemen Data Pelatihan**
   - Mengelola informasi tentang berbagai program pelatihan yang ditawarkan.
   - Menyimpan rincian peserta, jadwal, dan lokasi pelatihan.

### 2. **Pemantauan Kemajuan Peserta**
   - Memungkinkan pemantauan kemajuan peserta selama pelatihan.
   - Menyediakan laporan tentang kehadiran dan pencapaian peserta.

### 3. **Laporan Kegiatan**
   - Menghasilkan laporan berkala mengenai kegiatan pelatihan.
   - Menyediakan analisis dan statistik untuk evaluasi program.

### 4. **Antarmuka Pengguna yang Ramah**
   - Menggunakan antarmuka berbasis AdminLTE yang intuitif dan responsif.
   - Memudahkan pengguna dalam navigasi dan akses informasi.

### 5. **Keamanan Data**
   - Mengimplementasikan sistem autentikasi untuk melindungi data pengguna.
   - Menjamin bahwa hanya pengguna yang berwenang yang dapat mengakses informasi sensitif.

### 6. **Integrasi dengan Sistem Lain**
   - Kemampuan untuk terintegrasi dengan aplikasi atau sistem lain untuk memperluas fungsionalitas.

Fitur-fitur ini dirancang untuk mendukung organisasi nirlaba dalam meningkatkan efisiensi dan efektivitas program pelatihan mereka.



### Premium Partners

- **[Siva](https://www.instagram.com/agus.maharta/)**
- **[Panca Dharma](https://www.instagram.com/panca_dharma/)**
- **[Wirawan Wira](https://www.instagram.com/wirawan.wira/)**
- **[Gede Adi Surya](https://www.instagram.com/gedeadisurya)**

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
 
- duplicate the code, cd into project
- open terminal, run composer install
- run php artisan key:generate
- run php artisan:migrate to run migration and make database, make sure you update .env based on your sistem
- run php artisan db:seed to seed default data and super admin user



## Log

- cd to project & run composer install to install composer dependency

- Add LTE via https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
- Install plugin sweetalert2 by running -  php artisan adminlte:plugins install --plugin=sweetalert2
- use php artisan adminlte to see available command list    


## RUN
cp .env.example .env


# Prompt untuk Laravel dan Blade

1. **Membuat Komponen Blade**:
   - "Buatkan saya komponen Blade untuk menampilkan daftar produk dengan nama, harga, dan gambar."

2. **Formulir Pengguna**:
   - "Tulis formulir pendaftaran pengguna menggunakan Blade, dengan validasi untuk nama, email, dan password."

3. **Menggunakan Route dan Controller**:
   - "Tunjukkan cara membuat route dan controller untuk menampilkan halaman detail produk di Laravel."

4. **Menggunakan Layouts**:
   - "Buatkan saya layout Blade dasar untuk aplikasi Laravel dengan header, footer, dan section utama."

# Prompt untuk JavaScript (jQuery)

1. **Manipulasi DOM**:
   - "Tuliskan kode jQuery untuk menampilkan pesan 'Data berhasil disimpan' setelah formulir disubmit."

2. **AJAX Request**:
   - "Buatkan saya contoh jQuery AJAX request untuk mengambil data pengguna dari API dan menampilkannya di tabel."

3. **Event Handling**:
   - "Tulis kode jQuery untuk menangani klik pada tombol dan mengubah teks pada elemen tertentu."

4. **Validasi Formulir**:
   - "Buatkan validasi jQuery untuk memastikan semua field pada formulir diisi sebelum disubmit."

# Tips Tambahan
- **Spesifik**: Semakin spesifik Anda dalam permintaan, semakin baik hasilnya.
- **Contoh**: Sertakan contoh data atau struktur yang Anda inginkan untuk mendapatkan hasil yang lebih relevan.
- **Iterasi**: Jika hasil pertama tidak memuaskan, coba ubah prompt Anda sedikit untuk mendapatkan hasil yang lebih baik.


# Teknologi yang Digunakan dalam Pengembangan Sistem Informasi IDEP

## 1. Framework
- **Laravel**: Framework PHP yang digunakan untuk membangun aplikasi web dengan struktur yang rapi dan kemudahan dalam pengembangan.

## 2. Basis Data
- **MySQL**: Sistem manajemen basis data relasional yang digunakan untuk menyimpan data aplikasi.

## 3. Antarmuka Pengguna
- **AdminLTE**: Template dashboard berbasis HTML yang digunakan untuk membangun antarmuka pengguna yang responsif dan menarik.

## 4. Bahasa Pemrograman
- **PHP**: Bahasa pemrograman yang digunakan dalam pengembangan backend aplikasi.

## 5. Frontend
- **HTML, CSS, dan JavaScript**: Digunakan untuk membangun tampilan dan interaktivitas di sisi klien.

## 6. Tools dan Library Tambahan
- Berbagai alat dan pustaka tambahan yang mendukung pengembangan, seperti Composer untuk manajemen dependensi.