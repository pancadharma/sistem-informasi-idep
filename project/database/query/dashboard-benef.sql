SELECT 
    tpm.program_id, 
    p.nama AS nama_program,
    COUNT(tpm.id) AS totalBeneficiaries
FROM 
    trmeals_penerima_manfaat AS tpm
-- Melakukan Join bertingkat sesuai relasi 'whereHas'
INNER JOIN dusun ON tpm.dusun_id = dusun.id
INNER JOIN kelurahan ON dusun.desa_id = kelurahan.id
INNER JOIN kecamatan ON kelurahan.kecamatan_id = kecamatan.id
INNER JOIN kabupaten ON kecamatan.kabupaten_id = kabupaten.id
-- Filter Provinsi dilakukan pada tabel kabupaten atau join ke tabel provinsi
INNER JOIN 
    trprogram AS p ON tpm.program_id = p.id
WHERE 
    tpm.deleted_at IS NULL
    -- AND tpm.program_id IN (1, 2, 3) -- Ganti dengan array $programIds
--     AND kabupaten.provinsi_id = 15  -- Ganti dengan nilai $provinsiId
GROUP BY 
    tpm.program_id;
    
    
/*

trmeals_penerima_manfaat_activity


*/
SELECT 
    tpm.program_id, 
    COUNT(tpma.id) AS totalBeneficiaries
FROM 
    trmeals_penerima_manfaat_activity AS tpma
-- 1. Join ke Parent (Penerima Manfaat)
INNER JOIN 
    trmeals_penerima_manfaat AS tpm ON tpma.trmeals_penerima_manfaat_id = tpm.id
-- 2. Join Lokasi (dimulai dari dusun di tabel Parent)
INNER JOIN dusun ON tpm.dusun_id = dusun.id
INNER JOIN kelurahan ON dusun.desa_id = kelurahan.id
INNER JOIN kecamatan ON kelurahan.kecamatan_id = kecamatan.id
INNER JOIN kabupaten ON kecamatan.kabupaten_id = kabupaten.id
WHERE 
    tpma.deleted_at IS NULL
    AND tpm.deleted_at IS NULL
--     AND tpm.program_id IN (1, 2, 3) -- Array $programIds
--     AND kabupaten.provinsi_id = 15  -- Nilai $provinsiId
GROUP BY 
    tpm.program_id;

/*

update
*/


SELECT 
    tpm.program_id,
    p.nama AS nama_program,
    act.kode AS kode_activity,
    act.nama AS nama_activity,
    COUNT(tpma.id) AS totalBeneficiaries
FROM 
    trmeals_penerima_manfaat_activity AS tpma
-- 1. Join ke Master Activity untuk ambil Kode & Nama Activity
INNER JOIN 
    trprogramoutcomeoutputactivity AS act ON tpma.programoutcomeoutputactivity_id = act.id
-- 2. Join ke Parent (Penerima Manfaat) untuk ambil Program ID
INNER JOIN 
    trmeals_penerima_manfaat AS tpm ON tpma.trmeals_penerima_manfaat_id = tpm.id
-- 3. Join ke Master Program untuk ambil Nama Program
INNER JOIN 
    trprogram AS p ON tpm.program_id = p.id
WHERE 
    tpma.deleted_at IS NULL
--     AND tpm.deleted_at IS NULL
--     AND tpm.program_id IN (1, 2, 3) -- Masukkan $programIds
GROUP BY 
    tpm.program_id, 
    p.nama,
    act.kode, 
    act.nama;



SELECT COUNT(*) FROM trprogramlokasi WHERE provinsi_id = 51


SELECT act.id, act.kode, act.nama, SUM(tka.penerimamanfaattotal) AS "total"
FROM trkegiatan AS tka
INNER JOIN 
    trprogramoutcomeoutputactivity AS act ON tka.programoutcomeoutputactivity_id = act.id
WHERE tka.programoutcomeoutputactivity_id = 2



/*
//
//
*/

SELECT 
    p.id,
    act.id AS id_act, 
    p.nama AS namaProgram,
    act.kode,
    act.nama, 
    SUM(DISTINCT tka.penerimamanfaattotal) AS btor_beneficiary, -- from trkegiatan manual input data
    COUNT(DISTINCT tpm.id) AS meals_beneficiary -- inputed from MEALS > BENEFICIARY (trmeals_penerima_manfaat) actuall data
