<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Meals_Penerima_Manfaat;
use App\Models\Meals_Penerima_Manfaat_Activity;
use App\Models\Meals_Penerima_Manfaat_Jenis_Kelompok;
use App\Models\Meals_Penerima_Manfaat_Kelompok_Marjinal;
use App\Models\Program;
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
    public function edit(Program $program)
    {
        abort_if(Gate::denies('beneficiary_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $data = Meals_Penerima_Manfaat::where('program_id', $program->id)->with(['penerimaActivity', 'jenisKelompok', 'kelompokMarjinal'])->get();
        $beneficiaries = Meals_Penerima_Manfaat::select('trmeals_penerima_manfaat.*')
        ->where('program_id', $program->id)
        ->with([
            'jenisKelompok'     => fn($query) => $query->select('master_jenis_kelompok.id','nama'),
            'kelompokMarjinal'  => fn($query) => $query->select('mkelompokmarjinal.id','nama'),
            'penerimaActivity'  => fn($query) => $query->select('trprogramoutcomeoutputactivity.id', 'nama', 'kode'),
            'dusun.desa'        => fn($query) => $query->select('kelurahan.id', 'nama') // Eager load desa through dusun
        ])
        ->get();

        $activities = $program->programOutputActivities()->get(['id', 'kode', 'nama']);

        return view('tr.beneficiary.edit', compact('program', 'beneficiaries', 'activities'));
    }

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
