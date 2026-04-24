<?php

namespace App\Models\Export;

use App\Models\Kegiatan;
use App\Models\Export\ProgramBTOR;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BTOR extends Model
{
    /**
     * BTOR doesn't have its own table, it uses Kegiatan
     */
    protected $table = 'trkegiatan';

    /**
     * Jenis Kegiatan ID Mapping
     */
    const JENIS_ASSESSMENT = 1;
    const JENIS_SOSIALISASI = 2;
    const JENIS_PELATIHAN = 3;
    const JENIS_PEMBELANJAAN = 4;
    const JENIS_PENGEMBANGAN = 5;
    const JENIS_KAMPANYE = 6;
    const JENIS_PEMETAAN = 7;
    const JENIS_MONITORING = 8;
    const JENIS_KUNJUNGAN = 9;
    const JENIS_KONSULTASI = 10;
    const JENIS_LAINNYA = 11;

    /**
     * Get export class based on jeniskegiatan_id
     */
    public static function getExportClass(int $jenisKegiatanId): string
    {
        $mapping = [
            self::JENIS_ASSESSMENT => BTORAssessment::class,
            self::JENIS_SOSIALISASI => BTORSosialisasi::class,
            self::JENIS_PELATIHAN => BTORPelatihan::class,
            self::JENIS_PEMBELANJAAN => BTORPembelanjaan::class,
            self::JENIS_PENGEMBANGAN => BTORPengembangan::class,
            self::JENIS_KAMPANYE => BTORKampanye::class,
            self::JENIS_PEMETAAN => BTORPemetaan::class,
            self::JENIS_MONITORING => BTORMonitoring::class,
            self::JENIS_KUNJUNGAN => BTORKunjungan::class,
            self::JENIS_KONSULTASI => BTORKonsultasi::class,
            self::JENIS_LAINNYA => BTORLainnya::class,
        ];

        return $mapping[$jenisKegiatanId] ?? self::class;
    }

    /**
     * Get blade view path based on jeniskegiatan_id
     */
    public static function getViewPath(int $jenisKegiatanId): string
    {
        $mapping = [
            self::JENIS_ASSESSMENT => 'tr.btor.jeniskegiatan.assessment',
            self::JENIS_SOSIALISASI => 'tr.btor.jeniskegiatan.sosialisasi',
            self::JENIS_PELATIHAN => 'tr.btor.jeniskegiatan.pelatihan',
            self::JENIS_PEMBELANJAAN => 'tr.btor.jeniskegiatan.pembelanjaan',
            self::JENIS_PENGEMBANGAN => 'tr.btor.jeniskegiatan.pengembangan',
            self::JENIS_KAMPANYE => 'tr.btor.jeniskegiatan.kampanye',
            self::JENIS_PEMETAAN => 'tr.btor.jeniskegiatan.pemetaan',
            self::JENIS_MONITORING => 'tr.btor.jeniskegiatan.monitoring',
            self::JENIS_KUNJUNGAN => 'tr.btor.jeniskegiatan.kunjungan',
            self::JENIS_KONSULTASI => 'tr.btor.jeniskegiatan.konsultasi',
            self::JENIS_LAINNYA => 'tr.btor.jeniskegiatan.lainnya',
        ];

        return $mapping[$jenisKegiatanId] ?? 'tr.btor.jeniskegiatan.default';
    }

    /**
     * Get BTOR data for specific kegiatan
     */
    public static function getData(int $kegiatanId)
    {
        return Kegiatan::with([
            'programOutcomeOutputActivity.program_outcome_output.program_outcome.program.goal',
            'jenisKegiatan',
            'lokasi.desa.kecamatan.kabupaten.provinsi',
            'datapenulis',
            'lokasi_kegiatan',
            'kegiatan_penulis.peran',
            'kegiatan_penulis.user',
            'assessment',
            'monitoring',
            'sosialisasi',
            'pelatihan',
            'pembelanjaan',
            'pengembangan',
            'kampanye',
            'pemetaan',
            'kunjungan',
            'konsultasi',
            'lainnya',
            'programOutcomeOutputActivity.program_outcome_output.program_outcome.program.kaitanSdg',
            'sektor',
            'mitra',
            'user',
        ])->findOrFail($kegiatanId);
    }

    /**
     * Get filtered BTOR list
     */
    public static function getFilteredList(array $filters = []): Collection
    {
        $query = Kegiatan::with([
            'jenisKegiatan',
            'programOutcomeOutputActivity.program_outcome_output.program_outcome.program',
            'user'
        ]);

        // Filter by specific kegiatan ID
        if (!empty($filters['kegiatan_id'])) {
            $query->where('id', $filters['kegiatan_id']);
        }

        // Filter by jenis kegiatan
        if (!empty($filters['jeniskegiatan_id'])) {
            $query->where('jeniskegiatan_id', $filters['jeniskegiatan_id']);
        }

        // Filter by activity (programoutcomeoutputactivity_id)
        if (!empty($filters['activity_id'])) {
            $query->where('programoutcomeoutputactivity_id', $filters['activity_id']);
        }

        // Filter by date range
        if (!empty($filters['start_date'])) {
            $query->where('tanggalmulai', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->where('tanggalselesai', '<=', $filters['end_date']);
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by program
        if (!empty($filters['program_id'])) {
            $query->whereHas('programOutcomeOutputActivity.program_outcome_output.program_outcome.program', function ($q) use ($filters) {
                $q->where('id', $filters['program_id']);
            });
        }

        return $query->orderBy('tanggalmulai', 'desc')->get();
    }


    /**
     * Prepare data for export (PDF, Excel, etc.)
     */
    public function prepareExportData(Kegiatan $kegiatan): array
    {
        return [
            'basic_info' => $this->getBasicInfo($kegiatan),
            'program_info' => $this->getProgramInfo($kegiatan),
            'location_info' => $this->getLocationInfo($kegiatan),
            'beneficiaries' => $this->getBeneficiariesInfo($kegiatan),
            'specific_data' => $this->getSpecificData($kegiatan),
            'media' => $this->getMediaInfo($kegiatan),
        ];
    }

    /**
     * Get basic information
     */
    protected function getBasicInfo(Kegiatan $kegiatan): array
    {
        return [
            'id' => $kegiatan->id,
            'kode' => $kegiatan->programOutcomeOutputActivity?->kode,
            'nama' => $kegiatan->programOutcomeOutputActivity?->nama,
            'jenis_kegiatan' => $kegiatan->jenisKegiatan?->nama,
            'tanggal_mulai' => $kegiatan->tanggalmulai,
            'tanggal_selesai' => $kegiatan->tanggalselesai,
            'durasi' => $kegiatan->getDurationInDays(),
            'status' => $kegiatan->status,
            'fase_pelaporan' => $kegiatan->fasepelaporan,
        ];
    }

    /**
     * Get program information
     */
    protected function getProgramInfo(Kegiatan $kegiatan): array
    {
        return ProgramBTOR::getProgramInfo($kegiatan);
    }

    /**
     * Get location information
     */
    protected function getLocationInfo(Kegiatan $kegiatan): array
    {
        return $kegiatan->lokasi->map(function ($lokasi) {
            return [
                'lokasi' => $lokasi->lokasi,
                'desa' => $lokasi->desa?->nama,
                'kecamatan' => $lokasi->desa?->kecamatan?->nama,
                'kabupaten' => $lokasi->desa?->kecamatan?->kabupaten?->nama,
                'provinsi' => $lokasi->desa?->kecamatan?->kabupaten?->provinsi?->nama,
                'latitude' => $lokasi->lat,
                'longitude' => $lokasi->long,
            ];
        })->toArray();
    }

    /**
     * Get beneficiaries information
     */
    protected function getBeneficiariesInfo(Kegiatan $kegiatan): array
    {
        return [
            'dewasa' => [
                'perempuan' => $kegiatan->penerimamanfaatdewasaperempuan ?? 0,
                'lakilaki' => $kegiatan->penerimamanfaatdewasalakilaki ?? 0,
                'total' => $kegiatan->penerimamanfaatdewasatotal ?? 0,
            ],
            'lansia' => [
                'perempuan' => $kegiatan->penerimamanfaatlansiaperempuan ?? 0,
                'lakilaki' => $kegiatan->penerimamanfaatlansialakilaki ?? 0,
                'total' => $kegiatan->penerimamanfaatlansiatotal ?? 0,
            ],
            'remaja' => [
                'perempuan' => $kegiatan->penerimamanfaatremajaperempuan ?? 0,
                'lakilaki' => $kegiatan->penerimamanfaatremajalakilaki ?? 0,
                'total' => $kegiatan->penerimamanfaatremajatotal ?? 0,
            ],
            'anak' => [
                'perempuan' => $kegiatan->penerimamanfaatanakperempuan ?? 0,
                'lakilaki' => $kegiatan->penerimamanfaatanaklakilaki ?? 0,
                'total' => $kegiatan->penerimamanfaatanaktotal ?? 0,
            ],
            'disabilitas' => [
                'perempuan' => $kegiatan->penerimamanfaatdisabilitasperempuan ?? 0,
                'lakilaki' => $kegiatan->penerimamanfaatdisabilitaslakilaki ?? 0,
                'total' => $kegiatan->penerimamanfaatdisabilitastotal ?? 0,
            ],
            'marjinal' => [
                'perempuan' => $kegiatan->penerimamanfaatmarjinalperempuan ?? 0,
                'lakilaki' => $kegiatan->penerimamanfaatmarjinallakilaki ?? 0,
                'total' => $kegiatan->penerimamanfaatmarjinaltotal ?? 0,
            ],
            'grand_total' => [
                'perempuan' => $kegiatan->penerimamanfaatperempuantotal ?? 0,
                'lakilaki' => $kegiatan->penerimamanfaatlakilakitotal ?? 0,
                'total' => $kegiatan->penerimamanfaattotal ?? 0,
            ],
        ];
    }

    /**
     * Get specific data based on jeniskegiatan_id
     * Override in child classes
     */
    protected function getSpecificData(Kegiatan $kegiatan): array
    {
        return [];
    }

    /**
     * Get media information
     */
    protected function getMediaInfo(Kegiatan $kegiatan): array
    {
        return [
            'dokumen' => $kegiatan->getDokumenPendukung()?->map(function ($doc) {
                return [
                    'name' => $doc->file_name,
                    'url' => $doc->getUrl(),
                    'size' => $doc->size,
                ];
            })->toArray() ?? [],
            'media' => $kegiatan->getMediaPendukung()?->map(function ($media) {
                return [
                    'name' => $media->file_name,
                    'url' => $media->getUrl(),
                    'thumb' => $media->getUrl('thumb'),
                ];
            })->toArray() ?? [],
        ];
    }
}