FROM 
    trkegiatan AS tka
-- 1. Join ke Master Activity
INNER JOIN 
    trprogramoutcomeoutputactivity AS act ON tka.programoutcomeoutputactivity_id = act.id
-- 2. Join ke Output (Parent dari Activity)
INNER JOIN 
    trprogramoutcomeoutput AS po_output ON act.programoutcomeoutput_id = po_output.id
-- 3. Join ke Outcome (Parent dari Output)
INNER JOIN 
    trprogramoutcome AS po_outcome ON po_output.programoutcome_id = po_outcome.id
-- 4. Join ke Program (Parent dari Outcome) - Baru bisa ketemu tabel Program di sini
INNER JOIN 
    trprogram AS p ON po_outcome.program_id = p.id
INNER JOIN
    trmeals_penerima_manfaat AS tpm ON p.id = tpm.program_id
WHERE
p.id = 14

--     tka.programoutcomeoutputactivity_id = 2
GROUP BY 
    act.id, 
    p.id,
    tpm.program_id,
    act.kode, 
    act.nama;

--
--
SELECT 
    tpm.program_id,
    p.nama AS nama_program,
    act.nama AS nama_activity,
    act.kode AS kd,
    -- Data Lokasi Kegiatan
    tl.lokasi AS nama_lokasi_kegiatan,
    tl.lat AS latitude_kegiatan,
    tl.long AS longitude_kegiatan,
    -- Hitung Data (Gunakan Distinct untuk menghindari duplikasi row akibat join)
    COUNT(DISTINCT tpma.id) AS totalBeneficiaries
FROM 
    trmeals_penerima_manfaat_activity AS tpma

-- 1. Join ke Parent (Penerima Manfaat) & Program (Standar)
INNER JOIN 
    trmeals_penerima_manfaat AS tpm ON tpma.trmeals_penerima_manfaat_id = tpm.id
INNER JOIN 
    trprogram AS p ON tpm.program_id = p.id

-- 2. Join ke Master Activity (Jembatan Utama)
INNER JOIN 
    trprogramoutcomeoutputactivity AS act ON tpma.programoutcomeoutputactivity_id = act.id

-- 3. Join ke Tabel Pelaksanaan Kegiatan (trkegiatan)
-- Kita hubungkan berdasarkan jenis aktivitas yang sama
INNER JOIN 
    trkegiatan AS tk ON act.id = tk.programoutcomeoutputactivity_id

-- 4. Join ke Tabel Lokasi Kegiatan (Target Utama Anda)
INNER JOIN 
    trkegiatan_lokasi AS tl ON tk.id = tl.kegiatan_id

WHERE 
    tpma.deleted_at IS NULL
--     AND tpm.deleted_at IS NULL
--     AND tpm.program_id IN (1, 2, 3) -- Array $programIds
    
GROUP BY 
    tpm.program_id, 
    p.nama,
    act.nama,
    act.kode,
    tl.lokasi,
    tl.lat,
    tl.long;
    

-- cara untuk membandingkan Realisasi vs Target, tergantung pada level kedalaman data yang ingin Anda lihat: apakah level Program (Makro) atau level Aktivitas (Mikro).
-- Berikut adalah opsi query SQL yang disusun berdasarkan sumber data Anda.
-- 1. Level Program: Target Beneficiaries vs Realisasi Aktual
-- Query ini membandingkan Ekspektasi Penerima Manfaat (yang diset di tabel Program) dengan Jumlah Aktual individu yang terdaftar di tabel Penerima Manfaat.
-- • Tabel Target: trprogram (kolom ekspektasipenerimamanfaat).
-- • Tabel Realisasi: trmeals_penerima_manfaat (Count rows).



SELECT 
    p.id AS id_program,
    p.kode AS kode_program,
    p.nama AS nama_program,
    -- TARGET (Dari setting program)
    p.ekspektasipenerimamanfaat AS target_total,
    p.ekspektasipenerimamanfaatwoman AS target_perempuan,
    p.ekspektasipenerimamanfaatman AS target_laki_laki,
    
    -- REALISASI (Hitung data aktual yang masuk)
    COUNT(tpm.id) AS realisasi_total,
    SUM(CASE WHEN tpm.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END) AS realisasi_perempuan,
    SUM(CASE WHEN tpm.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END) AS realisasi_laki_laki,
    
    -- PERSENTASE CAPAIAN
    ROUND((COUNT(tpm.id) / NULLIF(p.ekspektasipenerimamanfaat, 0)) * 100, 2) AS persentase_capaian
