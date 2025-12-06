# Panduan Setup Development dengan Docker

Panduan ini digunakan untuk menjalankan project Sistem Informasi IDEP menggunakan Docker.
Environment ini sudah mencakup:

- **App**: PHP 8.3 (Laravel)
- **Web Server**: Nginx
- **Database**: MariaDB 10.5
- **Tools**: Composer, Node.js, NPM
- **Helper**: PHPMyAdmin

---

## 1. Prasyarat

Pastikan di komputer Anda sudah terinstal:

- [Docker Desktop](https://docs.docker.com/get-docker/) (Windows/Mac) atau Docker Engine & Compose (Linux)
- Git

### Cek Instalasi

Buka terminal dan jalankan:

```bash
docker --version
docker compose version
```

---

## 2. Instalasi Awal (First Time Setup)

Ikuti langkah ini jika Anda baru pertama kali clone repository ini atau baru setup ulang.

### Langkah 0: Masuk ke Direktori Project

File konfigurasi Docker terletak di dalam folder `project`. Pastikan Anda masuk ke folder tersebut sebelum menjalankan perintah Docker.

```bash
cd project
```

### Langkah 1: Setup Environment Variable

Copy file `.env.example` menjadi `.env`.

```bash
cp .env.example .env
```

**PENTING:** Buka file `.env` yang baru dibuat dan sesuaikan konfigurasi database agar terhubung ke container database Docker.

Cari bagian database dan ubah menjadi seperti ini:

```ini
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_password
```

> [!NOTE]
>
> - `DB_HOST=db`: Mengarah ke nama service database di `docker-compose.yml`. Jangan gunakan `localhost` atau `127.0.0.1` untuk `DB_HOST` saat aplikasi berjalan di dalam Docker.
> - Credential (DB_DATABASE, USERNAME, PASSWORD) disesuaikan dengan default di `docker-compose.yml`.

### Langkah 2: Jalankan Container

Perintah ini akan mendownload image dan membangun container. Pastikan internet stabil.

```bash
docker compose up -d --build
```

{{ ... }}
docker compose exec app php -d memory_limit=512M artisan migrate --seed

````

---

## 3. Workflow Harian (Daily Usage)

### Menyalakan Project

Masuk ke folder `project` lalu jalankan:

```bash
cd project
docker compose up -d

Akses aplikasi di:

- **Web App**: [http://localhost](http://localhost)
- **PHPMyAdmin**: [http://localhost:8080](http://localhost:8080)

### Mematikan Project

```bash
docker compose stop
{{ ... }}
docker compose exec app npm run dev
```

---

## 4. Daftar Perintah Berguna

Jangan jalankan `php artisan` di terminal host laptop Anda. Gunakan prefix `docker compose exec app`.

| Aksi                         | Perintah Docker                                                      |
| :--------------------------- | :------------------------------------------------------------------- |
| **Buat Controller**          | `docker compose exec app php artisan make:controller NamaController` |
| **Buat Model**               | `docker compose exec app php artisan make:model NamaModel -m`        |
| **Composer Update**          | `docker compose exec app composer update`                            |
| **Clear Cache**              | `docker compose exec app php artisan optimize:clear`                 |
| **Masuk Terminal Container** | `docker compose exec app bash`                                       |
| **Lihat Log Error**          | `docker compose logs -f app`                                         |

---

## 5. Troubleshooting (Masalah Umum)

### Q: Error "Connection Refused" atau "Name Resolution" saat Migrate?

**A:**

1. Pastikan container database hidup: `docker compose ps`.
2. Jika mati, cek log: `docker compose logs db`.
3. Pastikan `.env` berisi `DB_HOST=db`.

### Q: Error "Allowed memory size exhausted"?

**A:** Tambahkan flag `-d memory_limit=512M` (atau lebih besar) pada perintah php artisan Anda.
Contoh: `docker compose exec app php -d memory_limit=512M artisan migrate`

### Q: Error "Permission Denied" saat upload file atau buka web?

**A:** Izin folder `storage` terreset. Jalankan ulang:
`docker compose exec app chmod -R 777 storage bootstrap/cache`

### Q: Port Conflict (Bind for 0.0.0.0:80 failed)?

**A:** Port 80 (Web) atau 3306 (MySQL) atau 8080 (PMA) mungkin sedang dipakai aplikasi lain di laptop Anda (misal XAMPP atau Laragon). Matikan aplikasi tersebut lalu jalankan `docker compose up -d` lagi.
