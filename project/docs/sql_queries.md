# SQL Query Reference for Dashboard Revisions

This document provides complete SQL queries for implementing the three dashboard revisions.

## Dashboard 1: Beneficiaries Dashboard

### Query 1: Get All Kegiatan Locations with Full Details

```sql
-- Get kegiatan locations per desa with program information
SELECT
    kl.id as lokasi_id,
    kl.lat,
    kl.long,
    kl.lokasi as lokasi_detail,

    -- Kegiatan info
    k.id as kegiatan_id,
    k.tanggalmulai as kegiatan_mulai,
    k.tanggalselesai as kegiatan_selesai,
    k.penerimamanfaattotal,
    k.penerimamanfaatperempuantotal,
    k.penerimamanfaatlakilakitotal,

    -- Jenis Kegiatan
    jk.nama as jenis_kegiatan,

    -- Program info (through complex relationship)
    p.id as program_id,
    p.nama as program_nama,
    p.kode as program_kode,
    p.status as program_status,
    p.tanggalmulai as program_mulai,
    p.tanggalselesai as program_selesai,

    -- Location hierarchy
    kel.nama as desa_nama,
    kec.nama as kecamatan_nama,
    kab.nama as kabupaten_nama,
    prov.nama as provinsi_nama

FROM trkegiatan_lokasi kl
INNER JOIN trkegiatan k ON kl.kegiatan_id = k.id
INNER JOIN mjenis_kegiatan jk ON k.jeniskegiatan_id = jk.id
INNER JOIN trprogramoutcomeoutputactivity pooa ON k.programoutcomeoutputactivity_id = pooa.id
INNER JOIN trprogramoutcomeoutput poo ON pooa.programoutcomeoutput_id = poo.id
INNER JOIN trprogramoutcome po ON poo.programoutcome_id = po.id
INNER JOIN trprogram p ON po.program_id = p.id
INNER JOIN mkelurahan kel ON kl.desa_id = kel.id
INNER JOIN mkecamatan kec ON kel.kecamatan_id = kec.id
INNER JOIN mkabupaten kab ON kec.kabupaten_id = kab.id
INNER JOIN mprovinsi prov ON kab.provinsi_id = prov.id

WHERE kl.lat IS NOT NULL
  AND kl.long IS NOT NULL
  AND p.id = ? -- Optional: filter by program_id

ORDER BY p.nama, kel.nama;
```

### Query 2: Get Gender Distribution

```sql
-- Get gender distribution from penerima manfaat
SELECT
    CASE
        WHEN jenis_kelamin = 'L' THEN 'Laki-laki'
        WHEN jenis_kelamin = 'P' THEN 'Perempuan'
        ELSE 'Tidak Diketahui'
    END as jenis_kelamin,
    COUNT(*) as total,
    ROUND(COUNT(*) * 100.0 / SUM(COUNT(*)) OVER(), 2) as persentase
FROM trmeals_penerima_manfaat
WHERE program_id = ? -- Optional: filter by program
  AND deleted_at IS NULL
GROUP BY jenis_kelamin
ORDER BY total DESC;
```

### Query 3: Get Kelompok Marjinal Distribution

```sql
-- Get kelompok marjinal with beneficiary counts
SELECT
    km.id as kelompok_id,
    km.nama as kelompok,
    COUNT(DISTINCT pm.trmeals_penerima_manfaat_id) as jumlah,
    ROUND(COUNT(DISTINCT pm.trmeals_penerima_manfaat_id) * 100.0 /
          (SELECT COUNT(DISTINCT trmeals_penerima_manfaat_id)
           FROM trmeals_penerima_manfaat_kelompok_marjinal), 2) as persentase
FROM kelompok_marjinal km
LEFT JOIN trmeals_penerima_manfaat_kelompok_marjinal pm ON km.id = pm.kelompok_marjinal_id
LEFT JOIN trmeals_penerima_manfaat tpm ON pm.trmeals_penerima_manfaat_id = tpm.id
WHERE (tpm.program_id = ? OR ? IS NULL) -- Optional: filter by program
  AND tpm.deleted_at IS NULL
GROUP BY km.id, km.nama
HAVING COUNT(DISTINCT pm.trmeals_penerima_manfaat_id) > 0
ORDER BY jumlah DESC;
```

