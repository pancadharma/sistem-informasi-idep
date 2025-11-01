 # Pemetaan Proyek Laravel — IDEP
 
 Dokumen ini merangkum struktur, dependensi, dan alur kode utama berdasarkan penelusuran kode saat ini (Laravel 10, PHP 8.1).
 
 ## Ringkasan
 - Framework: Laravel 10 (`laravel/framework ^10.10`), PHP `^8.1`
 - Locale/Timezone: `id`, `Asia/Singapore`
 - UI/Admin: Laravel UI (Auth routes), AdminLTE (`jeroennoten/laravel-adminlte`), DataTables (`yajra/laravel-datatables-oracle`)
 - Auth: Sanctum; roles/permissions via fork `gedeadisurya/laravel-permission` (konfigurasi model/table khusus)
 - Media: Spatie Medialibrary (`spatie/laravel-medialibrary`), custom PathGenerators; beberapa disk lokal publik di `config/filesystems.php`
 - Observability: spatie/laravel-activitylog, debugbar (dev)
 - Pola: sebagian repository pattern (KomponenModel), selebihnya controller langsung Eloquent/DB
 
 ## Dependensi Utama (composer)
 - HTTP/Utility: `guzzlehttp/guzzle`, `spatie/laravel-ignition`
 - PDF/Excel/Word: `barryvdh/laravel-dompdf`, `maatwebsite/excel`, `phpoffice/phpword`
 - Charts: `laraveldaily/laravel-charts`
 - Dev: `barryvdh/laravel-debugbar`, `laravel/pint`, `phpunit ^10`
 
 ## Struktur Direktori Kunci
 - `app/Http/Controllers`: Admin, API, KomponenModel; `HomeController`, `MealsDashboardController`
 - `app/Repositories`: `BaseRepository*`, `KomponenModelRepository*` (dibind di `App\Providers\RepositoryServiceProvider`)
 - `app/Jobs`: `ProcessProgramFiles`, `ProcessKegiatanFiles`
 - `app/Models`: sangat banyak model domain (wilayah, program, kegiatan, meals, master data)
 - `app/Http/Requests`: Store/Update FormRequest untuk berbagai resource (Program, Kegiatan, Wilayah, dll.)
 - `app/Http/Resources`: `ProgramResource`, `KegiatanResource`, `ProvinsiResource`
 - `resources/views`: admin, dashboard, report, tr, layouts, auth
 - `routes`: `web.php` (banyak UI + API-like), `api.php` (dashboard API, admin API), `channels.php`, `console.php`
 
 ## Konfigurasi Relevan
 - `config/app.php`: timezone `Asia/Singapore`, locale `id`. Provider tambahan: `Yajra\DataTables\DataTablesServiceProvider`, `App\Providers\RepositoryServiceProvider`
 - `config/auth.php`: guard `web` (session), provider `users` via `App\Models\User`
 - `config/filesystems.php`:
   - Disk publik khusus: `program_uploads` (`/uploads/program`), `kegiatan_uploads`, `media_pendukung`, `dokumen_pendukung`, `userprofile`
   - Pastikan `php artisan storage:link` sesuai kebutuhan akses publik
 - `config/permission.php` (fork): model `GedeAdi\Permission\*`, tabel `roles`, `permissions`, `permission_role`, `model_has_roles`, `model_has_permissions`
 
 ## Pemetaan Route
 - `routes/web.php`:
   - Auth::routes(['register' => false]); root dialihkan ke `HomeController@index` dalam middleware `auth`
   - Modul admin: master wilayah, master data, kegiatan, program, report, dashboard, dsb.
   - Program: `Route::resource('program', ProgramController::class)` + API helper (pendonor, staff, partner, peran), details, media, dashboard
   - Catatan: terdapat duplikasi deklarasi route (mis. `kegiatan.destroy` dan `kategoripendonor` terdaftar 2x) — potensi konflik
   - Terdapat “API-like endpoints” berprefix `kegiatan/api/*` yang ditempatkan di `web.php`
 - `routes/api.php`:
   - `auth:sanctum` `/user`
   - Group `admin` (middleware `role:admin`) untuk permissions/roles2
   - Group `dashboard`: `komodel-v4`, KPI, geographic, components, filter, export pdf
   - Kegiatan media endpoints: store/delete media, temp files, get hasil
 - `routes/channels.php`: channel user default
 - `routes/console.php`: command `inspire`
 
 ## Controller, Service/Repository
 - `App\Providers\RepositoryServiceProvider`: bind `BaseRepositoryInterface` → `BaseRepository`, `KomponenModelRepositoryInterface` → `KomponenModelRepository`
 - `KomponenModel\DashboardKomponenModelV4Controller`: injeksi Repository, gunakan `getDashboardData($filters)` untuk data
 - Banyak controller Admin lain langsung menggunakan Eloquent/DB (belum konsisten memakai service/repository)
 
 ## Model, Validasi, Otorisasi
 - Models: cakupan luas (wilayah, program, kegiatan, meals, master)
 - Policies: tidak ditemukan `app/Policies`; otorisasi mengandalkan Gate/Permission, contoh `Gate::denies(...)`
 - FormRequest: tersedia lengkap untuk Store/Update berbagai resource
   - Contoh `StoreProgramRequest`: `Gate::allows('program_create')` (kecuali user id 1), validasi arrays + file (`mimes`, `max`)
 - Permission: fork `gedeadisurya/laravel-permission` — pastikan migrasi/seeder sesuai (tabel `permission_role` bukan `role_has_permissions`)
 
 ## Resources & Views
 - API Resources: `ProgramResource`, `KegiatanResource`, `ProvinsiResource` untuk output terstruktur
 - Views: modul admin, dashboard, report, serta folder `tr` (kemungkinan modul transactional tertentu)
 
 ## Jobs & Media
 - `ProcessProgramFiles`:
   - Menerima `Program`, array path file sementara, dan caption
   - Mengunggah via Medialibrary ke koleksi `program_{id}` di disk `program_uploads`
   - Pembersihan file temp, logging error jika gagal
 - Custom PathGenerators: `app/Services/MediaLibrary/*PathGenerator.php`
 
 ## Testing
 - `tests/Feature/ProgramCreationRefactorTest`:
   - Menguji endpoint `program.store` → ekspektasi HTTP 201 JSON, create record di DB, pivot relasi (`trprogrampartner`, `trprogramuser`)
   - Memastikan job `ProcessProgramFiles` didispatch
   - Perlu memastikan route name, response code, dan payload sesuai agar test lulus
 - `tests/Feature/ProgramObjectiveTest`, `tests/Unit/ExampleTest`
 
 ## Temuan & Hotspots
 1) Duplikasi route di `web.php` (contoh: `kegiatan.destroy`, `kategoripendonor`) — berisiko bentrok/ambigu  
 2) API-like routes di `web.php` — pertimbangkan relokasi ke `api.php` (middleware API + rate limit)  
 3) Pola Service/Repository belum merata — controller cenderung “gemuk”  
 4) Timezone `Asia/Singapore` — cek kebutuhan lokal (umumnya `Asia/Jakarta`)  
 5) Permission fork — pastikan keselarasan antara gate/perms di seeder dan pemeriksaan di controller/FormRequest  
 
 ## Rekomendasi Next Steps
 - Bersihkan duplikasi route (surgical edit, jaga kompatibilitas nama route)
 - Pindahkan endpoint API ke `routes/api.php` + middleware yang tepat
 - Tambah Policies untuk model kunci (Program/Kegiatan), gunakan `$this->authorize()` di controller
 - Standarkan controller ke Service/Repository untuk logic kompleks
 - Tambah test: validasi request, policy, dan serialisasi resource
 - Verifikasi konfigurasi permission (tabel & model) dan storage link untuk media publik
 
 ## Verifikasi Cepat (Artisan)
 - Versi & env: `php artisan about`
 - Peta route: 
   - `php artisan route:list --name=program`
   - `php artisan route:list --path=dashboard`
   - `php artisan route:list --path=kegiatan`
 - Testing (subset): 
   - `php artisan test --filter=ProgramCreationRefactorTest`
   - `php artisan test --filter=ProgramObjectiveTest`
 - Format/Syntax: 
   - `vendor/bin/pint --test`
   - `php -l path/to/changed.php`
 
 ---
 Dokumen ini bersifat ringkasan; detail lebih lanjut tersedia di masing-masing file terkait.
 
