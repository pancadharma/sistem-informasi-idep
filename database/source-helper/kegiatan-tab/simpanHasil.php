<?php

namespace App\Http\Controllers;

use App\Models\Trkegiatan;
use App\Models\TrkegiatanAssessment;
use App\Models\TrkegiatanSosialisasi;
use App\Models\TrkegiatanPelatihan;
use App\Models\TrkegiatanPembelanjaan;
use App\Models\TrkegiatanPengembangan;
use App\Models\TrkegiatanKampanye;
use App\Models\TrkegiatanPemetaan;
use App\Models\TrkegiatanMonitoring;
use App\Models\TrkegiatanKunjungan;
use App\Models\TrkegiatanKonsultasi;
use App\Models\TrkegiatanLainnya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Import Log

class KegiatanController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // 1. Create the main Trkegiatan record
            $kegiatan = Trkegiatan::create($request->except([
                // Fields from trkegiatanassessment
                'assessmentyangterlibat',
                'assessmenttemuan',
                'assessmenttambahan',
                'assessmenttambahan_ket',
                'assessmentkendala',
                'assessmentisu',
                'assessmentpembelajaran',

                // Fields from trkegiatansosialisasi
                'sosialisasiyangterlibat',
                'sosialisasitemuan',
                'sosialisasitambahan',
                'sosialisasitambahan_ket',
                'sosialisasikendala',
                'sosialisasiisu',
                'sosialisasipembelajaran',

                // Fields from trkegiatanpelatihan
                'pelatihanpelatih',
                'pelatihanhasil',
                'pelatihandistribusi',
                'pelatihandistribusi_ket',
                'pelatihanrencana',
                'pelatihanunggahan',
                'pelatihanisu',
                'pelatihanpembelajaran',

                // Fields from trkegiatanpembelanjaan
                'pembelanjaandetailbarang',
                'pembelanjaanmulai',
                'pembelanjaanselesai',
                'pembelanjaandistribusimulai',
                'pembelanjaandistribusiselesai',
                'pembelanjaanterdistribusi',
                'pembelanjaanakandistribusi',
                'pembelanjaanakandistribusi_ket',
                'pembelanjaankendala',
                'pembelanjaanisu',
                'pembelanjaanpembelajaran',

                // Fields from trkegiatanpengembangan
                'pengembanganjeniskomponen',
                'pengembanganberapakomponen',
                'pengembanganlokasikomponen',
                'pengembanganyangterlibat',
                'pengembanganrencana',
                'pengembangankendala',
                'pengembanganisu',
                'pengembanganpembelajaran',

                // Fields from trkegiatankampanye
                'kampanyeyangdikampanyekan',
                'kampanyejenis',
                'kampanyebentukkegiatan',
                'kampanyeyangterlibat',
                'kampanyeyangdisasar',
                'kampanyejangkauan',
                'kampanyerencana',
                'kampanyekendala',
                'kampanyeisu',
                'kampanyepembelajaran',

                // Fields from trkegiatanpemetaan
                'pemetaanyangdihasilkan',
                'pemetaanluasan',
                'pemetaanunit',
                'pemetaanyangterlibat',
                'pemetaanrencana',
                'pemetaanisu',
                'pemetaanpembelajaran',

                // Fields from trkegiatanmonitoring
                'monitoringyangdipantau',
                'monitoringdata',
                'monitoringyangterlibat',
                'monitoringmetode',
                'monitoringhasil',
                'monitoringkegiatanselanjutnya',
                'monitoringkegiatanselanjutnya_ket',
                'monitoringkendala',
                'monitoringisu',
                'monitoringpembelajaran',

                // Fields from trkegiatankunjungan
                'kunjunganlembaga',
                'kunjunganpeserta',
                'kunjunganyangdilakukan',
                'kunjunganhasil',
                'kunjunganpotensipendapatan',
                'kunjunganrencana',
                'kunjungankendala',
                'kunjunganisu',
                'kunjunganpembelajaran',

                // Fields from trkegiatankonsultasi
                'konsultasilembaga',
                'konsultasikomponen',
                'konsultasiyangdilakukan',
                'konsultasihasil',
                'konsultasipotensipendapatan',
                'konsultasirencana',
                'konsultasikendala',
                'konsultasiisu',
                'konsultasipembelajaran',

                // Fields from trkegiatanlainnya
                'lainnyamengapadilakukan',
                'lainnyadampak',
                'lainnyasumberpendanaan',
                'lainnyasumberpendanaan_ket',
                'lainnyayangterlibat',
                'lainnyarencana',
                'lainnyakendala',
                'lainnyaisu',
                'lainnyapembelajaran',

                //If you have file inputs
                'dokumen'
            ]));

            $jenisKegiatan = $request->input('id_jeniskegiatan');
            $idKegiatan = $kegiatan->id;

            // 2. Create the related record based on jenisKegiatan
            switch ($jenisKegiatan) {
                case 1: // Assessment
                    TrkegiatanAssessment::create(array_merge($request->only([
                        'assessmentyangterlibat',
                        'assessmenttemuan',
                        'assessmenttambahan',
                        'assessmenttambahan_ket',
                        'assessmentkendala',
                        'assessmentisu',
                        'assessmentpembelajaran'
                    ]), ['id_kegiatan' => $idKegiatan]));
                    break;
                case 2: // Sosialisasi
                    TrkegiatanSosialisasi::create(array_merge($request->only([
                        'sosialisasiyangterlibat',
                        'sosialisasitemuan',
                        'sosialisasitambahan',
                        'sosialisasitambahan_ket',
                        'sosialisasikendala',
                        'sosialisasiisu',
                        'sosialisasipembelajaran'
                    ]), ['id_kegiatan' => $idKegiatan]));
                    break;
                case 3: // Pelatihan
                    TrkegiatanPelatihan::create(array_merge($request->only([
                        'pelatihanpelatih',
                        'pelatihanhasil',
                        'pelatihandistribusi',
                        'pelatihandistribusi_ket',
                        'pelatihanrencana',
                        'pelatihanunggahan',
                        'pelatihanisu',
                        'pelatihanpembelajaran'
                    ]), ['id_kegiatan' => $idKegiatan]));
                    break;
                case 4: // Pembelanjaan
                    TrkegiatanPembelanjaan::create(array_merge($request->only([
                        'pembelanjaandetailbarang',
                        'pembelanjaanmulai',
                        'pembelanjaanselesai',
                        'pembelanjaandistribusimulai',
                        'pembelanjaandistribusiselesai',
                        'pembelanjaanterdistribusi',
                        'pembelanjaanakandistribusi',
                        'pembelanjaanakandistribusi_ket',
                        'pembelanjaankendala',
                        'pembelanjaanisu',
                        'pembelanjaanpembelajaran'
                    ]), ['id_kegiatan' => $idKegiatan]));
                    break;
                case 5: // Pengembangan
                    TrkegiatanPengembangan::create(array_merge($request->only([
                        'pengembanganjeniskomponen',
                        'pengembanganberapakomponen',
                        'pengembanganlokasikomponen',
                        'pengembanganyangterlibat',
                        'pengembanganrencana',
                        'pengembangankendala',
                        'pengembanganisu',
                        'pengembanganpembelajaran'
                    ]), ['id_kegiatan' => $idKegiatan]));
                    break;
                case 6: // Kampanye
                    TrkegiatanKampanye::create(array_merge($request->only([
                        'kampanyeyangdikampanyekan',
                        'kampanyejenis',
                        'kampanyebentukkegiatan',
                        'kampanyeyangterlibat',
                        'kampanyeyangdisasar',
                        'kampanyejangkauan',
                        'kampanyerencana',
                        'kampanyekendala',
                        'kampanyeisu',
                        'kampanyepembelajaran'
                    ]), ['id_kegiatan' => $idKegiatan]));
                    break;
                case 7: // Pemetaan
                    TrkegiatanPemetaan::create(array_merge($request->only([
                        'pemetaanyangdihasilkan',
                        'pemetaanluasan',
                        'pemetaanunit',
                        'pemetaanyangterlibat',
                        'pemetaanrencana',
                        'pemetaanisu',
                        'pemetaanpembelajaran'
                    ]), ['id_kegiatan' => $idKegiatan]));
                    break;
                case 8: // Monitoring
                    TrkegiatanMonitoring::create(array_merge($request->only([
                        'monitoringyangdipantau',
                        'monitoringdata',
                        'monitoringyangterlibat',
                        'monitoringmetode',
                        'monitoringhasil',
                        'monitoringkegiatanselanjutnya',
                        'monitoringkegiatanselanjutnya_ket',
                        'monitoringkendala',
                        'monitoringisu',
                        'monitoringpembelajaran'
                    ]), ['id_kegiatan' => $idKegiatan]));
                    break;
                case 9: // Kunjungan
                    TrkegiatanKunjungan::create(array_merge($request->only([
                        'kunjunganlembaga',
                        'kunjunganpeserta',
                        'kunjunganyangdilakukan',
                        'kunjunganhasil',
                        'kunjunganpotensipendapatan',
                        'kunjunganrencana',
                        'kunjungankendala',
                        'kunjunganisu',
                        'kunjunganpembelajaran'
                    ]), ['id_kegiatan' => $idKegiatan]));
                    break;
                case 10: // Konsultasi
                    TrkegiatanKonsultasi::create(array_merge($request->only([
                        'konsultasilembaga',
                        'konsultasikomponen',
                        'konsultasiyangdilakukan',
                        'konsultasihasil',
                        'konsultasipotensipendapatan',
                        'konsultasirencana',
                        'konsultasikendala',
                        'konsultasiisu',
                        'konsultasipembelajaran'
                    ]), ['id_kegiatan' => $idKegiatan]));
                    break;
                case 11: // Lainnya
                    TrkegiatanLainnya::create(array_merge($request->only([
                        'lainnyamengapadilakukan',
                        'lainnyadampak',
                        'lainnyasumberpendanaan',
                        'lainnyasumberpendanaan_ket',
                        'lainnyayangterlibat',
                        'lainnyarencana',
                        'lainnyakendala',
                        'lainnyaisu',
                        'lainnyapembelajaran'
                    ]), ['id_kegiatan' => $idKegiatan]));
                    break;
                default:
                    // Handle invalid jenisKegiatan (e.g., throw an exception)
                    throw new \Exception("Invalid jenisKegiatan: " . $jenisKegiatan);
            }

            DB::commit();

            return response()->json(['message' => 'Kegiatan created successfully'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e); // Log the error for debugging
            return response()->json(['message' => 'Failed to create kegiatan: ' . $e->getMessage()], 500);
        }
    }
}