### Query 4: Get Program Status Summary

```sql
-- Get programs with calculated status
SELECT
    p.id,
    p.nama,
    p.kode,
    p.tanggalmulai,
    p.tanggalselesai,
    p.status as status_field,
    u.name as pic_name,

    -- Calculate dynamic status
    CASE
        WHEN p.status = 'complete' THEN 'Sudah Selesai'
        WHEN CURRENT_DATE BETWEEN p.tanggalmulai AND p.tanggalselesai THEN 'Sedang Berjalan'
        WHEN CURRENT_DATE < p.tanggalmulai THEN 'Belum Dimulai'
        ELSE 'Sudah Selesai'
    END as status_text,

    CASE
        WHEN p.status = 'complete' THEN 'completed'
        WHEN CURRENT_DATE BETWEEN p.tanggalmulai AND p.tanggalselesai THEN 'running'
        WHEN CURRENT_DATE < p.tanggalmulai THEN 'pending'
        ELSE 'completed'
    END as status_class,

    -- Count related data
    (SELECT COUNT(*) FROM trkegiatan_lokasi kl
     INNER JOIN trkegiatan k ON kl.kegiatan_id = k.id
     INNER JOIN trprogramoutcomeoutputactivity pooa ON k.programoutcomeoutputactivity_id = pooa.id
     INNER JOIN trprogramoutcomeoutput poo ON pooa.programoutcomeoutput_id = poo.id
     INNER JOIN trprogramoutcome po ON poo.programoutcome_id = po.id
     WHERE po.program_id = p.id) as total_locations,

    (SELECT COUNT(*) FROM trmeals_penerima_manfaat
     WHERE program_id = p.id AND deleted_at IS NULL) as total_beneficiaries

FROM trprogram p
LEFT JOIN users u ON p.user_id = u.id
WHERE (p.id = ? OR ? IS NULL) -- Optional: filter by program_id
ORDER BY p.tanggalmulai DESC;
```

---

## Dashboard 2: Model Dashboard

### Query 1: Get Model Locations with Coordinates

```sql
-- Get komponen model locations per province
SELECT
    mkml.id as lokasi_id,
    mkml.lat,
    mkml.long,
    mkml.jumlah,

    -- Satuan
    s.nama as satuan,

    -- Komponen Model info
    mkm.id as komponen_model_id,
    mkm.totaljumlah,

    -- Jenis Model
    km.nama as jenis_model,

    -- Program info
    p.id as program_id,
    p.nama as program_nama,
    p.kode as program_kode,

    -- Location hierarchy
    prov.nama as provinsi_nama,
    kab.nama as kabupaten_nama,
    kec.nama as kecamatan_nama,
    kel.nama as desa_nama

FROM trmeals_komponen_model_lokasi mkml
INNER JOIN trmeals_komponen_model mkm ON mkml.mealskomponenmodel_id = mkm.id
INNER JOIN mkomponenmodel km ON mkm.komponenmodel_id = km.id
INNER JOIN trprogram p ON mkm.program_id = p.id
LEFT JOIN msatuan s ON mkml.satuan_id = s.id
LEFT JOIN mprovinsi prov ON mkml.provinsi_id = prov.id
LEFT JOIN mkabupaten kab ON mkml.kabupaten_id = kab.id
LEFT JOIN mkecamatan kec ON mkml.kecamatan_id = kec.id
LEFT JOIN mkelurahan kel ON mkml.desa_id = kel.id

WHERE mkml.lat IS NOT NULL
  AND mkml.long IS NOT NULL
  AND (mkm.program_id = ? OR ? IS NULL) -- Optional: filter by program

ORDER BY prov.nama, kab.nama;
```

