SELECT (SELECT COUNT(id) FROM mkomponenmodel) AS TotalKomponenModel, (SELECT COUNT(DISTINCT program_id) 
FROM trmeals_komponen_model) AS TotalProgramsWithKomponenModel, (SELECT COUNT(DISTINCT tkl.desa_id) 
FROM trmeals_komponen_model tkm JOIN trprogram tp ON tkm.program_id = tp.id 
JOIN trprogramoutcome tpo ON tp.id = tpo.program_id 
JOIN trprogramoutcomeoutput tpoo ON tpo.id = tpoo.programoutcome_id 
JOIN trprogramoutcomeoutputactivity tpooa ON tpoo.id = tpooa.programoutcomeoutput_id 
JOIN trkegiatan tk ON tpooa.id = tk.programoutcomeoutputactivity_id JOIN trkegiatan_lokasi tkl ON tk.id = tkl.kegiatan_id 
WHERE tkl.desa_id IS NOT NULL) AS CoverageAreas, (SELECT SUM(tk.penerimamanfaattotal) 
FROM trmeals_komponen_model tkm 
JOIN trprogram tp ON tkm.program_id = tp.id 
JOIN trprogramoutcome tpo ON tp.id = tpo.program_id 
JOIN trprogramoutcomeoutput tpoo ON tpo.id = tpoo.programoutcome_id 
JOIN trprogramoutcomeoutputactivity tpooa 
ON tpoo.id = tpooa.programoutcomeoutput_id 
JOIN trkegiatan tk ON tpooa.id = tk.programoutcomeoutputactivity_id) AS TotalBeneficiaries;



SELECT tp.id AS ProgramID, tp.nama AS ProgramName, 
GROUP_CONCAT(DISTINCT mkm.nama ORDER BY mkm.nama SEPARATOR '; ') AS ProgramComponents, 
GROUP_CONCAT(DISTINCT CONCAT_WS(', ', k.nama, kc.nama, kb.nama, p.nama) 
ORDER BY p.nama, kb.nama, kc.nama, k.nama SEPARATOR '; ') AS Locations, 
tp.status AS ProgramStatus, MAX(al.created_at) AS LastUpdated 
FROM trprogram tp INNER JOIN trmeals_komponen_model tkm ON tp.id = tkm.program_id 
INNER JOIN mkomponenmodel mkm ON tkm.komponenmodel_id = mkm.id 
LEFT JOIN trprogramoutcome tpo ON tp.id = tpo.program_id 
LEFT JOIN trprogramoutcomeoutput tpoo ON tpo.id = tpoo.programoutcome_id 
LEFT JOIN trprogramoutcomeoutputactivity tpooa ON tpoo.id = tpooa.programoutcomeoutput_id 
LEFT JOIN trkegiatan tk ON tpooa.id = tk.programoutcomeoutputactivity_id 
LEFT JOIN trkegiatan_lokasi tkl ON tk.id = tkl.kegiatan_id 
LEFT JOIN kelurahan k ON tkl.desa_id = k.id 
LEFT JOIN kecamatan kc ON k.kecamatan_id = kc.id 
LEFT JOIN kabupaten kb ON kc.kabupaten_id = kb.id 
LEFT JOIN provinsi p ON kb.provinsi_id = p.id 
LEFT JOIN activity_log al ON ( (al.subject_id = tp.id AND al.subject_type = 'App\\Models\\TrProgram') 
OR (al.subject_id = tkm.id 
AND al.subject_type = 'App\\Models\\TrMealsKomponenModel') 
OR (al.subject_id = tk.id 
AND al.subject_type = 'App\\Models\\TrKegiatan') ) 
GROUP BY tp.id, tp.nama, tp.status 
ORDER BY tp.nama;


/*
Test
*/
SELECT P.nama AS program_name, POOA.nama AS activity_program_output_activity_name, MK.nama AS jenis_kegiatan_name, K.tanggalmulai AS kegiatan_start_date, K.tanggalselesai AS kegiatan_end_date, 
K.status AS kegiatan_status, K.deskripsitujuan AS kegiatan_purpose_description, K.penerimamanfaattotal AS kegiatan_total_beneficiaries, KL.lokasi AS kegiatan_lokasi_description, L.nama AS kelurahan_name, 
C.nama AS kecamatan_name, B.nama AS kabupaten_name, 
V.nama AS provinsi_name FROM trkegiatan AS K 
JOIN mjeniskegiatan AS MK ON K.jeniskegiatan_id = MK.id 
JOIN trprogramoutcomeoutputactivity AS POOA ON K.programoutcomeoutputactivity_id = POOA.id 
JOIN trprogramoutcomeoutput AS POO ON POOA.programoutcomeoutput_id = POO.id 
JOIN trprogramoutcome AS PO ON POO.programoutcome_id = PO.id 
JOIN trprogram AS P ON PO.program_id = P.id 
LEFT JOIN trkegiatan_lokasi AS KL ON K.id = KL.kegiatan_id 
LEFT JOIN kelurahan AS L ON KL.desa_id = L.id 
JOIN kecamatan AS C ON L.kecamatan_id = C.id 
LEFT JOIN kabupaten AS B ON C.kabupaten_id = B.id LEFT JOIN provinsi AS V ON B.provinsi_id = V.id 
WHERE P.id = 1 AND (1 IS NULL OR MK.id = 2) ORDER BY P.nama, K.tanggalmulai


