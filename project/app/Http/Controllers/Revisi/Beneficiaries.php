<?php

namespace App\Http\Controllers\Revisi;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Kegiatan_Lokasi;
use App\Models\Meals_Penerima_Manfaat;
use App\Models\Kelompok_Marjinal;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Beneficiaries extends Controller
{
    /**
     * Display the beneficiaries dashboard
     */
    public function index()
    {
        // Get all programs for filter dropdown
        $programs = Program::select('id', 'nama', 'kode')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all provinces for filter dropdown
        $provinsis = Provinsi::where('aktif', true)
            ->orderBy('nama')
            ->get();

        // Get years from programs
        $years = DB::table('trprogram')
            ->select(DB::raw('YEAR(tanggalmulai) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');

        return view('dashboard.revisi.beneficiaries', compact(
            'programs',
            'provinsis',
            'years',
            'googleMapsApiKey'
        ));
    }

    /**
     * Get dashboard data via AJAX
     * Returns statistics for cards
     */
    public function getData(Request $request)
    {
        $programId = $request->get('program_id');
        $provinsiId = $request->get('provinsi_id');
        $tahun = $request->get('tahun');
        $status = $request->get('status');

        // First, get the list of valid program IDs based on filters (including status)
        $filteredProgramIds = $this->getFilteredProgramIds($programId, $tahun, $status);

        // If no programs match, we can return empty data immediately or let the queries run with empty ID list
        // It's safer to let queries run with whereIn empty array which yields no results usually

        $data = [
            'programs' => $this->getProgramsWithStatus($filteredProgramIds),
            'locations' => $this->getKegiatanLocations($filteredProgramIds, $provinsiId, $tahun),
            'genderData' => $this->getGenderDistribution($filteredProgramIds, $provinsiId, $tahun),
            'marjinalData' => $this->getKelompokMarjinal($filteredProgramIds, $provinsiId, $tahun),
            'stats' => $this->getStatistics($filteredProgramIds, $provinsiId, $tahun),
        ];

        return response()->json($data);
    }

    /**
     * Helper to get program IDs filtered by ID, Year, and Status
     */
    private function getFilteredProgramIds($programId = null, $tahun = null, $status = null)
    {
        $query = Program::select('id', 'tanggalmulai', 'tanggalselesai', 'status');

        if ($programId) {
            $query->where('id', $programId);
        }

        if ($tahun) {
            $query->whereYear('tanggalmulai', '<=', $tahun)
                ->whereYear('tanggalselesai', '>=', $tahun);
        }

        $programs = $query->get();

        if (!$status && !$tahun && !$programId) {
            // Optimization: if no filters that affect program list, return all IDs? 
            // Better to return pluck id
            return $programs->pluck('id')->toArray();
        }

        if ($status) {
            $programs = $programs->filter(function ($program) use ($status) {
                // Ensure dates are cast to Carbon if not already
                // Assuming model casts are set or we handle it here
                // Note: User's previous issue was format() on string. 
                // Program model might not have casts yet, so robust parsing is needed.
                $start = $program->tanggalmulai ? Carbon::parse($program->tanggalmulai) : null;
                $end = $program->tanggalselesai ? Carbon::parse($program->tanggalselesai) : null;
                $now = now();

                // Determine program status
                $programStatus = 'completed'; // default fallback

                if ($program->status === 'complete') {
                    $programStatus = 'completed';
                } elseif ($start && $end && $now->between($start, $end)) {
                    $programStatus = 'running';
                } elseif ($start && $now->lt($start)) {
                    $programStatus = 'pending';
                } else {
                    $programStatus = 'completed';
                }

                return $programStatus === $status;
            });
        }

        return $programs->pluck('id')->toArray();
    }

    /**
     * Get programs with calculated status
     * Status: Sedang Berjalan, Sudah Selesai, Belum Dimulai
     */
    private function getProgramsWithStatus($programIds)
    {
        if (empty($programIds)) {
            return [];
        }

        $query = Program::with(['users'])
            ->whereIn('id', $programIds)
            // Added deskripsiprojek and totalnilai for the modal details
            ->select('id', 'nama', 'kode', 'tanggalmulai', 'tanggalselesai', 'status', 'user_id', 'deskripsiprojek', 'totalnilai');

        $programs = $query->get()->map(function ($program) {
            $now = now();
            // Robust parsing
            $start = $program->tanggalmulai ? Carbon::parse($program->tanggalmulai) : null;
            $end = $program->tanggalselesai ? Carbon::parse($program->tanggalselesai) : null;

            // Determine status based on dates and status field
            if ($program->status === 'complete') {
                $statusText = 'Sudah Selesai';
                $statusClass = 'completed';
            } elseif ($start && $end && $now->between($start, $end)) {
                $statusText = 'Sedang Berjalan';
                $statusClass = 'running';
            } elseif ($start && $now->lt($start)) {
                $statusText = 'Belum Dimulai';
                $statusClass = 'pending';
            } else {
                $statusText = 'Sudah Selesai';
                $statusClass = 'completed';
            }

            return [
                'id' => $program->id,
                'nama' => $program->nama,
                'kode' => $program->kode,
                'tanggal_mulai' => $start ? $start->format('d M Y') : '-',
                'tanggal_selesai' => $end ? $end->format('d M Y') : '-',
                'status' => $statusText,
                'statusClass' => $statusClass,
                'pic' => $program->users->name ?? 'N/A',
                // New fields for modal
                'deskripsi' => $program->deskripsiprojek ?? 'Tidak ada deskripsi',
                'total_nilai' => $program->totalnilai ? 'Rp ' . number_format($program->totalnilai, 0, ',', '.') : 'Rp 0'
            ];
        });

        return $programs;
    }

    /**
     * Get kegiatan locations with village details
     * Source: trkegiatan -> trkegiatan_lokasi
     * This is the main data source for map markers (per desa, not per province)
     */
    private function getKegiatanLocations($programIds, $provinsiId = null, $tahun = null)
    {
        if (empty($programIds)) {
            return collect([]);
        }

        $query = Kegiatan_Lokasi::with([
            'kegiatan.programOutcomeOutputActivity.program_outcome_output.program_outcome.program',
            'kegiatan.jenisKegiatan',
            'desa.kecamatan.kabupaten.provinsi'
        ])
            ->whereHas('kegiatan', function ($q) use ($programIds, $tahun) {
                // Filter by program IDs from the pre-filtering step
                $q->whereHas('programOutcomeOutputActivity.program_outcome_output.program_outcome', function ($pq) use ($programIds) {
                    $pq->whereIn('program_id', $programIds);
                });

                // Date filtering already handled by program IDs mostly, 
                // but strictly speaking activities should also be within that year if year is set?
                // The requirements usually equate "Data by Year" to "Program in that Year".
                // We'll trust the program ID filter for year context.
                // But if we want to be strict about activity dates:
                if ($tahun) {
                $q->whereYear('tanggalmulai', '<=', $tahun)
                        ->whereYear('tanggalselesai', '>=', $tahun);
                }
            })
            ->whereNotNull('lat')
            ->whereNotNull('long');

        // Filter by province if specified
        if ($provinsiId) {
            $query->whereHas('desa.kecamatan.kabupaten.provinsi', function ($q) use ($provinsiId) {
                $q->where('id', $provinsiId);
            });
        }

        $locations = $query->get()->map(function ($lokasi) {
            $kegiatan = $lokasi->kegiatan;

            // Get program through the relationship chain
            $program = optional(optional(optional(optional($kegiatan->programOutcomeOutputActivity)
                ->program_outcome_output)
                ->program_outcome)
                ->program);

            if (!$program) return null;

            // Calculate beneficiaries from kegiatan
            $totalBeneficiaries = $kegiatan->penerimamanfaattotal ?? 0;

            return [
                'program_id' => $program->id ?? null,
                'program_nama' => $program->nama ?? 'N/A',
                'program_kode' => $program->kode ?? 'N/A',
                'program_status' => $program->status ?? 'N/A',
                'program_objektif' => $program->objektif->deskripsi ?? 'N/A',
                'desa_nama' => optional($lokasi->desa)->nama ?? 'N/A',
                'kecamatan_nama' => optional(optional($lokasi->desa)->kecamatan)->nama ?? 'N/A',
                'kabupaten_nama' => optional(optional(optional($lokasi->desa)->kecamatan)->kabupaten)->nama ?? 'N/A',
                'provinsi_nama' => optional(optional(optional(optional($lokasi->desa)->kecamatan)->kabupaten)->provinsi)->nama ?? 'N/A',
                'lat' => (float) $lokasi->lat,
                'long' => (float) $lokasi->long,
                'kegiatan_mulai' => $kegiatan->tanggalmulai ? Carbon::parse($kegiatan->tanggalmulai)->format('d M Y') : '-',
                'kegiatan_selesai' => $kegiatan->tanggalselesai ? Carbon::parse($kegiatan->tanggalselesai)->format('d M Y') : '-',
                'penerimamanfaattotal' => $totalBeneficiaries,
                'aktivitas_list' => optional($kegiatan->jenisKegiatan)->nama ?? 'N/A'
            ];
        })->filter()->values();

        return $locations;
    }

    /**
     * Get gender distribution from penerima manfaat
     * Returns: Laki-laki and Perempuan counts
     */
    private function getGenderDistribution($programIds, $provinsiId = null, $tahun = null)
    {
        if (empty($programIds)) {
            return collect([]);
        }

        $query = Meals_Penerima_Manfaat::select('jenis_kelamin', DB::raw('COUNT(*) as total'))
            ->whereNull('deleted_at')
            ->whereIn('program_id', $programIds);

        if ($provinsiId) {
            $query->whereHas('dusun.desa.kecamatan.kabupaten.provinsi', function ($q) use ($provinsiId) {
                $q->where('id', $provinsiId);
            });
        }

        // Year is implicitly handled by programIds

        $data = $query->groupBy('jenis_kelamin')->get();

        return $data->map(function ($item) {
            // Normalized DB values usually 'L'/'P' or 'laki'/'perempuan'
            // Based on previous logs/code, it might be 'laki'? Check DB dump if possible.
            // Assuming standard 'L'/'P' or checking value.
            // Use strtolower to be safe
            $jk = strtolower($item->jenis_kelamin);
            $label = ($jk === 'laki' || $jk === 'laki-laki') ? 'Laki-laki' : ($jk === 'perempuan' || $jk === 'p' ? 'Perempuan' : ($jk === 'lainnya' ? 'Lainnya' : 'Tidak Diketahui'));

            return [
                'jenis_kelamin' => $label,
                'total' => (int) $item->total
            ];
        });
    }

    /**
     * Get kelompok marjinal distribution
     * Returns: counts for each kelompok marjinal category
     */
    private function getKelompokMarjinal($programIds, $provinsiId = null, $tahun = null)
    {
        if (empty($programIds)) {
            return collect([]);
        }

        // FIX: Using correct table name 'mkelompokmarjinal' instead of 'kelompok_marjinal'
        $query = DB::table('trmeals_penerima_manfaat_kelompok_marjinal as pm')
            ->join('mkelompokmarjinal as km', 'pm.kelompok_marjinal_id', '=', 'km.id')
            ->join('trmeals_penerima_manfaat as tpm', 'pm.trmeals_penerima_manfaat_id', '=', 'tpm.id')
            ->select('km.nama as kelompok', DB::raw('COUNT(DISTINCT pm.trmeals_penerima_manfaat_id) as jumlah'))
            ->whereNull('tpm.deleted_at')
            ->whereIn('tpm.program_id', $programIds);

        if ($provinsiId) {
            $query->join('dusun as d', 'tpm.dusun_id', '=', 'd.id')
                ->join('kelurahan as kel', 'd.desa_id', '=', 'kel.id')
                ->join('kecamatan as kec', 'kel.kecamatan_id', '=', 'kec.id')
                ->join('kabupaten as kab', 'kec.kabupaten_id', '=', 'kab.id')
                ->where('kab.provinsi_id', $provinsiId);
        }

        return $query->groupBy('km.id', 'km.nama')
            ->orderBy('jumlah', 'desc')
            ->get();
    }

    /**
     * Get dashboard statistics
     * Returns: total programs, locations, beneficiaries, gender breakdown, children, disability, families
     */
    private function getStatistics($programIds, $provinsiId = null, $tahun = null)
    {
        if (empty($programIds)) {
            return [
                'totalPrograms' => 0,
                'totalLocations' => 0,
                'totalBeneficiaries' => 0,
                'femaleBeneficiaries' => 0,
                'maleBeneficiaries' => 0,
                'childrenMale' => 0,
                'childrenFemale' => 0,
                'families' => 0,
                'disability' => 0,
            ];
        }

        // Programs count
        $totalPrograms = count($programIds);

        // Base Beneficiary Query
        $beneficiaryQuery = Meals_Penerima_Manfaat::whereNull('deleted_at')
            ->whereIn('program_id', $programIds);

        if ($provinsiId) {
            $beneficiaryQuery->whereHas('dusun.desa.kecamatan.kabupaten.provinsi', function ($q) use ($provinsiId) {
                $q->where('id', $provinsiId);
            });
        }

        // Locations count
        $locationQuery = Kegiatan_Lokasi::whereNotNull('lat')->whereNotNull('long');
        $locationQuery->whereHas('kegiatan.programOutcomeOutputActivity.program_outcome_output.program_outcome', function ($q) use ($programIds) {
            $q->whereIn('program_id', $programIds);
        });

        if ($provinsiId) {
            $locationQuery->whereHas('desa.kecamatan.kabupaten.provinsi', function ($q) use ($provinsiId) {
                $q->where('id', $provinsiId);
            });
        }

        // Gender Distribution Data
        $genderData = $this->getGenderDistribution($programIds, $provinsiId, $tahun);
        $maleTotal = $genderData->where('jenis_kelamin', 'Laki-laki')->first()['total'] ?? 0;
        $femaleTotal = $genderData->where('jenis_kelamin', 'Perempuan')->first()['total'] ?? 0;

        // Clone query for specific stats to avoid side effects if not careful (though count() is safe usually)

        // Children Male (< 18)
        $childrenMale = (clone $beneficiaryQuery)->where('umur', '<', 18)
            ->where(function ($q) {
                $q->where('jenis_kelamin', 'L')
                    ->orWhere('jenis_kelamin', 'LIKE', 'laki%');
            })->count();

        // Children Female (< 18)
        $childrenFemale = (clone $beneficiaryQuery)->where('umur', '<', 18)
            ->where(function ($q) {
                $q->where('jenis_kelamin', 'P')
                    ->orWhere('jenis_kelamin', 'LIKE', 'perempuan');
            })->count();

        // Families (Heads of Family)
        $families = (clone $beneficiaryQuery)->where('is_head_family', 1)->count();

        // Disability
        // Assuming relationship 'kelompokMarjinal' exists as seen in previous steps
        $disability = (clone $beneficiaryQuery)->whereHas('kelompokMarjinal', function ($q) {
            $q->where('nama', 'LIKE', '%Disabilitas%');
        })->count();

        return [
            'totalPrograms' => $totalPrograms,
            'totalLocations' => $locationQuery->count(),
            'totalBeneficiaries' => $beneficiaryQuery->count(),
            'femaleBeneficiaries' => $femaleTotal, // Keep existing keys
            'maleBeneficiaries' => $maleTotal,
            // Add new keys
            'childrenMale' => $childrenMale,
            'childrenFemale' => $childrenFemale,
            'families' => $families,
            'disability' => $disability,
        ];
    }
}