FROM 
    trprogram AS p
LEFT JOIN 
    trmeals_penerima_manfaat AS tpm ON p.id = tpm.program_id AND tpm.deleted_at IS NULL
-- WHERE 
--     p.id IN (1, 2) -- Filter ID Program tertentu
GROUP BY 
    p.id, p.kode, p.nama, p.ekspektasipenerimamanfaat, 
    p.ekspektasipenerimamanfaatwoman, p.ekspektasipenerimamanfaatman;






-- Level Aktivitas: Target Output vs Realisasi Kegiatan
-- Query ini membandingkan target spesifik per aktivitas (Logframe) dengan jumlah peserta yang hadir dalam pelaksanaan kegiatan.
-- • Tabel Target: trprogramoutcomeoutputactivity (kolom target).
-- • Tabel Realisasi: trkegiatan (kolom penerimamanfaattotal).
-- Catatan: Kolom target pada tabel master activity bertipe longtext. Jika isinya berupa teks (misal: "50 Orang"), Anda mungkin perlu membersihkannya di aplikasi. Query di bawah mengasumsikan isinya angka.
SELECT 
    p.nama AS nama_program,
    act.nama AS nama_aktivitas,
    
    -- TARGET (Dari Logframe)
    act.target AS target_aktivitas, 
    
    -- REALISASI (Akumulasi dari pelaksanaan kegiatan)
    COALESCE(SUM(tk.penerimamanfaattotal), 0) AS total_peserta_hadir,
    
    -- Detail Realisasi Gender
    COALESCE(SUM(tk.penerimamanfaatperempuantotal), 0) AS total_perempuan,
    COALESCE(SUM(tk.penerimamanfaatlakilakitotal), 0) AS total_laki_laki,
    
    -- Frekuensi Kegiatan Dilakukan
    COUNT(tk.id) AS frekuensi_pelaksanaan
FROM 
    trprogramoutcomeoutputactivity AS act
-- Join ke atas untuk nama program
INNER JOIN trprogramoutcomeoutput AS out_put ON act.programoutcomeoutput_id = out_put.id
INNER JOIN trprogramoutcome AS out_come ON out_put.programoutcome_id = out_come.id
INNER JOIN trprogram AS p ON out_come.program_id = p.id
-- Join ke bawah untuk data realisasi (Left Join agar target tetap muncul meski belum ada kegiatan)
LEFT JOIN trkegiatan AS tk ON act.id = tk.programoutcomeoutputactivity_id 
    AND tk.status = 'Approved' -- Opsional: Hanya hitung kegiatan yang sudah disetujui [5]
-- WHERE 
--     p.id = 1 -- Filter Program ID
GROUP BY 
    p.nama, act.id, act.nama, act.target;

/*
Alternatif: Menggunakan Modul MEALS Target Progress
*/
 
 SELECT 
    p.nama AS nama_program,
    progress.tanggal AS tanggal_laporan,
    detail.level AS level_indikator, -- Misal: Output, Outcome
    detail.tipe AS tipe_indikator,
    detail.achievements AS capaian_deskriptif,
    detail.progress AS nilai_progress,
    detail.persentase_complete AS persentase_selesai,
    detail.challenges AS tantangan,
    detail.mitigation AS mitigasi
FROM 
    trmeals_target_progress AS progress
INNER JOIN 
    trprogram AS p ON progress.program_id = p.id
INNER JOIN 
    trmeals_target_progress_detail AS detail ON progress.id = detail.id_meals_target_progress
WHERE 
    p.id = 1
ORDER BY 
    progress.tanggal DESC;
    
    
/*



*/