/*Report Query*/

SELECT tp.nama 
AS program_nama, tp.kode AS program_kode, tp.tanggalmulai AS program_tanggal_mulai, tp.tanggalselesai AS program_tanggal_selesai, tp.totalnilai 
AS program_total_nilai, tp.status AS program_status, u_program.nama AS program_creator, tpo.deskripsi AS program_outcome_deskripsi, tpoo.deskripsi AS program_output_deskripsi, tpooa.kode 
AS program_activity_kode, tpooa.nama AS program_activity_nama, tk.tanggalmulai AS kegiatan_tanggal_mulai, tk.tanggalselesai 
AS kegiatan_tanggal_selesai, tk.status AS kegiatan_status, tk.deskripsitujuan 
AS kegiatan_deskripsi_tujuan, tk.penerimamanfaattotal AS kegiatan_penerima_manfaat_total, mjk.nama 
AS jenis_kegiatan_nama, tkl.lokasi AS kegiatan_lokasi_detail, k.nama AS kelurahan_nama, kc.nama 
AS kecamatan_nama, kb.nama AS kabupaten_nama, p.nama 
AS provinsi_nama, c.nama AS negara_nama FROM trprogram AS tp 
LEFT JOIN users AS u_program ON tp.user_id = u_program.id 
LEFT JOIN trprogramoutcome AS tpo ON tp.id = tpo.program_id 
LEFT JOIN trprogramoutcomeoutput AS tpoo ON tpo.id = tpoo.programoutcome_id 
LEFT JOIN trprogramoutcomeoutputactivity AS tpooa ON tpoo.id = tpooa.programoutcomeoutput_id 
LEFT JOIN trkegiatan AS tk ON tpooa.id = tk.programoutcomeoutputactivity_id LEFT JOIN mjeniskegiatan AS mjk ON tk.jeniskegiatan_id = mjk.id 
LEFT JOIN trkegiatan_lokasi AS tkl ON tk.id = tkl.kegiatan_id LEFT JOIN kelurahan AS k ON tkl.desa_id = k.id LEFT JOIN kecamatan AS kc ON k.kecamatan_id = kc.id 
LEFT JOIN kabupaten AS kb ON kc.kabupaten_id = kb.id LEFT JOIN provinsi AS p ON kb.provinsi_id = p.id LEFT JOIN country AS c ON p.negara_id = c.id 
WHERE 1 = 1 /* AND tp.id = [selected_program_id] */ /* AND mjk.id = [selected_jenis_kegiatan_id] */ /* AND tk.id = [selected_kegiatan_id] */ ORDER BY tp.tanggalmulai, tk.tanggalmulai










-- Program Analysis
SELECT 
    COUNT(*) AS total_program,
    SUM(totalnilai) AS total_anggaran,
    SUM(ekspektasipenerimamanfaat) AS target_beneficiaries
FROM trprogram 
WHERE STATUS = 'aktif';

-- Kegiatan Analysis  
SELECT 
    mjk.nama AS jenis_kegiatan,
    COUNT(*) AS total_kegiatan,
    SUM(tk.penerimamanfaattotal) AS total_penerima
FROM trkegiatan tk
JOIN mjeniskegiatan mjk ON tk.jeniskegiatan_id = mjk.id
GROUP BY mjk.nama;

-- Komponen Model Analysis
SELECT 
    mkm.nama AS komponen,
    COUNT(*) AS total_implementasi,
    SUM(tkcm.totaljumlah) AS total_jumlah
FROM trmeals_komponen_model tkcm
JOIN mkomponenmodel mkm ON tkcm.komponenmodel_id = mkm.id
JOIN trprogram tp ON tkcm.program_id = tp.id
GROUP BY mkm.nama;

-- Geographic Distribution
SELECT 
    p.nama AS provinsi,
    COUNT(DISTINCT tp.id) AS total_program,
    SUM(tk.penerimamanfaattotal) AS total_beneficiaries
FROM provinsi p
JOIN trprogramlokasi tpl ON p.id = tpl.provinsi_id
JOIN trprogram tp ON tpl.program_id = tp.id
LEFT JOIN trprogramoutcome tpo ON tp.id = tpo.program_id
LEFT JOIN trprogramoutcomeoutput tpoo ON tpo.id = tpoo.programoutcome_id  
LEFT JOIN trprogramoutcomeoutputactivity tpooa ON tpoo.id = tpooa.programoutcomeoutput_id
LEFT JOIN trkegiatan tk ON tpooa.id = tk.programoutcomeoutputactivity_id
GROUP BY p.nama;