 # Rencana Enhancement Dashboard — Komodel v3

 Dokumen ini mengusulkan peningkatan UX, data, dan metrik untuk halaman `/dashboard/komodel-v3`. Ringkasan dibuat berdasarkan audit `KomponenModelDashboardController@indexV3`, API `GET /api/dashboard-init`, `GET /api/dashboard-data`, serta view `resources/views/tr/komponenmodel/dashboard-v3.blade.php`.

 ## Tujuan
 - Tingkatkan daya guna analitik: metrik inti, tren waktu, dan segmentasi.
 - Permudah eksplorasi: filter multi-variabel, insight top-N, dan peta yang informatif.
 - Rapikan kontrak API agar efisien, konsisten, dan cache-friendly.
 - Pastikan keamanan (auth, rate limit) dan performa (indeks, cache).

 ## Kondisi Saat Ini (Ringkas)
 - Filter: Program, Tipe Komponen, Provinsi, Tahun.
 - KPI: Total Komponen, Total Lokasi, Total Unit Implementasi, Program Aktif.
 - Visual: Pie/Bar distribusi tipe komponen, tabel ringkas per komponen, peta marker, modal detail.
 - API: `/api/dashboard-init`, `/api/dashboard-data` mengembalikan raw hasil gabungan; agregasi berada di controller (`fetchData()`).
 - Celah: belum ada tren waktu; distribusi TargetReinstra/SDG belum terlihat; kualitas data belum diukur; cluster/choropleth peta belum ada; export hanya Print; endpoint API berada di luar `auth`.

 ## Usulan UX
 - Filter panel:
   - Multi-select: Program, Tipe Komponen, Provinsi.
   - Tambahan filter: TargetReinstra, Status Program, Tahun (rentang), Kata kunci (opsional).
   - Aksi cepat: Reset/Clear Filters, Simpan Preset (opsional).
 - KPI Cards (atas):
   - Tambahan: Coverage Provinsi, Komponen tanpa Lokasi, Rata-rata Unit/Komponen, Top Program (short label).
 - Visual Chart:
   - Time-series bulanan: jumlah komponen dan total unit (line/area).
   - Distribusi TargetReinstra (bar horizontal).
   - Kontribusi Program (bar horizontal, top 10).
   - Status Program (donut).
   - Distribusi per Provinsi (bar top 10) + choropleth (opsional).
 - Peta:
   - Marker clustering; legend/filter on-map (by tipe komponen/target).
   - InfoWindow menampilkan ringkasan target & unit; klik menuju modal detail.
 - Tabel:
   - DataTables dengan server-side (paging/sort/search).
   - Kolom: Program, Tipe Komponen, TargetReinstra (concat), Total Unit, #Lokasi, Status, Tahun.
   - Export dari tabel: CSV/XLS.
 - Export:
   - PDF rekap KPI+chart (menggunakan DomPDF) dan XLS (Maatwebsite Excel).

 ## Sumber Data & Mapping Domain
 - Tabel utama: `trmeals_komponen_model (km)`, `trmeals_komponen_model_lokasi (kml)`, `mkomponenmodel (mkm)`, `trprogram (p)`.
 - Pivot Target: `trmeals_komponen_model_targetreinstra (tkmtr)` → `mtargetreinstra (mtr)`.
 - Wilayah: `provinsi`, `kabupaten`, `kecamatan`, `kelurahan`.
 - Satuan: `msatuan`.
 - Atribut Program yang relevan (opsional): `status`, `totalnilai`, `ekspektasipenerimamanfaat*` (untuk beneficiary).
 - SDG (opsional): mapping dari relasi `kaitansdg` pada Program bila tersedia.

 ## Kontrak API (Diusulkan)
 - `GET /api/dashboard-init`
   - Query: none
   - Response:
     ```json
     {
       "filters": {
         "programs": [{"id":1,"nama":"..." ,"status":"Active"}],
         "komponen_models": [{"id":1,"nama":"..."}],
         "targetreinstra": [{"id":1,"nama":"..."}],
         "provinces": [{"id":11,"nama":"Bali"}],
         "years": [2024, 2023, 2022]
       },
       "defaults": {"years_range": [2023,2024]}
     }
     ```
 - `GET /api/dashboard-data`
   - Query:
     - `program_ids[]`, `komponenmodel_ids[]`, `sektor_ids[]`, `provinsi_ids[]`
     - `tahun_from`, `tahun_to`
     - `status[]` (e.g. Active, Planned, Completed)
     - `q` (optional keyword)
   - Response:
     ```json
     {
       "kpis": {
         "total_components": 120,
         "total_units": 5320,
         "active_programs": 8,
         "coverage_provinces": 17,
         "components_without_location": 5,
         "avg_units_per_component": 44.33
       },
       "timeseries": {
         "by_month": [{"month":"2024-01","components":12,"units":320}]
       },
       "distributions": {
         "by_komponen": [{"label":"Pelatihan","count":40}],
         "by_targetreinstra": [{"label":"Kesehatan","count":28}],
         "by_program": [{"label":"Program A","units":1200}],
         "by_status": [{"label":"Active","count":8}]
       },
       "geographic": {
         "by_province": [{"provinsi_id":11,"provinsi":"Bali","locations":120,"units":860}],
         "markers": [{"lat":-8.65,"long":115.22,"komponen_tipe":"...","program":"..."}]
       },
       "table": {
         "data": [
           {
             "komponen_id": 101,
             "program": "Program A",
             "komponen_tipe": "Pelatihan",
             "targets": ["Kesehatan","Pendidikan"],
             "total_unit": 120,
             "locations": 6,
             "status": "Active",
             "tahun": 2024
           }
         ],
         "meta": {"total": 120, "per_page": 25, "page": 1}
       }
     }
     ```

 ## Definisi Metrik
 - `total_components`: distinct count `km.id`.
 - `total_units`: sum `km.totaljumlah`.
 - `active_programs`: distinct count program dengan `status = 'Active'`.
 - `coverage_provinces`: distinct count `kml.provinsi_id`.
 - `components_without_location`: count `km` yang tidak punya baris di `kml`.
 - `avg_units_per_component`: `total_units / total_components`.
 - `by_month`: group by `DATE_FORMAT(km.created_at,'%Y-%m')` → `components`, `SUM(kml.jumlah)` sebagai `units` (opsional).
 - Distribusi:
   - `by_komponen`: group by `mkm.nama` → count `km.id`.
   - `by_targetreinstra`: group by `mtr.nama` via `tkmtr` → count `km.id`.
   - `by_program`: group by `p.nama` → sum `km.totaljumlah` (atau count komponen).
   - `by_status`: group by `p.status` → count program/komponen.
 - Kualitas data (opsional KPI tambahan):
   - `missing_geo`: count `kml.lat`/`kml.long` null.
   - `missing_unit`: count `kml.jumlah` null atau 0.
   - `orphan_targets`: entry `tkmtr` tanpa `mtr`.

 ## Rencana Agregasi (Query)
 - Induk: join `km` + `p` + `mkm`, left join `kml`, lalu side-query untuk `tkmtr + mtr` (grouped) → mapping per `komponen_id` (pattern saat ini, dipertahankan dan dioptimalkan).
 - Timeseries: group by bulan `km.created_at` (gunakan index).
 - Distribusi Target: group by `mtr.nama` dari pivot (IN pada komponen_id hasil filter).
 - Geografis: group by `provinsi_id` untuk ringkasan + kumpulkan marker (lat/long).
 - Paginated table: gunakan query terfilter + group rekap target (implode).

 ## Performa & Skalabilitas
 - Indeks DB yang disarankan:
   - `trmeals_komponen_model`: `(program_id)`, `(komponenmodel_id)`, `(created_at)`.
   - `trmeals_komponen_model_lokasi`: `(mealskomponenmodel_id)`, `(provinsi_id)`.
   - `trmeals_komponen_model_targetreinstra`: `(mealskomponenmodel_id)`, `(targetreinstra_id)`.
 - Cache:
   - Cache per kombinasi filter (hash signature) untuk `kpis`, `distributions`, `timeseries`, `geographic.by_province` (TTL 5–15 menit).
   - Invalidate on create/update/delete terkait komponen/lokasi/target.

 ## Keamanan
 - Pindahkan `/api/dashboard-init` dan `/api/dashboard-data` di balik `auth` (atau Sanctum untuk SPA).
 - Rate limiting (throttle) untuk endpoint agregasi.
 - Validasi request via FormRequest (array, in:status, integer, exists).

 ## Roadmap Implementasi
 - Fase 1 (Struktur & Paritas):
   - Standarkan kontrak API (init/data) + caching KPI/distribusi.
   - Adaptasi view v3 agar konsumsi struktur baru (tanpa hilangkan fitur lama).
   - Tests: Feature API (shape) + Unit aggregator.
 - Fase 2 (Insight & Peta):
   - Tambah time-series, distribusi target, top-N, choropleth/cluster map.
   - Export PDF/XLS.
 - Fase 3 (Data Quality & UX Lanjutan):
   - KPI kualitas data, preset filter, simpan preferensi user.

 ## Testing
 - Feature: `GET /api/dashboard-init` dan `GET /api/dashboard-data` → 200 + struktur JSON sesuai.
 - Unit: service aggregator per blok (kpis, timeseries, distribusi, geographic).
 - Snapshot chart data (opsional).

 ## Risiko & Mitigasi
 - Query berat: gunakan indeks + cache, batasi range tahun default + pagination.
 - Perubahan kontrak API: sediakan adapter di view v3, implementasi bertahap.
 - Kualitas data buruk (null/inkonsisten): tampilkan metrik kualitas + fallback di UI.

 ## Catatan Implementasi
 - Refactor: ekstrak logic agregasi ke `App\\Services\\Dashboard\\KomodelV3Service` + binding via provider.
 - Validasi: buat `DashboardDataRequest` untuk filter.
 - Keamanan: bungkus route API di `Route::middleware('auth')` (atau `auth:sanctum` untuk SPA).
 - Dokumentasi: update README/Docs untuk kontrak API dan metrik.