### Query 2: Get Trend Per Year (Line Chart)

```sql
-- Get komponen model trend by year
SELECT
    YEAR(mkm.created_at) as tahun,
    COUNT(*) as jumlah_komponen,
    COUNT(DISTINCT mkm.program_id) as jumlah_program,
    SUM(mkm.totaljumlah) as total_jumlah
FROM trmeals_komponen_model mkm
WHERE (mkm.program_id = ? OR ? IS NULL) -- Optional: filter by program
GROUP BY YEAR(mkm.created_at)
ORDER BY tahun ASC;
```

### Query 3: Get Distribution by Jenis Model (Bar Chart)

```sql
-- Get distribution by jenis model
SELECT
    km.id as model_id,
    km.nama as jenis_model,
    COUNT(mkm.id) as jumlah_komponen,
    SUM(mkm.totaljumlah) as total_jumlah,
    COUNT(DISTINCT mkm.program_id) as jumlah_program
FROM mkomponenmodel km
LEFT JOIN trmeals_komponen_model mkm ON km.id = mkm.komponenmodel_id
WHERE (mkm.program_id = ? OR ? IS NULL) -- Optional: filter by program
GROUP BY km.id, km.nama
HAVING COUNT(mkm.id) > 0
ORDER BY jumlah_komponen DESC;
```

### Query 4: Get Sektor Contribution to Components

```sql
-- Get sektor contribution to komponen model
SELECT
    tr.id as sektor_id,
    tr.deskripsi as sektor,
    COUNT(DISTINCT pivot.mealskomponenmodel_id) as jumlah_komponen,
    COUNT(DISTINCT mkm.program_id) as jumlah_program,
    SUM(mkm.totaljumlah) as total_jumlah
FROM targetreinstra tr
INNER JOIN trmeals_komponen_model_targetreinstra pivot ON tr.id = pivot.targetreinstra_id
INNER JOIN trmeals_komponen_model mkm ON pivot.mealskomponenmodel_id = mkm.id
WHERE (mkm.program_id = ? OR ? IS NULL) -- Optional: filter by program
GROUP BY tr.id, tr.deskripsi
ORDER BY jumlah_komponen DESC;
```

### Query 5: Filter by Sektor

```sql
-- Get komponen models filtered by sektor
SELECT DISTINCT
    mkm.*,
    p.nama as program_nama,
    km.nama as jenis_model
FROM trmeals_komponen_model mkm
INNER JOIN trmeals_komponen_model_targetreinstra pivot ON mkm.id = pivot.mealskomponenmodel_id
INNER JOIN targetreinstra tr ON pivot.targetreinstra_id = tr.id
INNER JOIN trprogram p ON mkm.program_id = p.id
INNER JOIN mkomponenmodel km ON mkm.komponenmodel_id = km.id
WHERE tr.id = ? -- Filter by sektor_id
  AND (mkm.program_id = ? OR ? IS NULL) -- Optional: filter by program
ORDER BY mkm.created_at DESC;
```

---

## Dashboard 3: Pendanaan Dashboard

### Query 1: Get SDG Contribution

```sql
-- Get SDG contribution from donations
SELECT
    sdg.id as sdg_id,
    sdg.nama as sdg_nama,
    sdg.kode as sdg_kode,
    SUM(pp.nilaidonasi) as total_donasi,
    COUNT(DISTINCT pp.program_id) as jumlah_program,
    COUNT(DISTINCT pp.pendonor_id) as jumlah_pendonor,
    ROUND(SUM(pp.nilaidonasi) * 100.0 /
          (SELECT SUM(nilaidonasi) FROM trprogrampendonor), 2) as persentase
FROM kaitansdg sdg
INNER JOIN trprogramkaitansdg psdg ON sdg.id = psdg.kaitansdg_id
INNER JOIN trprogram p ON psdg.program_id = p.id
INNER JOIN trprogrampendonor pp ON p.id = pp.program_id
WHERE (pp.pendonor_id = ? OR ? IS NULL) -- Optional: filter by pendonor
GROUP BY sdg.id, sdg.nama, sdg.kode
HAVING SUM(pp.nilaidonasi) > 0
ORDER BY total_donasi DESC;
```

