Saya telah melengkapi sistem laporan kegiatan yang komprehensif. Mari saya jawab pertanyaan-pertanyaan Anda dan berikan rekomendasi:

## **Rekomendasi untuk Laporan `trkegiatan`:**

### **1. Strategi Pemilihan Data untuk Dilaporkan:**

Berdasarkan parameter yang diberikan (program_id, jeniskegiatan_id, trkegiatan_id), sistem akan memilih data dengan prioritas:

1. **Jika `trkegiatan_id` spesifik dipilih** → Tampilkan kegiatan tunggal dengan detail lengkap
2. **Jika hanya `jeniskegiatan_id`** → Tampilkan semua kegiatan dari jenis tersebut  
3. **Jika hanya `program_id`** → Tampilkan semua kegiatan dalam program
4. **Jika "all"** → Tampilkan semua kegiatan dengan agregasi per kategori

### **2. Jawaban untuk Spesifikasi:**

**Output Type:** 
- ✅ **HTML Preview** (default untuk review)
- ✅ **Excel (.xlsx)** untuk analisis lanjut
- ✅ **CSV** untuk integrasi sistem lain
- ✅ **PDF** untuk distribusi formal

**Scope per Type:**

**Kegiatan:**
```sql
SELECT 
    tk.id, tp.nama as program_name, mjk.nama as jenis_kegiatan,
    tk.tanggalmulai, tk.tanggalselesai, tk.status,
    STRING_AGG(DISTINCT kl.nama, ', ') as lokasi,
    tk.penerimamanfaattotal, tk.penerimamanfaatperempuantotal,
    tk.penerimamanfaatlakilakitotal, tk.penerimamanfaatdisabilitastotal
FROM trkegiatan tk
JOIN trprogram tp ON tk.programoutcomeoutputactivity_id = tp.id  
JOIN mjeniskegiatan mjk ON tk.jeniskegiatan_id = mjk.id
LEFT JOIN trkegiatan_lokasi tkl ON tk.id = tkl.kegiatan_id
LEFT JOIN kelurahan kl ON tkl.desa_id = kl.id
WHERE [filters]
GROUP BY tk.id
```

**Program:**
```sql
SELECT 
    tp.nama, tp.kode, tp.tanggalmulai, tp.tanggalselesai,
    COUNT(tk.id) as total_kegiatan,
    SUM(tk.penerimamanfaattotal) as total_beneficiaries,
    tp.totalnilai as budget
FROM trprogram tp
LEFT JOIN trprogramoutcomeoutputactivity tpooa ON tp.id = tpooa.program_id
LEFT JOIN trkegiatan tk ON tpooa.id = tk.programoutcomeoutputactivity_id
GROUP BY tp.id
```

**MEALS:**
```sql  
SELECT 
    tmpm.nama, tmpm.jenis_kelamin, tmpm.umur,
    d.nama as dusun, kl.nama as desa,
    COUNT(tmpma.programoutcomeoutputactivity_id) as activity_count
FROM trmeals_penerima_manfaat tmpm
JOIN dusun d ON tmpm.dusun_id = d.id
JOIN kelurahan kl ON d.desa_id = kl.id
LEFT JOIN trmeals_penerima_manfaat_activity tmpma ON tmpm.id = tmpma.trmeals_penerima_manfaat_id
GROUP BY tmpm.id
```

**Filters yang Direkomendasikan:**
- ✅ Date range (tanggal_mulai, tanggal_selesai)
- ✅ Program, jenis kegiatan, kegiatan spesifik
- ✅ Status (draft, aktif, selesai, dibatalkan)
- ✅ Provinsi/Kabupaten/Desa (geographic)
- ✅ User/Penanggung jawab

**Agregasi:**
- ✅ **Per Program:** Total kegiatan, beneficiaries, budget utilization
- ✅ **Per Jenis:** Distribution analysis, success rate
- ✅ **Per Region:** Geographic coverage, regional impact
- ✅ **Per Period:** Trend analysis, seasonal patterns

**Layout:** 
- ✅ **Consolidated Report** (recommended) - satu laporan dengan multiple sections
- ✅ **Separate Reports** - option untuk detail spesifik per type

### **3. Fitur Unggulan Sistem:**

1. **Dynamic Column Selection** - User dapat memilih kolom yang ditampilkan
2. **Real-time Filtering** - Filter interaktif dengan preview langsung  
3. **Multiple Export Formats** - HTML, Excel, CSV, PDF
4. **Automated Scheduling** - Laporan terjadwal otomatis
5. **Summary Statistics** - Cards dengan key metrics
6. **Visualization Charts** - Grafik untuk trend analysis
7. **Subtotal Grouping** - Agregasi berdasarkan kategori
8. **Print-friendly Layout** - Optimized untuk printing

### **4. Query SQL untuk Implementasi:**

```sql
-- Master query untuk laporan kegiatan lengkap
WITH kegiatan_detail AS (
    SELECT 
        tk.id,
        tp.nama as program_nama,
        mjk.nama as jenis_kegiatan,
        tk.tanggalmulai,
        tk.tanggalselesai, 
        tk.status,
        STRING_AGG(DISTINCT CONCAT(kl.nama, ', ', kc.nama, ', ', kb.nama), '; ') as lokasi_detail,
        tk.penerimamanfaattotal,
        tk.penerimamanfaatperempuantotal,
        tk.penerimamanfaatlakilakitotal,
        tk.penerimamanfaatdisabilitastotal,
        tk.penerimamanfaatmarjinaltotal,
        u.nama as penanggung_jawab
    FROM trkegiatan tk
    JOIN trprogramoutcomeoutputactivity tpooa ON tk.programoutcomeoutputactivity_id = tpooa.id
    JOIN trprogramoutcomeoutput tpoo ON tpooa.programoutcomeoutput_id = tpoo.id  
    JOIN trprogramoutcome tpo ON tpoo.programoutcome_id = tpo.id
    JOIN trprogram tp ON tpo.program_id = tp.id
    JOIN mjeniskegiatan mjk ON tk.jeniskegiatan_id = mjk.id
    JOIN users u ON tk.user_id = u.id
    LEFT JOIN trkegiatan_lokasi tkl ON tk.id = tkl.kegiatan_id
    LEFT JOIN kelurahan kl ON tkl.desa_id = kl.id
    LEFT JOIN kecamatan kc ON kl.kecamatan_id = kc.id
    LEFT JOIN kabupaten kb ON kc.kabupaten_id = kb.id
    WHERE 1=1
        AND (? IS NULL OR tp.id = ?)  -- program_id filter
        AND (? IS NULL OR mjk.id = ?) -- jeniskegiatan_id filter  
        AND (? IS NULL OR tk.id = ?)  -- kegiatan_id filter
        AND (? IS NULL OR tk.tanggalmulai >= ?)  -- date filters
        AND (? IS NULL OR tk.tanggalselesai <= ?)
        AND (? IS NULL OR tk.status = ?)  -- status filter
    GROUP BY tk.id
)
SELECT * FROM kegiatan_detail
ORDER BY tanggalmulai DESC, program_nama, jenis_kegiatan;
```

Sistem ini memberikan fleksibilitas maksimal untuk berbagai kebutuhan pelaporan dengan interface yang user-friendly dan output yang profesional.
