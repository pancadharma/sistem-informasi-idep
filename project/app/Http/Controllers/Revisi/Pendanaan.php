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

        $stats = [
            'totalFunding' => $totalFunding,
            'totalDonors' => $totalDonors,
            'totalPrograms' => $totalPrograms,
            'avgDonation' => $avgDonation
        ];

        // --- 1. SDG Contribution ---
        $sdgContribution = DB::table('trprogramkaitansdg as psdg')
            ->join('mkaitansdg as sdg', 'psdg.kaitansdg_id', '=', 'sdg.id')
            ->join('trprogrampendonor as pp', 'psdg.program_id', '=', 'pp.program_id')
            ->select('sdg.nama as sdg_name', DB::raw('SUM(pp.nilaidonasi) as total'))
            ->when($programId, fn($q) => $q->where('psdg.program_id', $programId))
            ->when($donorId, fn($q) => $q->where('pp.pendonor_id', $donorId))
            ->when($tahun, function ($q) use ($tahun) {
                $q->join('trprogram as p', 'psdg.program_id', '=', 'p.id')
                    ->whereYear('p.tanggalmulai', $tahun);
            })
            ->groupBy('sdg.id', 'sdg.nama')
            ->orderBy('total', 'desc')
            ->get();

        // --- 2. Sector Contribution (from Kegiatan/Transaksi) ---
        $sektorContribution = DB::table('trkegiatan as k')
            ->join('trkegiatan_sektor as ks', 'k.id', '=', 'ks.kegiatan_id')
            ->join('mtargetreinstra as s', 'ks.sektor_id', '=', 's.id')
            // Join through the hierarchy to get to program
            ->join('trprogramoutcomeoutputactivity as pooa', 'k.programoutcomeoutputactivity_id', '=', 'pooa.id')
            ->join('trprogramoutcomeoutput as poo', 'pooa.programoutcomeoutput_id', '=', 'poo.id')
            ->join('trprogramoutcome as po', 'poo.programoutcome_id', '=', 'po.id')
            ->join('trprogrampendonor as pp', 'po.program_id', '=', 'pp.program_id')
            ->select('s.nama as sektor_name', DB::raw('SUM(pp.nilaidonasi) as total'))
            ->when($programId, fn($q) => $q->where('po.program_id', $programId))
            ->when($donorId, fn($q) => $q->where('pp.pendonor_id', $donorId))
            ->when($tahun, function ($q) use ($tahun) {
                $q->join('trprogram as p', 'po.program_id', '=', 'p.id')
                    ->whereYear('p.tanggalmulai', $tahun);
            })
            ->groupBy('s.id', 's.nama')
            ->orderBy('total', 'desc')
            ->get();

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

        // --- 4. Timeline Data (Funding by Year) ---
        $timelineData = DB::table('trprogrampendonor as pp')
            ->join('trprogram as p', 'pp.program_id', '=', 'p.id')
            ->selectRaw('YEAR(p.tanggalmulai) as year, SUM(pp.nilaidonasi) as total')
            ->when($programId, fn($q) => $q->where('pp.program_id', $programId))
            ->when($donorId, fn($q) => $q->where('pp.pendonor_id', $donorId))
            ->whereNotNull('p.tanggalmulai')
            ->groupBy('year')
            ->orderBy('year', 'asc')
            ->get();

        return response()->json([
            'stats' => $stats,
            'sdgContribution' => $sdgContribution,
            'sektorContribution' => $sektorContribution,
            'donorList' => $donorList->values(),
            'timelineData' => $timelineData
        ]);
    }
}
