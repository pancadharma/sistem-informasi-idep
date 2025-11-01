<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Meals_Penerima_Manfaat;
use App\Models\Meals_Penerima_Manfaat_Activity;
use App\Models\Meals_Penerima_Manfaat_Jenis_Kelompok;
use App\Models\Meals_Penerima_Manfaat_Kelompok_Marjinal;
use App\Models\Program;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

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
    // public function edit(Program $program)
    // {
    //     abort_if(Gate::denies('beneficiary_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    //     $beneficiaries = Meals_Penerima_Manfaat::select('trmeals_penerima_manfaat.*')
    //         ->where('program_id', $program->id)
    //         ->with([
    //             'jenisKelompok'     => fn($query) => $query->select('master_jenis_kelompok.id', 'nama'),
    //             'kelompokMarjinal'  => fn($query) => $query->select('mkelompokmarjinal.id', 'nama'),
    //             'penerimaActivity'  => fn($query) => $query->select('trprogramoutcomeoutputactivity.id', 'nama', 'kode'),
    //             'dusun'             => fn($query) => $query->select('dusun.id', 'nama', 'desa_id'),
    //             'dusun.desa'        => fn($query) => $query->select('kelurahan.id', 'nama', 'kecamatan_id'),
    //             'dusun.desa.kecamatan' => fn($query) => $query->select('kecamatan.id', 'nama', 'kabupaten_id'),
    //             'dusun.desa.kecamatan.kabupaten' => fn($query) => $query->select('kabupaten.id', 'nama', 'provinsi_id'),
    //             'dusun.desa.kecamatan.kabupaten.provinsi' => fn($query) => $query->select('provinsi.id', 'nama')
    //         ])
    //         ->get();
    //     $activities = $program->programOutputActivities()->select(['id', 'kode', 'nama'])->get();
    //     // return $beneficiaries;
    //     return view('tr.beneficiary.edit', compact('program', 'beneficiaries', 'activities'));
    // }

    public function edit(Program $program)
    {
        abort_if(Gate::denies('beneficiary_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $beneficiaries = Meals_Penerima_Manfaat::select('trmeals_penerima_manfaat.*')
            ->where('program_id', $program->id)
            ->with([
                'jenisKelompok:id,nama',
                'kelompokMarjinal:id,nama',
                'penerimaActivity:id,nama,kode',
                'dusun:id,nama,desa_id',
                'dusun.desa:id,nama,kecamatan_id',
                'dusun.desa.kecamatan:id,nama,kabupaten_id',
                'dusun.desa.kecamatan.kabupaten:id,nama,provinsi_id',
                'dusun.desa.kecamatan.kabupaten.provinsi:id,nama',
            ])
            ->get();

        $activities = $program->programOutputActivities()->select(['id', 'kode', 'nama'])->get();

        return view('tr.beneficiary.edit', compact('program', 'beneficiaries', 'activities'));
    }


    public function store(Request $request)
    {
        abort_if(Gate::denies('beneficiary_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $validated = $request->validate([
            'program_id'              => 'required|integer',
            'data'                    => 'required|array',
            'data.*.nama'             => 'required|string|max:255',
            'data.*.is_head_family'   => 'nullable|boolean',
            'data.*.head_family_name' => 'nullable|string|max:255',
            'data.*.no_telp'          => 'nullable|string|max:15',
            'data.*.gender'           => 'required|in:laki,perempuan,lainnya',
            'data.*.rt'               => 'required|string|max:10',
            'data.*.rw'               => 'required|string|max:10',
            'data.*.dusun_id'         => 'required|integer',
            'data.*.usia'             => 'required|integer|min:0',
            'data.*.is_non_activity'  => 'boolean',
            'data.*.keterangan'       => 'nullable|string',
            'data.*.jenis_kelompok'   => 'nullable|array',
            'data.*.kelompok_rentan'  => 'nullable|array',
            'data.*.activitySelect'   => 'nullable|array',
        ]);

        $programId = $request->input('program_id');
        $userId = auth()->id();
        $beneficiaries = $request->input('data');

        DB::beginTransaction();

        try {
            foreach ($beneficiaries as $beneficiary) {
                $penerima = Meals_Penerima_Manfaat::create([
                    'program_id'       => $programId,
                    'user_id'          => $userId,
                    'dusun_id'         => $beneficiary['dusun_id'],
                    'nama'             => $beneficiary['nama'],
                    'no_telp'          => $beneficiary['no_telp'] ?? null,
                    'jenis_kelamin'    => $beneficiary['gender'],
                    'rt'               => $beneficiary['rt'],
                    'rw'               => $beneficiary['rw'],
                    'umur'             => $beneficiary['usia'],
                    'keterangan'       => $beneficiary['keterangan'] ?? null,
                    'is_non_activity'  => $beneficiary['is_non_activity'] ?? false,
                    'is_head_family'   => $beneficiary['is_head_family'] ?? false,
                    'head_family_name' => $beneficiary['head_family_name'] ?? null,
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
                'data' => [
                    'redirect_url' => route('beneficiary.index'), // contoh
                ],
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


    public function deleteBeneficiary($id)
    {
        // abort_if(Gate::denies('beneficiary_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $beneficiary = Meals_Penerima_Manfaat::findOrFail($id);
        if (!$beneficiary) {
            return response()->json([
                'success' => false,
                'message' => 'Beneficiary not found.',
            ], Response::HTTP_NOT_FOUND);
        }
        $beneficiary->delete();
        return response()->json([
            'success' => true,
            'message' => 'Beneficiary deleted successfully.',
        ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $beneficiary = Meals_Penerima_Manfaat::findOrFail($id);
        $beneficiary->delete();
        return response()->json(['message' => 'Beneficiary deleted'], 204);
    }

    public function storeBeneficiary(Request $request){

        $validated = $request->validate([
            'program_id'          => 'required|integer',
            'user_id'             => 'required|integer',
            'nama'                => 'required|string|max:255',
            'no_telp'             => 'nullable|string|max:15',
            'jenis_kelamin'       => 'required|in:laki,perempuan,lainnya',
            'umur'                => 'required|integer|min:0',
            'rt'                  => 'required|string|max:10',
            'rw'                  => 'required|string|max:10',
            'dusun_id'            => 'required|integer',
            'is_non_activity'     => 'boolean',
            'keterangan'          => 'nullable|string',

            // 🔥 New fields 🔥
            'is_head_family'      => 'boolean',
            'head_family_name'    => $request->input('is_head_family')
                                      ? 'nullable|string|max:255'
                                      : 'required|string|max:255',

            // 'head_family_name'   => [
            //     'string', 'max:255',
            //     function ($attribute, $value, $fail) use ($request) {
            //         if (!$request->boolean('is_head_family') && empty($value)) {
            //             $fail('The head family name is required if not the head of the family.');
            //         }
            //     }
            // ],

            // Pivot arrays
            'kelompok_rentan'     => 'nullable|array',
            'kelompok_rentan.*'   => 'integer',
            'jenis_kelompok'      => 'nullable|array',
            'jenis_kelompok.*'    => 'integer',
            'activity_ids'        => 'nullable|array',
            'activity_ids.*'      => 'integer',
        ]);


        DB::beginTransaction();
        try {
            // $beneficiary = Meals_Penerima_Manfaat::create($request->only('program_id', 'user_id','nama', 'no_telp', 'jenis_kelamin', 'umur', 'rt', 'rw', 'dusun_id', 'is_non_activity', 'keterangan', 'is_head_family', 'head_family_name'));
            $beneficiary = Meals_Penerima_Manfaat::create(Arr::only($validated, [
                'program_id', 'user_id','nama', 'no_telp', 'jenis_kelamin', 'umur',
                'rt', 'rw', 'dusun_id', 'is_non_activity', 'keterangan',
                'is_head_family', 'head_family_name'
            ]));
            
            $beneficiary->kelompokMarjinal()->sync($request->kelompok_rentan);
            $beneficiary->jenisKelompok()->sync($request->jenis_kelompok);
            $beneficiary->penerimaActivity()->sync($request->activity_ids);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Beneficiary created',
                'data' => $beneficiary
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create beneficiary',
                'error' => $e->getMessage()], 500);
        }
    }

    public function getBeneficiaryData($id){
        $beneficiaries = Meals_Penerima_Manfaat::select('trmeals_penerima_manfaat.*')
        ->where('id', $id)
        ->with([
            'jenisKelompok'     => fn($query) => $query->select('master_jenis_kelompok.id', 'nama'),
            'kelompokMarjinal'  => fn($query) => $query->select('mkelompokmarjinal.id', 'nama'),
            'penerimaActivity'  => fn($query) => $query->select('trprogramoutcomeoutputactivity.id', 'nama', 'kode'),
            'dusun'             => fn($query) => $query->select('dusun.id', 'nama', 'desa_id'),
            'dusun.desa'        => fn($query) => $query->select('kelurahan.id', 'nama', 'kecamatan_id'),
            'dusun.desa.kecamatan' => fn($query) => $query->select('kecamatan.id', 'nama', 'kabupaten_id'),
            'dusun.desa.kecamatan.kabupaten' => fn($query) => $query->select('kabupaten.id', 'nama', 'provinsi_id'),
            'dusun.desa.kecamatan.kabupaten.provinsi' => fn($query) => $query->select('provinsi.id', 'nama')
        ])
        ->get();
        return response()->json($beneficiaries);
    }

    // public function editBeneficiary(Request $request, $id){
    //     DB::beginTransaction();
    //     try {
    //         $beneficiary = Meals_Penerima_Manfaat::findOrFail($id);
    //         $beneficiary->nama = $request->input('nama');
    //         $beneficiary->no_telp = $request->input('no_telp');
    //         $beneficiary->jenis_kelamin = $request->input('jenis_kelamin');
    //         $beneficiary->umur = $request->input('umur');
    //         $beneficiary->rt = $request->input('rt');
    //         $beneficiary->rw = $request->input('rw');
    //         $beneficiary->dusun_id = $request->input('dusun_id');
    //         $beneficiary->is_non_activity = $request->input('is_non_activity');

    //         $beneficiary->is_head_family = $request->input('is_head_family');
    //         $beneficiary->head_family_name = $request->input('head_family_name');

    //         $beneficiary->keterangan = $request->input('keterangan');

    //         $beneficiary->save();
    //         $beneficiary->kelompokMarjinal()->sync($request->kelompok_rentan);
    //         $beneficiary->jenisKelompok()->sync($request->jenis_kelompok);
    //         $beneficiary->penerimaActivity()->sync($request->activity_ids);

    //         DB::commit();

    //         $beneficiaries = Meals_Penerima_Manfaat::select('trmeals_penerima_manfaat.*')
    //         ->where('id', $id)
    //         ->with([
    //             'jenisKelompok'     => fn($query) => $query->select('master_jenis_kelompok.id', 'nama'),
    //             'kelompokMarjinal'  => fn($query) => $query->select('mkelompokmarjinal.id', 'nama'),
    //             'penerimaActivity'  => fn($query) => $query->select('trprogramoutcomeoutputactivity.id', 'nama', 'kode'),
    //             'dusun'             => fn($query) => $query->select('dusun.id', 'nama', 'desa_id'),
    //             'dusun.desa'        => fn($query) => $query->select('kelurahan.id', 'nama', 'kecamatan_id'),
    //             'dusun.desa.kecamatan' => fn($query) => $query->select('kecamatan.id', 'nama', 'kabupaten_id'),
    //             'dusun.desa.kecamatan.kabupaten' => fn($query) => $query->select('kabupaten.id', 'nama', 'provinsi_id'),
    //             'dusun.desa.kecamatan.kabupaten.provinsi' => fn($query) => $query->select('provinsi.id', 'nama')
    //         ])
    //         ->get();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Beneficiary updated',
    //             'data' => $beneficiaries
    //         ], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'data' => $request->all(),
    //             'message' => 'Failed to update beneficiary',
    //             'error' => $e->getMessage()], 500);
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'data' => $request->all(),
    //             'message' => 'Failed to update beneficiary',
    //             'error' => $th->getMessage()], 500);
    //     } catch (\Error $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'success' => false,
    //             'data' => $request->all(),
    //             'message' => 'Failed to update beneficiary',
    //             'error' => $e->getMessage()], 500);
    //     }
    // }

    public function updateDataBeneficiary(Request $request, $id)
    {
        abort_if(Gate::denies('beneficiary_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validated = $request->validate([
            'nama'               => 'required|string|max:255',
            'no_telp'            => 'nullable|string|max:15',
            'jenis_kelamin'      => 'required|in:laki,perempuan,lainnya',
            'umur'               => 'required|integer|min:0',
            'rt'                 => 'required|string|max:10',
            'rw'                 => 'required|string|max:10',
            'dusun_id'           => 'required|integer|exists:dusun,id',
            'is_non_activity'    => 'boolean',
            'keterangan'         => 'nullable|string',

            // ← NEW VALIDATION RULES
            'is_head_family'     => 'boolean',
            'head_family_name'   => $request->input('is_head_family')
                                    ? 'nullable|string|max:255'
                                    : 'required|string|max:255',
            'kelompok_rentan'    => 'nullable|array',
            'kelompok_rentan.*'  => 'integer|exists:mkelompokmarjinal,id',
            'jenis_kelompok'     => 'nullable|array',
            'jenis_kelompok.*'   => 'integer|exists:master_jenis_kelompok,id',
            'activity_ids'       => 'nullable|array',
            'activity_ids.*'     => 'integer|exists:trprogramoutcomeoutputactivity,id',
        ]);

        DB::beginTransaction();

        try {
            $b = Meals_Penerima_Manfaat::findOrFail($id);

            // scalar fields
            $b->fill([
                'nama'              => $validated['nama'],
                'no_telp'           => $validated['no_telp'] ?? null,
                'jenis_kelamin'     => $validated['jenis_kelamin'],
                'umur'              => $validated['umur'],
                'rt'                => $validated['rt'],
                'rw'                => $validated['rw'],
                'dusun_id'          => $validated['dusun_id'],
                'is_non_activity'   => $validated['is_non_activity'] ?? false,
                'keterangan'        => $validated['keterangan'] ?? null,

                // ← NEW FIELDS
                'is_head_family'    => $validated['is_head_family'] ?? false,
                'head_family_name'  => $validated['head_family_name'] ?? null,
            ]);
            $b->save();

            // pivot syncs
            $b->kelompokMarjinal()->sync($validated['kelompok_rentan'] ?? []);
            $b->jenisKelompok()->sync($validated['jenis_kelompok'] ?? []);
            $b->penerimaActivity()->sync($validated['activity_ids'] ?? []);

            DB::commit();

            // reload with relationships...
            $data = Meals_Penerima_Manfaat::with('jenisKelompok', 'kelompokMarjinal', 'penerimaActivity')
                ->where('id', $id)
                ->find($id);

                // )->find($id);

                // ->with([
                //     'jenisKelompok'     => fn($query) => $query->select('master_jenis_kelompok.id', 'nama'),
                //     'kelompokMarjinal'  => fn($query) => $query->select('mkelompokmarjinal.id', 'nama'),
                //     'penerimaActivity'  => fn($query) => $query->select('trprogramoutcomeoutputactivity.id', 'nama', 'kode'),
                //     'dusun'             => fn($query) => $query->select('dusun.id', 'nama', 'desa_id'),
                //     'dusun.desa'        => fn($query) => $query->select('kelurahan.id', 'nama', 'kecamatan_id'),
                //     'dusun.desa.kecamatan' => fn($query) => $query->select('kecamatan.id', 'nama', 'kabupaten_id'),
                //     'dusun.desa.kecamatan.kabupaten' => fn($query) => $query->select('kabupaten.id', 'nama', 'provinsi_id'),
                //     'dusun.desa.kecamatan.kabupaten.provinsi' => fn($query) => $query->select('provinsi.id', 'nama')
                // ])

            return response()->json([
                'success' => true,
                'message' => 'Beneficiary updated',
                'data'    => $data,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update beneficiary',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

}