### Query 2: Get Sektor Contribution from Transaksi (Kegiatan)

```sql
-- Get sektor contribution from kegiatan (transaksi)
SELECT
    tr.id as sektor_id,
    tr.deskripsi as sektor,
    SUM(pp.nilaidonasi) as total_donasi,
    COUNT(DISTINCT k.id) as jumlah_kegiatan,
    COUNT(DISTINCT pp.program_id) as jumlah_program,
    COUNT(DISTINCT pp.pendonor_id) as jumlah_pendonor
FROM targetreinstra tr
INNER JOIN trkegiatan_sektor ks ON tr.id = ks.sektor_id
INNER JOIN trkegiatan k ON ks.kegiatan_id = k.id
INNER JOIN trprogramoutcomeoutputactivity pooa ON k.programoutcomeoutputactivity_id = pooa.id
INNER JOIN trprogramoutcomeoutput poo ON pooa.programoutcomeoutput_id = poo.id
INNER JOIN trprogramoutcome po ON poo.programoutcome_id = po.id
INNER JOIN trprogram p ON po.program_id = p.id
INNER JOIN trprogrampendonor pp ON p.id = pp.program_id
WHERE (pp.pendonor_id = ? OR ? IS NULL) -- Optional: filter by pendonor
GROUP BY tr.id, tr.deskripsi
HAVING SUM(pp.nilaidonasi) > 0
ORDER BY total_donasi DESC;
```

### Query 3: Get Donor List with Total Donations

```sql
-- Get pendonor list with donation summary
SELECT
    mp.id as pendonor_id,
    mp.nama as pendonor_nama,
    mp.pic,
    mp.email,
    mp.phone,
    kp.nama as kategori,

    -- Donation summary
    COUNT(DISTINCT pp.program_id) as jumlah_program,
    SUM(pp.nilaidonasi) as total_donasi,
    AVG(pp.nilaidonasi) as rata_rata_donasi,
    MIN(pp.nilaidonasi) as donasi_terkecil,
    MAX(pp.nilaidonasi) as donasi_terbesar,

    -- Latest donation
    MAX(pp.created_at) as donasi_terakhir

FROM mpendonor mp
LEFT JOIN kategori_pendonor kp ON mp.mpendonorkategori_id = kp.id
LEFT JOIN trprogrampendonor pp ON mp.id = pp.pendonor_id
WHERE mp.aktif = 1
  AND (mp.id = ? OR ? IS NULL) -- Optional: filter by pendonor
GROUP BY mp.id, mp.nama, mp.pic, mp.email, mp.phone, kp.nama
HAVING COUNT(DISTINCT pp.program_id) > 0
ORDER BY total_donasi DESC;
```

### Query 4: Get Funding Statistics

```sql
-- Get overall funding statistics
SELECT
    COUNT(DISTINCT pp.pendonor_id) as total_pendonor,
    COUNT(DISTINCT pp.program_id) as total_program,
    SUM(pp.nilaidonasi) as total_donasi,
    AVG(pp.nilaidonasi) as rata_rata_donasi,
    MIN(pp.nilaidonasi) as donasi_terkecil,
    MAX(pp.nilaidonasi) as donasi_terbesar,

    -- By year
    YEAR(pp.created_at) as tahun,
    COUNT(*) as jumlah_transaksi
FROM trprogrampendonor pp
WHERE (pp.pendonor_id = ? OR ? IS NULL) -- Optional: filter by pendonor
GROUP BY YEAR(pp.created_at)
ORDER BY tahun DESC;
```

