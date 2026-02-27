<?php

namespace App\Http\Controllers\Revisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\MPendonor;
use App\Models\KaitanSdg;
use App\Models\TargetReinstra;
use DB;

class Pendanaan extends Controller
{
    /**
     * Display the Funding Dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch Filter Data
        $programs = Program::select('id', 'nama', 'kode')->orderBy('created_at', 'desc')->get();

        // Get unique years from programs
        $years = Program::selectRaw('YEAR(tanggalmulai) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->filter();

        // Fetch Donors
        $donors = MPendonor::select('id', 'nama')->where('aktif', 1)->orderBy('nama')->get();

        return view('dashboard.revisi.pendanaan', compact('programs', 'years', 'donors'));
    }

    /**
     * Get Dashboard Data via AJAX
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getData(Request $request)
    {
        $programId = $request->input('program_id');
        $tahun = $request->input('tahun');
        $donorId = $request->input('donor_id');

        // --- Statistics ---
        $donationQuery = DB::table('trprogrampendonor as pp')
            ->when($programId, fn($q) => $q->where('pp.program_id', $programId))
            ->when($donorId, fn($q) => $q->where('pp.pendonor_id', $donorId))
            ->when($tahun, function ($q) use ($tahun) {
                $q->join('trprogram as p', 'pp.program_id', '=', 'p.id')
                    ->whereYear('p.tanggalmulai', $tahun);
            });

        $totalFunding = (clone $donationQuery)->sum('pp.nilaidonasi') ?? 0;
        $totalDonors = (clone $donationQuery)->distinct('pp.pendonor_id')->count('pp.pendonor_id');
        $totalPrograms = (clone $donationQuery)->distinct('pp.program_id')->count('pp.program_id');
        $avgDonation = $totalDonors > 0 ? $totalFunding / $totalDonors : 0;

        $grandTotalFunding = DB::table('trprogrampendonor')->sum('nilaidonasi') ?? 0;
        
        $stats = [
            'totalFunding' => $totalFunding,
            'grandTotalFunding' => $grandTotalFunding,
            'totalDonors' => $totalDonors,
            'totalPrograms' => $totalPrograms,
            'avgDonation' => $avgDonation
        ];

        // --- 1. SDG Contribution ---
        $programSdgCounts = DB::table('trprogramkaitansdg')
            ->select('program_id', DB::raw('count(*) as count'))
            ->groupBy('program_id');

        $sdgContribution = DB::table('trprogramkaitansdg as psdg')
            ->join('mkaitansdg as sdg', 'psdg.kaitansdg_id', '=', 'sdg.id')
            ->join('trprogrampendonor as pp', 'psdg.program_id', '=', 'pp.program_id')
            ->joinSub($programSdgCounts, 'counts', function ($join) {
                $join->on('psdg.program_id', '=', 'counts.program_id');
            })
            ->select('sdg.nama as sdg_name', DB::raw('SUM(pp.nilaidonasi / counts.count) as total'))
            ->when($programId, fn($q) => $q->where('psdg.program_id', $programId))
            ->when($donorId, fn($q) => $q->where('pp.pendonor_id', $donorId))
            ->when($tahun, function ($q) use ($tahun) {
                $q->join('trprogram as p', 'psdg.program_id', '=', 'p.id')
                    ->whereYear('p.tanggalmulai', $tahun);
            })
            ->groupBy('sdg.id', 'sdg.nama')
            ->orderBy('total', 'desc')
            ->get();

        // --- 2. Sector Contribution ---
        $programSectorCounts = DB::table('trkegiatan as k')
            ->join('trkegiatan_sektor as ks', 'k.id', '=', 'ks.kegiatan_id')
            ->join('trprogramoutcomeoutputactivity as pooa', 'k.programoutcomeoutputactivity_id', '=', 'pooa.id')
            ->join('trprogramoutcomeoutput as poo', 'pooa.programoutcomeoutput_id', '=', 'poo.id')
            ->join('trprogramoutcome as po', 'poo.programoutcome_id', '=', 'po.id')
            ->select('po.program_id', DB::raw('count(distinct ks.sektor_id) as count'))
            ->groupBy('po.program_id');

        $sektorContribution = DB::table('trkegiatan as k')
            ->join('trkegiatan_sektor as ks', 'k.id', '=', 'ks.kegiatan_id')
            ->join('mtargetreinstra as s', 'ks.sektor_id', '=', 's.id')
            ->join('trprogramoutcomeoutputactivity as pooa', 'k.programoutcomeoutputactivity_id', '=', 'pooa.id')
            ->join('trprogramoutcomeoutput as poo', 'pooa.programoutcomeoutput_id', '=', 'poo.id')
            ->join('trprogramoutcome as po', 'poo.programoutcome_id', '=', 'po.id')
            ->join('trprogrampendonor as pp', 'po.program_id', '=', 'pp.program_id')
            ->joinSub($programSectorCounts, 's_counts', function ($join) {
                $join->on('po.program_id', '=', 's_counts.program_id');
            })
            ->select('s.nama as sektor_name', DB::raw('SUM(pp.nilaidonasi / s_counts.count / (SELECT count(*) FROM trkegiatan_sektor WHERE kegiatan_id = k.id AND sektor_id = s.id)) as total'))
            // Note: The sector logic is tricky because of multiple levels.
            // Simpler approach for Sectors: count distinct sectors per program and split.
            ->select('s.nama as sektor_name', DB::raw('SUM(pp.nilaidonasi / s_counts.count) as total'))
            // Re-evaluating Sector logic to match SDG's simple distinct category split
            ->groupBy('s.id', 's.nama')
            ->orderBy('total', 'desc')
            ->when($programId, fn($q) => $q->where('po.program_id', $programId))
            ->when($donorId, fn($q) => $q->where('pp.pendonor_id', $donorId))
            ->when($tahun, function ($q) use ($tahun) {
                $q->join('trprogram as p', 'po.program_id', '=', 'p.id')
                    ->whereYear('p.tanggalmulai', $tahun);
            })
            ->get()
            ->groupBy('sektor_name')
            ->map(function ($rows) {
                return [
                    'sektor_name' => $rows[0]->sektor_name,
                    'total' => $rows->avg('total') // Handle duplicate rows from joins by taking unique contribution
                ];
            })
            ->values();

        // Correction: Sektor logic needs to be cleaner to avoid Cartesian product issues
        $sektorContribution = DB::table('trprogram as p')
            ->join('trprogramoutcome as po', 'p.id', '=', 'po.program_id')
            ->join('trprogramoutcomeoutput as poo', 'po.id', '=', 'poo.programoutcome_id')
            ->join('trprogramoutcomeoutputactivity as pooa', 'poo.id', '=', 'pooa.programoutcomeoutput_id')
            ->join('trkegiatan as k', 'pooa.id', '=', 'k.programoutcomeoutputactivity_id')
            ->join('trkegiatan_sektor as ks', 'k.id', '=', 'ks.kegiatan_id')
            ->join('mtargetreinstra as s', 'ks.sektor_id', '=', 's.id')
            ->join('trprogrampendonor as pp', 'p.id', '=', 'pp.program_id')
            ->joinSub($programSectorCounts, 's_counts', 'p.id', '=', 's_counts.program_id')
            ->select('s.nama as sektor_name', DB::raw('SUM(pp.nilaidonasi / s_counts.count) as total'))
            ->when($programId, fn($q) => $q->where('p.id', $programId))
            ->when($donorId, fn($q) => $q->where('pp.pendonor_id', $donorId))
            ->when($tahun, fn($q) => $q->whereYear('p.tanggalmulai', $tahun))
            ->groupBy('s.id', 's.nama')
            ->orderBy('total', 'desc')
            ->get()
            ->map(function($item) use ($programId) {
                // If there's a join duplication (multiple activities per sector), SUM will over-calculate.
                // But since we joined sectors directly, we need to ensure each program donation is only counted once per sector.
                // A better SQL approach: Sum of (donation / sector_count) for distinct program-sector pairs.
                return $item;
            });

        // Let's use a cleaner approach for both to avoid join-multiplication issues
        $sdgContribution = DB::table('trprogramkaitansdg as psdg')
            ->join('trprogrampendonor as pp', 'psdg.program_id', '=', 'pp.program_id')
            ->join('mkaitansdg as sdg', 'psdg.kaitansdg_id', '=', 'sdg.id')
            ->leftJoinSub($programSdgCounts, 'c', 'psdg.program_id', '=', 'c.program_id')
            ->select('sdg.nama as sdg_name', DB::raw('SUM(pp.nilaidonasi / c.count) as total'))
            ->when($programId, fn($q) => $q->where('psdg.program_id', $programId))
            ->when($donorId, fn($q) => $q->where('pp.pendonor_id', $donorId))
            ->when($tahun, function ($q) use ($tahun) {
                $q->join('trprogram as p', 'psdg.program_id', '=', 'p.id')
                    ->whereYear('p.tanggalmulai', $tahun);
            })
            ->groupBy('sdg.id', 'sdg.nama')
            ->get();

        $sektorContribution = DB::table('trkegiatan_sektor as ks')
            ->join('trkegiatan as k', 'ks.kegiatan_id', '=', 'k.id')
            ->join('trprogramoutcomeoutputactivity as pooa', 'k.programoutcomeoutputactivity_id', '=', 'pooa.id')
            ->join('trprogramoutcomeoutput as poo', 'pooa.programoutcomeoutput_id', '=', 'poo.id')
            ->join('trprogramoutcome as po', 'poo.programoutcome_id', '=', 'po.id')
            ->join('trprogrampendonor as pp', 'po.program_id', '=', 'pp.program_id')
            ->join('mtargetreinstra as s', 'ks.sektor_id', '=', 's.id')
            ->leftJoinSub($programSectorCounts, 'c', 'po.program_id', '=', 'c.program_id')
            // Using DISTINCT key to prevent activity-based multiplication in SUM
            ->select('s.nama as sektor_name', DB::raw('SUM(pp.nilaidonasi / c.count / (SELECT count(*) FROM trkegiatan k2 JOIN trkegiatan_sektor ks2 ON k2.id = ks2.kegiatan_id WHERE k2.programoutcomeoutputactivity_id = pooa.id AND ks2.sektor_id = s.id)) as total'))
            // Actually, simplest fix for join multiplication:
            // Group by Program & Sector first, then join with donor and split.
            ->groupBy('s.id', 's.nama')
            ->orderBy('total', 'desc')
            ->when($programId, fn($q) => $q->where('po.program_id', $programId))
            ->when($donorId, fn($q) => $q->where('pp.pendonor_id', $donorId))
            ->when($tahun, function ($q) use ($tahun) {
                $q->join('trprogram as p', 'po.program_id', '=', 'p.id')
                    ->whereYear('p.tanggalmulai', $tahun);
            })
            ->get();
            
        // Final attempt at clean logic for Sektor to avoid Cartesian product issues
        $sektorContribution = DB::table('trkegiatan_sektor as ks')
            ->join('trkegiatan as k', 'ks.kegiatan_id', '=', 'k.id')
            ->join('trprogramoutcomeoutputactivity as pooa', 'k.programoutcomeoutputactivity_id', '=', 'pooa.id')
            ->join('trprogramoutcomeoutput as poo', 'pooa.programoutcomeoutput_id', '=', 'poo.id')
            ->join('trprogramoutcome as po', 'poo.programoutcome_id', '=', 'po.id')
            ->join('trprogram as p', 'po.program_id', '=', 'p.id')
            ->join('trprogrampendonor as pp', 'po.program_id', '=', 'pp.program_id')
            ->join('mtargetreinstra as s', 'ks.sektor_id', '=', 's.id')
            ->leftJoinSub($programSectorCounts, 'c', 'po.program_id', '=', 'c.program_id')
            ->select(
                's.nama as sektor_name', 
                DB::raw('SUM(pp.nilaidonasi / c.count) as raw_total'), 
                DB::raw('COUNT(ks.id) as overlap_count'),
                DB::raw('GROUP_CONCAT(DISTINCT p.nama SEPARATOR ", ") as program_names')
            )
            ->when($programId, fn($q) => $q->where('po.program_id', $programId))
            ->when($donorId, fn($q) => $q->where('pp.pendonor_id', $donorId))
            ->when($tahun, function ($q) use ($tahun) {
                $q->join('trprogram as p_year', 'po.program_id', '=', 'p_year.id')
                    ->whereYear('p_year.tanggalmulai', $tahun);
            })
            ->groupBy('s.id', 's.nama')
            ->get()
            ->map(function($item) {
                return [
                    'sektor_name' => $item->sektor_name,
                    'total' => $item->raw_total / ($item->overlap_count ?: 1),
                    'program_names' => $item->program_names
                ];
            });

        // --- 3. Donor List ---
        $donorList = MPendonor::select('id', 'nama', 'email')
            ->with(['programs' => function ($q) use ($programId, $tahun) {
                if ($programId) {
                    $q->where('trprogram.id', $programId);
                }
                if ($tahun) {
                    $q->whereYear('trprogram.tanggalmulai', $tahun);
                }
            }])
            ->when($donorId, fn($q) => $q->where('id', $donorId))
            ->get()
            ->map(function ($donor) use ($programId, $tahun) {
                // Calculate total based on filtered programs
                $totalDonated = DB::table('trprogrampendonor')
                    ->where('pendonor_id', $donor->id)
                    ->when($programId, fn($q) => $q->where('program_id', $programId))
                    ->when($tahun, function ($q) use ($tahun) {
                        $q->join('trprogram as p', 'trprogrampendonor.program_id', '=', 'p.id')
                            ->whereYear('p.tanggalmulai', $tahun);
                    })
                    ->sum('nilaidonasi') ?? 0;

                $programCount = DB::table('trprogrampendonor')
                    ->where('pendonor_id', $donor->id)
                    ->when($programId, fn($q) => $q->where('program_id', $programId))
                    ->when($tahun, function ($q) use ($tahun) {
                        $q->join('trprogram as p', 'trprogrampendonor.program_id', '=', 'p.id')
                            ->whereYear('p.tanggalmulai', $tahun);
                    })
                    ->distinct('program_id')
                    ->count('program_id');

                return [
                    'nama' => $donor->nama,
                    'email' => $donor->email ?? '-',
                    'program_count' => $programCount,
                    'total_donated' => $totalDonated
                ];
            })
            ->filter(fn($d) => $d['total_donated'] > 0); // Only show donors with contributions

        // --- 4. Timeline Data (Funding by Year or Month) ---
        // --- 4. Timeline Data (Funding by Year or Month) ---
        if ($tahun) {
            // If year filter is active, show monthly breakdown
            $timelineData = DB::table('trprogrampendonor as pp')
                ->join('trprogram as p', 'pp.program_id', '=', 'p.id')
                ->selectRaw('MONTH(p.tanggalmulai) as month, SUM(pp.nilaidonasi) as total')
                ->when($programId, fn($q) => $q->where('pp.program_id', $programId))
                ->when($donorId, fn($q) => $q->where('pp.pendonor_id', $donorId))
                ->whereYear('p.tanggalmulai', $tahun)
                ->whereNotNull('p.tanggalmulai')
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get()
                ->map(function ($item) use ($programId, $donorId, $tahun) {
                    $monthNames = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    
                    // Fetch breakdown for this month
                    $breakdown = DB::table('trprogrampendonor as pp')
                        ->join('trprogram as p', 'pp.program_id', '=', 'p.id')
                        ->select('p.nama', DB::raw('SUM(pp.nilaidonasi) as program_total'))
                        ->when($programId, fn($q) => $q->where('pp.program_id', $programId))
                        ->when($donorId, fn($q) => $q->where('pp.pendonor_id', $donorId))
                        ->whereYear('p.tanggalmulai', $tahun)
                        ->whereMonth('p.tanggalmulai', $item->month)
                        ->groupBy('p.id', 'p.nama')
                        ->orderBy('program_total', 'desc')
                        ->get()
                        ->map(function($prog) use ($item) {
                            return [
                                'nama' => $prog->nama,
                                'total' => $prog->program_total,
                                'percentage' => $item->total > 0 ? ($prog->program_total / $item->total) * 100 : 0
                            ];
                        });

                    return [
                        'label' => $monthNames[$item->month],
                        'total' => $item->total,
                        'breakdown' => $breakdown
                    ];
                });
        } else {
            // If no year filter, show yearly data
            $timelineData = DB::table('trprogrampendonor as pp')
                ->join('trprogram as p', 'pp.program_id', '=', 'p.id')
                ->selectRaw('YEAR(p.tanggalmulai) as year, SUM(pp.nilaidonasi) as total')
                ->when($programId, fn($q) => $q->where('pp.program_id', $programId))
                ->when($donorId, fn($q) => $q->where('pp.pendonor_id', $donorId))
                ->whereNotNull('p.tanggalmulai')
                ->groupBy('year')
                ->orderBy('year', 'asc')
                ->get()
                ->map(function ($item) use ($programId, $donorId) {
                    // Fetch breakdown for this year
                    $breakdown = DB::table('trprogrampendonor as pp')
                        ->join('trprogram as p', 'pp.program_id', '=', 'p.id')
                        ->select('p.nama', DB::raw('SUM(pp.nilaidonasi) as program_total'))
                        ->when($programId, fn($q) => $q->where('pp.program_id', $programId))
                        ->when($donorId, fn($q) => $q->where('pp.pendonor_id', $donorId))
                        ->whereYear('p.tanggalmulai', $item->year)
                        ->groupBy('p.id', 'p.nama')
                        ->orderBy('program_total', 'desc')
                        ->get()
                        ->map(function($prog) use ($item) {
                            return [
                                'nama' => $prog->nama,
                                'total' => $prog->program_total,
                                'percentage' => $item->total > 0 ? ($prog->program_total / $item->total) * 100 : 0
                            ];
                        });

                    return [
                        'label' => (string)$item->year,
                        'total' => $item->total,
                        'breakdown' => $breakdown
                    ];
                });
        }

        return response()->json([
            'stats' => $stats,
            'sdgContribution' => $sdgContribution,
            'sektorContribution' => $sektorContribution,
            'donorList' => $donorList->values(),
            'timelineData' => $timelineData
        ]);
    }
}
