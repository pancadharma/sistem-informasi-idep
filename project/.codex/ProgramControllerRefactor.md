
# Tutorial Refactoring Metode `store` pada `ProgramController`

## Pendahuluan

Tutorial ini akan memandu Anda melalui proses refactoring metode `store` di `App\Http\Controllers\Admin\ProgramController`. Tujuannya adalah untuk mengadopsi arsitektur yang lebih modern dan tangguh, mirip dengan yang digunakan oleh `KegiatanController`.

Perubahan utama adalah memisahkan logika inti pembuatan program ke dalam `API Controller` tersendiri dan menangani proses unggah file secara asinkron menggunakan *Laravel Jobs & Queues*.

**Keuntungan dari Refactoring Ini:**

1.  **Respons Lebih Cepat:** Pengguna akan mendapatkan respons dari server secara instan tanpa harus menunggu proses unggah file selesai.
2.  **Arsitektur Lebih Bersih:** Memisahkan tanggung jawab (SoC) antara controller yang menangani HTTP request dari web dan controller API yang berisi logika bisnis inti.
3.  **Stabilitas & Skalabilitas:** Proses unggahan yang berat dialihkan ke *background worker*, mengurangi risiko *timeout* dan membuat aplikasi lebih mudah diskalakan.
4.  **Kode yang Dapat Digunakan Kembali:** Logika di API controller dapat dengan mudah dipanggil dari bagian lain aplikasi (misalnya, command-line atau aplikasi mobile).

---

## Prasyarat

Sebelum memulai, pastikan sistem antrian (Queue) Laravel Anda sudah dikonfigurasi.

1.  **Pilih Driver Antrian:** Buka file `.env` Anda dan atur `QUEUE_CONNECTION`. Untuk pengembangan, `database` adalah pilihan yang baik.
    ```
    QUEUE_CONNECTION=database
    ```

2.  **Siapkan Tabel Database (jika menggunakan driver `database`):**
    Jalankan perintah berikut di terminal Anda:
    ```bash
    php artisan queue:table
    php artisan migrate
    ```

---

## Langkah 1: Membuat API Controller Baru

Kita akan memindahkan semua logika inti dari `ProgramController` ke `API\ProgramController` yang baru.

1.  **Buat Controller Baru:**
    ```bash
    php artisan make:controller API/ProgramController
    ```

