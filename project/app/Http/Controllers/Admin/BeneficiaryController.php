<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Meals_Penerima_Manfaat;
use App\Models\Meals_Penerima_Manfaat_Activity;
use App\Models\Meals_Penerima_Manfaat_Jenis_Kelompok;
use App\Models\Meals_Penerima_Manfaat_Kelompok_Marjinal;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;

class BeneficiaryController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('beneficiary_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.beneficiary.index');
    }

    public function create()
    {
        abort_if(Gate::denies('beneficiary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.beneficiary.create');
    }

    public function wilayah()
    {
        abort_if(Gate::denies('beneficiary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.beneficiary.wilayah');
    }
    // public function store(Request $request)
    // {
    //     abort_if(Gate::denies('beneficiary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     // Validate the incoming request data
    //     $validated = $request->validate([
    //         'program_id'             => 'required|integer',
    //         'data'                   => 'required|array',
    //         'data.*.nama'            => 'required|string|max:255',
    //         'data.*.no_telp'         => 'nullable|string|max:15',
    //         'data.*.gender'          => 'required|in:laki,perempuan,lainnya',
    //         'data.*.rt'              => 'required|string|max:10',
    //         'data.*.rw'              => 'required|string|max:10',
    //         'data.*.dusun_id'        => 'required|integer',
    //         'data.*.usia'            => 'required|integer|min:0',
    //         'data.*.is_non_activity' => 'boolean',
    //         'data.*.keterangan'      => 'nullable|string',
    //         'data.*.jenis_kelompok'  => 'nullable|array',
    //         'data.*.kelompok_rentan' => 'nullable|array',
    //         'data.*.activitySelect'  => 'nullable|array',
    //     ]);

    //     $programId = $request->input('program_id');
    //     $userId = auth()->id(); // Get the authenticated user's ID
    //     $beneficiaries = $request->input('data');

    //     // Loop through each beneficiary and save it
    //     // foreach ($beneficiaries as $beneficiary) {
    //     //     // Create the Meals_Penerima_Manfaat record
    //     //     $penerima = Meals_Penerima_Manfaat::create([
    //     //         'program_id' => $programId,
    //     //         'user_id' => $userId,
    //     //         'dusun_id' => $beneficiary['dusun_id'],
    //     //         'nama' => $beneficiary['nama'],
    //     //         'no_telp' => $beneficiary['no_telp'] ?? null,
    //     //         'jenis_kelamin' => $beneficiary['gender'], // Adjusted to match column name
    //     //         'rt' => $beneficiary['rt'],
    //     //         'rw' => $beneficiary['rw'],
    //     //         'umur' => $beneficiary['usia'], // Adjusted to match column name
    //     //         'keterangan' => $beneficiary['keterangan'] ?? null,
    //     //         'is_non_activity' => $beneficiary['is_non_activity'] ?? false,
    //     //     ]);

    //     //     // Sync jenis_kelompok (Master_Jenis_Kelompok)
    //     //     if (!empty($beneficiary['jenis_kelompok'])) {
    //     //         $penerima->jenisKelompok()->sync($beneficiary['jenis_kelompok']);
    //     //     }

    //     //     // Sync kelompok_marjinal (Kelompok_Marjinal)
    //     //     if (!empty($beneficiary['kelompok_rentan'])) {
    //     //         $penerima->kelompokMarjinal()->sync($beneficiary['kelompok_rentan']);
    //     //     }

    //     //     // Sync activities (Program_Outcome_Output_Activity)
    //     //     if (!empty($beneficiary['activitySelect'])) {
    //     //         $penerima->penerimaActivity()->sync($beneficiary['activitySelect']);
    //     //     }
    //     // }

    //     foreach ($beneficiaries as $beneficiary) {
    //         $penerima = Meals_Penerima_Manfaat::create([
    //             'program_id' => $programId,
    //             'user_id' => $userId,
    //             'dusun_id' => $beneficiary['dusun_id'],
    //             'nama' => $beneficiary['nama'],
    //             'no_telp' => $beneficiary['no_telp'] ?? null,
    //             'jenis_kelamin' => $beneficiary['gender'],
    //             'rt' => $beneficiary['rt'],
    //             'rw' => $beneficiary['rw'],
    //             'umur' => $beneficiary['usia'],
    //             'keterangan' => $beneficiary['keterangan'] ?? null,
    //             'is_non_activity' => $beneficiary['is_non_activity'] ?? false,
    //         ]);

    //         // Manually create pivot records for jenis_kelompok
    //         if (!empty($beneficiary['jenis_kelompok'])) {
    //             foreach ($beneficiary['jenis_kelompok'] as $jenisKelompokId) {
    //                 Meals_Penerima_Manfaat_Jenis_Kelompok::create([
    //                     'trmeals_penerima_manfaat_id' => $penerima->id,
    //                     'jenis_kelompok_id' => $jenisKelompokId,
    //                 ]);
    //             }
    //         }

    //         // Manually create pivot records for kelompok_marjinal
    //         if (!empty($beneficiary['kelompok_rentan'])) {
    //             foreach ($beneficiary['kelompok_rentan'] as $kelompokMarjinalId) {
    //                 Meals_Penerima_Manfaat_Kelompok_Marjinal::create([
    //                     'trmeals_penerima_manfaat_id' => $penerima->id,
    //                     'kelompok_marjinal_id' => $kelompokMarjinalId,
    //                 ]);
    //             }
    //         }

    //         // Manually create pivot records for activities
    //         if (!empty($beneficiary['activitySelect'])) {
    //             foreach ($beneficiary['activitySelect'] as $activityId) {
    //                 Meals_Penerima_Manfaat_Activity::create([
    //                     'trmeals_penerima_manfaat_id' => $penerima->id,
    //                     'programoutcomeoutputactivity_id' => $activityId,
    //                 ]);
    //             }
    //         }
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Beneficiaries created successfully!',
    //     ], Response::HTTP_CREATED);
    // }

    public function store(Request $request)
    {
        abort_if(Gate::denies('beneficiary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validated = $request->validate([
            'program_id'             => 'required|integer',
            'data'                   => 'required|array',
            'data.*.nama'            => 'required|string|max:255',
            'data.*.no_telp'         => 'nullable|string|max:15',
            'data.*.gender'          => 'required|in:laki,perempuan,lainnya',
            'data.*.rt'              => 'required|string|max:10',
            'data.*.rw'              => 'required|string|max:10',
            'data.*.dusun_id'        => 'required|integer',
            'data.*.usia'            => 'required|integer|min:0',
            'data.*.is_non_activity' => 'boolean',
            'data.*.keterangan'      => 'nullable|string',
            'data.*.jenis_kelompok'  => 'nullable|array',
            'data.*.kelompok_rentan' => 'nullable|array',
            'data.*.activitySelect'  => 'nullable|array',
        ]);

        $programId = $request->input('program_id');
        $userId = auth()->id();
        $beneficiaries = $request->input('data');

        DB::beginTransaction();

        try {
            foreach ($beneficiaries as $beneficiary) {
                $penerima = Meals_Penerima_Manfaat::create([
                    'program_id' => $programId,
                    'user_id' => $userId,
                    'dusun_id' => $beneficiary['dusun_id'],
                    'nama' => $beneficiary['nama'],
                    'no_telp' => $beneficiary['no_telp'] ?? null,
                    'jenis_kelamin' => $beneficiary['gender'],
                    'rt' => $beneficiary['rt'],
                    'rw' => $beneficiary['rw'],
                    'umur' => $beneficiary['usia'],
                    'keterangan' => $beneficiary['keterangan'] ?? null,
                    'is_non_activity' => $beneficiary['is_non_activity'] ?? false,
                ]);

                // Sync pivot table relationships
                if (!empty($beneficiary['jenis_kelompok'])) {
                    $penerima->jenisKelompok()->sync($beneficiary['jenis_kelompok']);
                }

                if (!empty($beneficiary['kelompok_rentan'])) {
                    $penerima->kelompokMarjinal()->sync($beneficiary['kelompok_rentan']);
                }

                if (!empty($beneficiary['activitySelect'])) {
                    $penerima->penerimaActivity()->sync($beneficiary['activitySelect']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Beneficiaries created successfully!',
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating beneficiaries.',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        abort_if(Gate::denies('beneficiary_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $beneficiaries = Meals_Penerima_Manfaat::where('program_id', $id)
        //     ->get(['nama', 'jenis_kelamin', 'rt', 'rw', 'umur']);

        if (!empty($id)) {

            $beneficiaries = Meals_Penerima_Manfaat::with('jenisKelompok', 'kelompokMarjinal', 'penerimaActivity')
                ->where('program_id', $id)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $beneficiaries,
            ], Response::HTTP_OK);
        }
        return response()->json([
            'success' => false,
            'message' => 'Beneficiaries not found.',
        ], Response::HTTP_NOT_FOUND);
    }
}
