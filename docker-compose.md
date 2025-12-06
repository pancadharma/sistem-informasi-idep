Tentu, ini adalah draf lengkap file `docker-compose.md` (atau `README_DOCKER.md`) yang bisa Anda letakkan di root project.

Panduan ini dirancang agar developer baru tinggal **Clone -\> Run -\> Code**, lengkap dengan penanganan masalah memori yang tadi kita bahas.

-----

### File: `TUTORIAL-DOCKER.md`

Silakan copy konten di bawah ini:

````markdown
# Panduan Setup Development dengan Docker

Panduan ini digunakan untuk menjalankan project Sistem Informasi IDEP menggunakan Docker. Environment ini sudah mencakup:
- **App**: PHP 8.3 (Laravel)
- **Web Server**: Nginx
- **Database**: MariaDB 10.5
- **Database GUI**: PHPMyAdmin

## 1. Prasyarat
Pastikan di komputer Anda sudah terinstal:
- [Docker](https://docs.docker.com/get-docker/)
- [Git](https://git-scm.com/)

---

## 2. Instalasi Awal (First Time Setup)

Ikuti langkah ini jika Anda baru pertama kali clone repository ini.

### Langkah 1: Clone & Setup Environment
Copy file environment bawaan dan sesuaikan isinya.

```bash
cp .env.example .env
````

**PENTING:** Buka file `.env` dan pastikan konfigurasi database sesuai dengan container:

```ini
DB_CONNECTION=mysql
DB_HOST=db             <-- JANGAN localhost, gunakan 'db'
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_password
```

### Langkah 2: Jalankan Container

Perintah ini akan mendownload image dan membangun container.

```bash
docker compose up -d --build
```

*Tunggu hingga semua proses selesai.*

### Langkah 3: Install Dependencies

Jalankan perintah ini untuk menginstal vendor PHP dan Node modules **di dalam container**.

```bash
# Install PHP Dependencies
docker compose exec app composer install

# Install Node Dependencies & Build Assets
docker compose exec app npm install
docker compose exec app npm run build
```

### Langkah 4: Setup Aplikasi

Generate key dan atur permission folder agar tidak error saat upload/logging.

```bash
# Generate Key
docker compose exec app php artisan key:generate

# Fix Permission Storage (Wajib di Linux/Mac)
docker compose exec app chmod -R 777 storage bootstrap/cache
```

### Langkah 5: Migrasi Database & Seeding

**Catatan:** Karena data seeder sangat besar (Wilayah Indonesia), kita perlu menaikkan limit memori PHP secara manual saat seeding.

```bash
docker compose exec app php -d memory_limit=512M artisan migrate --seed
```

-----

## 3\. Cara Penggunaan Harian

Setelah instalasi awal selesai, berikut cara kerja sehari-hari:

### Menyalakan Project

```bash
docker compose up -d
```

Akses di browser:

  - **Web App**: [http://localhost](https://www.google.com/search?q=http://localhost)
  - **PHPMyAdmin**: [http://localhost:8080](https://www.google.com/search?q=http://localhost:8080)

### Mematikan Project

```bash
docker compose stop
```

### Melihat Log (Debugging)

Jika ada error 500 atau masalah container:

```bash
docker compose logs -f app
```

-----

## 4\. Menjalankan Perintah Laravel (Artisan/Composer/NPM)

Jangan jalankan `php artisan` atau `npm` langsung di terminal laptop Anda (kecuali Anda punya PHP/Node versi sama di laptop). Gunakan `docker compose exec app` sebagai awalan.

**Format:**
`docker compose exec app [PERINTAH]`

**Contoh:**

1.  **Membuat Controller:**

    ```bash
    docker compose exec app php artisan make:controller HomeController
    ```

2.  **Update Composer:**

    ```bash
    docker compose exec app composer update
    ```

3.  **Compiling Assets (Vite/Tailwind) saat Development:**

    ```bash
    docker compose exec app npm run dev
    ```

4.  **Membersihkan Cache:**

    ```bash
    docker compose exec app php artisan optimize:clear
    ```

-----

## 5\. Troubleshooting Umum

**Q: Error "Connection Refused" ke Database?**
A: Pastikan di `.env` tertulis `DB_HOST=db`. Jika masih error, coba restart container database: `docker compose restart db`.

**Q: Error "Permission Denied" pada log/storage?**
A: Jalankan ulang perintah permission:
`docker compose exec app chmod -R 777 storage bootstrap/cache`

**Q: Error "Memory Exhausted" saat Seed/Export Excel?**
A: Tambahkan flag memory limit di perintah Anda:
`docker compose exec app php -d memory_limit=512M artisan [perintah]`

```

---

### Tips Tambahan untuk Anda:
Anda bisa menyimpan file ini dengan nama `README_DOCKER.md` di folder root project Anda, lalu commit ke Git. Ini akan sangat membantu tim Anda (atau Anda sendiri di masa depan) agar tidak lupa perintah-perintahnya.
```