2.  **Isi `app/Http/Controllers/API/ProgramController.php`:**
    Salin kode berikut ke dalam file yang baru saja dibuat. Controller ini akan bertanggung jawab atas validasi, transaksi database, dan mengirimkan job untuk pemrosesan file.

    ```php
    <?php

    namespace App\Http\Controllers\API;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\StoreProgramRequest;
    use App\Jobs\ProcessProgramFiles;
    use App\Models\Program;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Symfony\Component\HttpFoundation\Response;

    class ProgramController extends Controller
    {
        /**
         * Menyimpan data program baru dan menangani unggahan file secara asinkron.
         */
        public function storeApi(StoreProgramRequest $request)
        {
            // Memulai transaksi database untuk memastikan integritas data
            DB::beginTransaction();
            try {
                $data = $request->validated();
                $program = Program::create($data);

                // Sinkronisasi relasi (many-to-many)
                $program->targetReinstra()->sync($request->input('targetreinstra', []));
                $program->kelompokMarjinal()->sync($request->input('kelompokmarjinal', []));
                $program->kaitanSDG()->sync($request->input('kaitansdg', []));
                $program->partner()->sync($request->input('partner', []));
                $program->lokasi()->sync($request->input('lokasi', []));

                // Memanggil metode internal untuk menyimpan relasi yang lebih kompleks
                $this->storeStaffPeran($program, $request);
                $this->storeReportSchedule($request, $program);
                $this->storeDonatur($program, $request);
                $this->storeOutcome($request, $program);
                $this->storeGoal($request, $program);
                $this->storeObjective($request, $program);

                // Mengirimkan tugas unggah file ke antrian (queue)
                if ($request->hasFile('file_pendukung')) {
                    $this->queueMediaUploads($request, $program);
                }

                // Jika semua berhasil, commit transaksi
                DB::commit();

                return response()->json([
                    'success' => true,
                    'data' => $program,
                    'message' => __('cruds.data.data') . ' ' . __('cruds.program.title') . ' ' . $request->nama . ' ' . __('cruds.data.added') . ' File akan diproses di latar belakang.',
                ], Response::HTTP_CREATED);

            } catch (Exception $e) {
                // Jika terjadi error, batalkan semua perubahan database
                DB::rollBack();
                Log::error('Gagal membuat program: ' . $e->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat membuat program.',
                    'error'   => $e->getMessage(),
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        /**
         * Menangani unggahan file dengan menempatkannya di antrian.
         */
        private function queueMediaUploads(Request $request, Program $program)
        {
            $tempPaths = [];
            $captions = $request->input('captions', []);
            $collectionName = 'program_' . $program->id;

            foreach ($request->file('file_pendukung') as $file) {
                // Simpan file ke direktori sementara dan dapatkan path-nya
                $tempPath = $file->store('temp_program_files');
                $tempPaths[] = storage_path('app/' . $tempPath);
            }

            // Kirim job ke queue untuk diproses di background
            ProcessProgramFiles::dispatch($program, $tempPaths, $captions, $collectionName, auth()->user());
        }

        // ... (Salin semua metode helper seperti storeStaffPeran, storeReportSchedule, dll. dari ProgramController lama ke sini) ...
        // Contoh:
        public function storeStaffPeran(Program $program, Request $request)
        {
            $staff = $request->input('staff', []);
            $peran = $request->input('peran', []);

            if (count($staff) !== count($peran)) {
                throw new Exception('Jumlah Staff dan Peran tidak cocok.');
            }

            $staffPeranData = [];
            foreach ($staff as $index => $staffId) {
                $staffPeranData[$staffId] = ['peran_id' => $peran[$index]];
            }
            $program->staff()->sync($staffPeranData);
        }
        
        // ... (tambahkan metode helper lainnya di sini) ...
    }
    ```

---

## Langkah 2: Membuat Job untuk Unggahan Asinkron

Job ini akan berisi logika untuk memproses file yang telah disimpan sementara dan menambahkannya ke *Media Library*.

1.  **Buat Job Baru:**
    ```bash
    php artisan make:job ProcessProgramFiles
    ```

2.  **Isi `app/Jobs/ProcessProgramFiles.php`:**
    Salin kode berikut. Job ini akan mengambil file dari penyimpanan sementara, memprosesnya, lalu menghapusnya.

    ```php
    <?php

    namespace App\Jobs;

    use App\Models\Program;
    use App\Models\User;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\File;

    class ProcessProgramFiles implements ShouldQueue
    {
        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

        protected $program;
        protected $tempFilePaths;
        protected $captions;
        protected $collectionName;
        protected $user;

        /**
         * Create a new job instance.
         * 
         * @return void
         */
        public function __construct(Program $program, array $tempFilePaths, array $captions, string $collectionName, User $user)
        {
            $this->program = $program;
            $this->tempFilePaths = $tempFilePaths;
            $this->captions = $captions;
            $this->collectionName = $collectionName;
            $this->user = $user;
        }

        /**
         * Execute the job.
         * 
         * @return void
         */
        public function handle()
        {
            Log::info("Memulai pemrosesan file untuk program: {$this->program->nama} (ID: {$this->program->id})");
            $timestamp = now()->format('Ymd_His');
            $fileCount = 1;

            foreach ($this->tempFilePaths as $index => $tempPath) {
                if (!File::exists($tempPath)) {
                    Log::warning("File sementara tidak ditemukan: {$tempPath}");
                    continue;
                }

                try {
                    $file = new \Illuminate\Http\File($tempPath);
                    $originalName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                    $extension = $file->getExtension();
                    $programName = str_replace(' ', '_', $this->program->nama);
                    $fileName = "{$programName}_{$timestamp}_{$fileCount}.{$extension}";
                    $keterangan = $this->captions[$index] ?? $fileName;

                    $this->program->addMedia($tempPath)
                        ->withCustomProperties([
                            'keterangan' => $keterangan,
                            'user_id'  =>  $this->user->id,
                            'original_name' => $originalName,
                            'extension' => $extension
                        ])
                        ->usingName("{$programName}_{$originalName}_{$fileCount}")
                        ->usingFileName($fileName)
                        ->toMediaCollection($this->collectionName, 'program_uploads');

                    $fileCount++;
                    Log::info("File {$fileName} berhasil diproses untuk program ID {$this->program->id}.");

                } catch (\Exception $e) {
                    Log::error("Gagal memproses file {$tempPath} untuk program ID {$this->program->id}: " . $e->getMessage());
                } finally {
                    // Hapus file sementara setelah diproses, baik berhasil maupun gagal
                    // File::delete($tempPath);
                }
            }
            Log::info("Selesai memproses file untuk program: {$this->program->nama}");
        }
    }
    ```