SELECT 
    p.nama AS nama_program,
    
    -- TARGET (Dari tabel Program)
    p.ekspektasipenerimamanfaatwoman AS target_wanita_dewasa,
    p.ekspektasipenerimamanfaatman AS target_pria_dewasa,
    p.ekspektasipenerimamanfaatgirl AS target_anak_perempuan,
    p.ekspektasipenerimamanfaatboy AS target_anak_laki_laki,

    -- REALISASI (Hitung dari data Individu Penerima Manfaat)
    COUNT(CASE WHEN tpm.jenis_kelamin = 'Perempuan' AND tpm.umur >= 18 THEN 1 END) AS realisasi_wanita_dewasa,
    COUNT(CASE WHEN tpm.jenis_kelamin = 'Laki-laki' AND tpm.umur >= 18 THEN 1 END) AS realisasi_pria_dewasa,
    COUNT(CASE WHEN tpm.jenis_kelamin = 'Perempuan' AND tpm.umur < 18 THEN 1 END) AS realisasi_anak_perempuan,
    COUNT(CASE WHEN tpm.jenis_kelamin = 'Laki-laki' AND tpm.umur < 18 THEN 1 END) AS realisasi_anak_laki_laki

FROM 
    trprogram AS p
LEFT JOIN 
    trmeals_penerima_manfaat AS tpm ON p.id = tpm.program_id AND tpm.deleted_at IS NULL
-- WHERE 
--     p.id = 1 -- Ganti dengan ID Program yang diinginkan
GROUP BY 
    p.id, p.nama, 
    p.ekspektasipenerimamanfaatwoman, p.ekspektasipenerimamanfaatman,
    p.ekspektasipenerimamanfaatgirl, p.ekspektasipenerimamanfaatboy;
    
    
/*
Kemajuan Berdasarkan Kelompok Marjinal
Untuk kelompok marjinal, database menggunakan tabel relasi trmeals_penerima_manfaat_kelompok_marjinal yang menghubungkan individu dengan jenis kelompok marjinal (mkelompokmarjinal).
Query ini akan menampilkan distribusi penerima manfaat berdasarkan kategori kerentanannya:
    
*/
    
    SELECT 
    p.nama AS nama_program,
    mkm.nama AS kategori_marjinal,
    COUNT(tpm_km.trmeals_penerima_manfaat_id) AS total_penerima_manfaat
FROM 
    trmeals_penerima_manfaat AS tpm
-- 1. Join ke Program untuk filter nama
INNER JOIN 
    trprogram AS p ON tpm.program_id = p.id
-- 2. Join ke Tabel Pivot Kelompok Marjinal
INNER JOIN 
    trmeals_penerima_manfaat_kelompok_marjinal AS tpm_km ON tpm.id = tpm_km.trmeals_penerima_manfaat_id
-- 3. Join ke Master Kelompok Marjinal untuk ambil nama kategori
INNER JOIN 
    mkelompokmarjinal AS mkm ON tpm_km.kelompok_marjinal_id = mkm.id
WHERE 
    tpm.deleted_at IS NULL
--     AND p.id = 1 -- Ganti dengan ID Program
GROUP BY 
    p.nama, 
    mkm.nama
ORDER BY 
    total_penerima_manfaat DESC;


/*
Alternatif: Data Agregat dari Pelaksanaan Kegiatan (trkegiatan)
Jika Anda tidak ingin menghitung "orang unik" (by name), tetapi ingin melihat total partisipasi (jumlah kehadiran) dalam kegiatan, Anda bisa menggunakan tabel trkegiatan yang sudah memiliki kolom rekapitulasi spesifik.
Query ini berguna untuk melihat statistik kehadiran event:



*/
SELECT 
    act.nama AS nama_aktivitas,
    -- Data Disabilitas (Tersedia langsung di kolom tabel)
    SUM(tk.penerimamanfaatdisabilitastotal) AS total_disabilitas,
    SUM(tk.penerimamanfaatdisabilitasperempuan) AS disabilitas_perempuan,
    
    -- Data Marjinal (Tersedia langsung di kolom tabel)
    SUM(tk.penerimamanfaatmarjinaltotal) AS total_marjinal,
    SUM(tk.penerimamanfaatmarjinalperempuan) AS marjinal_perempuan,
    
    -- Total Gender Umum
    SUM(tk.penerimamanfaatperempuantotal) AS total_hadir_perempuan,
    SUM(tk.penerimamanfaatlakilakitotal) AS total_hadir_laki_laki
FROM 
    trkegiatan AS tk
INNER JOIN 
    trprogramoutcomeoutputactivity AS act ON tk.programoutcomeoutputactivity_id = act.id
-- WHERE 
--     tk.status = 'Approved' -- Opsional: Hanya yang sudah disetujui
GROUP BY 
    act.nama;