---

## Laravel Eloquent Equivalents

### Beneficiaries - Get Locations

```php
$locations = Kegiatan_Lokasi::with([
    'kegiatan.programOutcomeOutputActivity.program_outcome_output.program_outcome.program',
    'kegiatan.jenisKegiatan',
    'desa.kecamatan.kabupaten.provinsi'
])
->whereNotNull('lat')
->whereNotNull('long')
->when($programId, function ($query) use ($programId) {
    $query->whereHas('kegiatan.programOutcomeOutputActivity.program_outcome_output.program_outcome',
        fn($q) => $q->where('program_id', $programId)
    );
})
->get();
```

### Model - Get Locations

```php
$locations = Meals_Komponen_Model_Lokasi::with([
    'mealsKomponenModel.program',
    'mealsKomponenModel.komponenmodel',
    'provinsi',
    'kabupaten',
    'satuan'
])
->whereNotNull('lat')
->whereNotNull('long')
->when($programId, fn($q) => $q->whereHas('mealsKomponenModel',
    fn($mq) => $mq->where('program_id', $programId)
))
->get();
```

### Pendanaan - Get SDG Contribution

```php
$sdgContribution = DB::table('trprogrampendonor as pp')
    ->join('trprogram as p', 'pp.program_id', '=', 'p.id')
    ->join('trprogramkaitansdg as psdg', 'p.id', '=', 'psdg.program_id')
    ->join('kaitansdg as sdg', 'psdg.kaitansdg_id', '=', 'sdg.id')
    ->select(
        'sdg.nama as sdg_nama',
        DB::raw('SUM(pp.nilaidonasi) as total_donasi'),
        DB::raw('COUNT(DISTINCT pp.program_id) as jumlah_program')
    )
    ->when($pendonorId, fn($q) => $q->where('pp.pendonor_id', $pendonorId))
    ->groupBy('sdg.id', 'sdg.nama')
    ->orderBy('total_donasi', 'desc')
    ->get();
```

---

## Helper Functions

### Format Rupiah

```php
function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}
```

### Calculate Status

```php
function calculateProgramStatus($program) {
    $now = now();
    $start = Carbon::parse($program->tanggalmulai);
    $end = Carbon::parse($program->tanggalselesai);

    if ($program->status === 'complete') {
        return ['text' => 'Sudah Selesai', 'class' => 'completed'];
    } elseif ($now->between($start, $end)) {
        return ['text' => 'Sedang Berjalan', 'class' => 'running'];
    } elseif ($now->lt($start)) {
        return ['text' => 'Belum Dimulai', 'class' => 'pending'];
    } else {
        return ['text' => 'Sudah Selesai', 'class' => 'completed'];
    }
}
```

---

## Performance Tips

1. **Use Indexes**: Ensure foreign keys and frequently queried columns have indexes
2. **Limit Columns**: Use `SELECT` to only fetch needed columns
3. **Eager Loading**: Use `with()` to avoid N+1 queries
4. **Caching**: Cache frequently accessed data
5. **Pagination**: For large datasets, implement pagination

---

## Testing Queries

Use these queries to verify data exists:

```sql
-- Check if kegiatan has locations
SELECT COUNT(*) FROM trkegiatan_lokasi WHERE lat IS NOT NULL;

-- Check if penerima manfaat has gender data
SELECT jenis_kelamin, COUNT(*) FROM trmeals_penerima_manfaat GROUP BY jenis_kelamin;

-- Check if donations exist
SELECT COUNT(*), SUM(nilaidonasi) FROM trprogrampendonor;

-- Check if model locations exist
SELECT COUNT(*) FROM trmeals_komponen_model_lokasi WHERE lat IS NOT NULL;
```