---

## Langkah 3: Refactor `ProgramController` yang Sudah Ada

Sekarang, kita akan menyederhanakan `Admin\ProgramController` untuk mendelegasikan tugasnya ke `API\ProgramController`.

Buka file `app/Http/Controllers/Admin/ProgramController.php` dan ubah metode `store`.

**Sebelum Refactor:**
```php
// app/Http/Controllers/Admin/ProgramController.php

public function store(StoreProgramRequest $request, Program $program)
{
    DB::beginTransaction();
    try {
        // ... BANYAK SEKALI LOGIKA DI SINI ...
        // ... Termasuk validasi, sinkronisasi, dan unggah file sinkron ...
        // ...
        DB::commit();
        return response()->json([
            'success' => true,
            // ...
        ], Response::HTTP_CREATED);
    } catch (Exception $e) {
        DB::rollBack();
        // ... Penanganan error ...
    }
}
```

**Setelah Refactor:**
Metode `store` menjadi sangat ramping. Ia hanya memanggil `storeApi` dari controller API kita.

```php
// app/Http/Controllers/Admin/ProgramController.php

// Jangan lupa tambahkan `use` di bagian atas file
use App\Http\Controllers\API\ProgramController as APIProgramController;
use App\Http\Requests\StoreProgramRequest;
use App\Models\Program;
use Symfony\Component\HttpFoundation\Response;


// ...

public function store(StoreProgramRequest $request)
{
    try {
        $apiController = new APIProgramController();
        // Delegasikan semua pekerjaan ke API Controller
        return $apiController->storeApi($request);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => $e->getMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

// Hapus semua metode helper (storeStaffPeran, storeOutcome, dll.) dari controller ini
// karena sudah dipindahkan ke API Controller.
```

---

## Langkah 4: Menjalankan Queue Worker

Agar job yang kita kirim bisa dieksekusi, Anda perlu menjalankan *queue worker*. Worker ini adalah proses yang berjalan di latar belakang dan akan mengambil serta menjalankan job dari antrian.

Buka terminal baru dan jalankan perintah berikut:

```bash
php artisan queue:work
```

Biarkan terminal ini tetap berjalan. Sekarang, setiap kali Anda membuat program baru, Anda akan melihat log dari `ProcessProgramFiles` muncul di terminal ini, yang menandakan file sedang diproses.

---

## Kesimpulan

Selamat! Anda telah berhasil melakukan refactoring pada `ProgramController`. Aplikasi Anda sekarang memiliki arsitektur yang lebih baik, memberikan respons lebih cepat kepada pengguna, dan lebih siap untuk berkembang di masa depan. Anda dapat menerapkan pola yang sama untuk metode `update` jika diperlukan.
