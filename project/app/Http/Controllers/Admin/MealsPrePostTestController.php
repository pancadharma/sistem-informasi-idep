<?php

namespace App\Http\Controllers\Admin;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Meals_PrePostTest;
use App\Models\Meals_PrePostTestPeserta;
use Illuminate\Support\Facades\Gate;

class MealsPrePostTestController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('prepost_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.prepost.index');
    }

    public function create()
    {
        // abort_if(Gate::denies('prepost_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $program = Program::all();
        return view('tr.prepost.create',compact('program'));
    }

    public function getPrePostDatatable(Request $request)
    {
        try {
            if ($request->ajax()) {
                $query = Meals_PrePostTest::with([
                    'programActivity.program_outcome_output.program_outcome.program',
                    'peserta' // eager load relasi peserta
                ])
                ->select('trmealspreposttest.*');

                return DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('program_name', fn($item) => $item->programActivity->program_outcome_output->program_outcome->program->nama ?? '-')
                    ->addColumn('kegiatan_name', fn($item) => $item->programActivity->nama ?? '-')
                    ->addColumn('trainingname', fn($item) => $item->trainingname ?? '-')
                    ->addColumn('tanggalmulai', fn($item) => $item->tanggalmulai?->format('d M Y') ?? '-')
                    ->addColumn('tanggalselesai', fn($item) => $item->tanggalselesai?->format('d M Y') ?? '-')
                    ->addColumn('total_peserta', fn($item) => $item->peserta->count()) // hitung jumlah peserta
                    ->addColumn('action', function ($item) {
                        $buttons = [];
                        if (auth()->user()->id === 1 || auth()->user()->can('prepost_edit')) {
                            $buttons[] = $this->generateButton('edit', 'info', 'pencil-square', __('global.edit') . ' PrePost ' . $item->id, $item->id);
                        }
                        if (auth()->user()->id === 1 || auth()->user()->can('prepost_view')) {
                            $buttons[] = $this->generateButton('view', 'primary', 'folder2-open', __('global.view') . ' PrePost ' . $item->id, $item->id);
                        }
                        return "<div class='button-container'>" . implode(' ', $buttons) . "</div>";
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            \Log::error('Error in getPrePostDatatable: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memuat data.'], 500);
        }
    }
    private function generateButton($action, $class, $icon, $title, $id)
    {
        return '<button type="button" title="' . $title . '" class="btn btn-sm btn-' . $class . ' ' . $action . '-prepost-btn" 
            data-action="' . $action . '" 
            data-prepost-id="' . $id . '" 
            data-toggle="tooltip" data-placement="top">
            <i class="bi bi-' . $icon . '"></i>
            <span class="d-none d-sm-inline"></span>
        </button>';
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'programoutcomeoutputactivity_id' => 'required|exists:trprogramoutcomeoutputactivity,id',
            'nama_pelatihan' => 'required|string|max:200',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'data' => 'required|array|min:1',
            'data.*.nama' => 'required|string|max:200',
            'data.*.gender' => 'required|string|max:50',
            'data.*.no_telp' => 'nullable|string|max:20',
            'data.*.pretest' => 'nullable|numeric',
            'data.*.prefill' => 'nullable|boolean',
            'data.*.posttest' => 'nullable|numeric',
            'data.*.postfill' => 'nullable|boolean',
            'data.*.selisih' => 'nullable|numeric',
            'data.*.notes' => 'nullable|string',
            'data.*.provinsi_id' => 'required|integer',
            'data.*.kabupaten_id' => 'required|integer',
            'data.*.kecamatan_id' => 'required|integer',
            'data.*.desa_id' => 'required|integer',
            'data.*.dusun_id' => 'required|exists:dusun,id',
        ]);

        DB::beginTransaction();

        try {
            // 1. Simpan header ke trmealspreposttest
            $prepost = Meals_PrePostTest::create([
                'programoutcomeoutputactivity_id' => $validated['programoutcomeoutputactivity_id'],
                'user_id' => $validated['user_id'],
                'trainingname' => $validated['nama_pelatihan'],
                'tanggalmulai' => $validated['start_date'],
                'tanggalselesai' => $validated['end_date'],
            ]);

            $inserted = 0;
            $skipped = 0;

            foreach ($validated['data'] as $row) {
                // Cek duplikat peserta
                $exists = DB::table('trmealspreposttestpeserta')
                    ->where('preposttest_id', $prepost->id)
                    ->where('dusun_id', $row['dusun_id'])
                    ->where('nama', $row['nama'])
                    ->whereNull('deleted_at')
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                DB::table('trmealspreposttestpeserta')->insert([
                    'preposttest_id' => $prepost->id,
                    'dusun_id' => $row['dusun_id'],
                    'nama' => $row['nama'],
                    'jeniskelamin' => $row['gender'],
                    'notelp' => $row['no_telp'] ?? null,
                    'prescore' => $row['pretest'] ?? null,
                    'filedbytraineepre' => (bool)($row['prefill'] ?? false),
                    'postscore' => $row['posttest'] ?? null,
                    'filedbytraineepost' => (bool)($row['postfill'] ?? false),
                    'valuechange' => $row['selisih'] ?? null,
                    'keterangan' => $row['notes'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $inserted++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Data berhasil disimpan. {$inserted} peserta disimpan, {$skipped} duplikat dilewati.",
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $prepost = Meals_PrePostTest::with([
            'programActivity.program_outcome_output.program_outcome.program',
            'user',
            'peserta.dusun.desa.kecamatan.kabupaten.provinsi',
        ])->findOrFail($id);

        $programId = optional($prepost->programActivity?->program_outcome_output?->program_outcome?->program)->id;
        $program = Program::select('id', 'kode', 'nama')->findOrFail($programId);
        $pesertaList = $prepost->peserta;

        // Buat array lokasi dari peserta
        $lokasiData = $pesertaList->map(function ($peserta) {
            return [
                'provinsi_id' => optional($peserta->dusun->desa->kecamatan->kabupaten->provinsi)->id,
                'provinsi_nama' => optional($peserta->dusun->desa->kecamatan->kabupaten->provinsi)->nama,
                'kabupaten_id' => optional($peserta->dusun->desa->kecamatan->kabupaten)->id,
                'kabupaten_nama' => optional($peserta->dusun->desa->kecamatan->kabupaten)->nama,
                'kecamatan_id' => optional($peserta->dusun->desa->kecamatan)->id,
                'kecamatan_nama' => optional($peserta->dusun->desa->kecamatan)->nama,
                'desa_id' => optional($peserta->dusun->desa)->id,
                'desa_nama' => optional($peserta->dusun->desa)->nama,
                'dusun_id' => optional($peserta->dusun)->id,
                'dusun_nama' => optional($peserta->dusun)->nama,
            ];
        })->values();

        return view('tr.prepost.edit', compact('prepost', 'program', 'pesertaList', 'lokasiData'));
    }
    public function storeAddPeserta(Request $request)
    {
        $validated = $request->validate([
            'programoutcomeoutputactivity_id' => 'required|exists:trprogramoutcomeoutputactivity,id',
            'user_id' => 'required|exists:users,id',
            'nama_pelatihan' => 'required|string|max:200',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'data' => 'required|array|min:1',

            'data.0.nama' => 'required|string|max:200',
            'data.0.gender' => 'required|string|max:50',
            'data.0.no_telp' => 'nullable|string|max:20',
            'data.0.pretest' => 'nullable|numeric',
            'data.0.prefill' => 'nullable|boolean',
            'data.0.posttest' => 'nullable|numeric',
            'data.0.postfill' => 'nullable|boolean',
            'data.0.notes' => 'nullable|string',
            'data.0.provinsi_id' => 'required|exists:provinsi,id',
            'data.0.kabupaten_id' => 'required|exists:kabupaten,id',
            'data.0.kecamatan_id' => 'required|exists:kecamatan,id',
            'data.0.desa_id' => 'required|exists:kelurahan,id',
            'data.0.dusun_id' => 'required|exists:dusun,id',
        ]);

        $row = $validated['data'][0];

        // Cek duplikat peserta
        $exists = DB::table('trmealspreposttestpeserta')
            ->where('preposttest_id', function ($q) use ($validated) {
                $q->select('id')
                ->from('trmealspreposttest')
                ->where('programoutcomeoutputactivity_id', $validated['programoutcomeoutputactivity_id'])
                ->where('trainingname', $validated['nama_pelatihan'])
                ->whereDate('tanggalmulai', $validated['start_date'])
                ->whereDate('tanggalselesai', $validated['end_date'])
                ->limit(1);
            })
            ->where('dusun_id', $row['dusun_id'])
            ->where('nama', $row['nama'])
            ->whereNull('deleted_at')
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Peserta dengan nama dan lokasi yang sama sudah terdaftar.',
            ], 409);
        }

        // Temukan header preposttest
        $prepost = Meals_PrePostTest::where('programoutcomeoutputactivity_id', $validated['programoutcomeoutputactivity_id'])
            ->where('trainingname', $validated['nama_pelatihan'])
            ->whereDate('tanggalmulai', $validated['start_date'])
            ->whereDate('tanggalselesai', $validated['end_date'])
            ->firstOrFail();

        // Hitung value change jika bisa
        $valueChange = null;
        if (is_numeric($row['posttest'] ?? null) && is_numeric($row['pretest'] ?? null)) {
            $valueChange = $row['posttest'] - $row['pretest'];
        }

        $peserta = Meals_PrePostTestPeserta::create([
            'preposttest_id' => $prepost->id,
            'dusun_id' => $row['dusun_id'],
            'nama' => $row['nama'],
            'jeniskelamin' => $row['gender'],
            'notelp' => $row['no_telp'] ?? null,
            'prescore' => $row['pretest'] ?? null,
            'filedbytraineepre' => (bool)($row['prefill'] ?? false),
            'postscore' => $row['posttest'] ?? null,
            'filedbytraineepost' => (bool)($row['postfill'] ?? false),
            'valuechange' => $valueChange,
            'keterangan' => $row['notes'] ?? null,
        ]);

        return response()->json([
            'message' => 'Data peserta berhasil disimpan.',
            'data' => $peserta,
        ]);
    }
    public function getPesertaById($id)
    {
        $peserta = Meals_PrePostTestPeserta::with([
            'dusun:id,nama,desa_id',
            'dusun.desa:id,nama,kecamatan_id',
            'dusun.desa.kecamatan:id,nama,kabupaten_id',
            'dusun.desa.kecamatan.kabupaten:id,nama,provinsi_id',
            'dusun.desa.kecamatan.kabupaten.provinsi:id,nama',
        ])->findOrFail($id);

        return response()->json([
            'id' => $peserta->id,
            'nama' => $peserta->nama,
            'gender' => $peserta->jeniskelamin,
            'no_telp' => $peserta->notelp,
            'pretest' => $peserta->prescore,
            'prefill' => $peserta->filedbytraineepre,
            'posttest' => $peserta->postscore,
            'postfill' => $peserta->filedbytraineepost,
            'valuechange' => $peserta->valuechange,
            'notes' => $peserta->keterangan,
            'provinsi_id' => $peserta->dusun->desa->kecamatan->kabupaten->provinsi->id ?? null,
            'provinsi_nama' => $peserta->dusun->desa->kecamatan->kabupaten->provinsi->nama ?? '',
            'kabupaten_id' => $peserta->dusun->desa->kecamatan->kabupaten->id ?? null,
            'kabupaten_nama' => $peserta->dusun->desa->kecamatan->kabupaten->nama ?? '',
            'kecamatan_id' => $peserta->dusun->desa->kecamatan->id ?? null,
            'kecamatan_nama' => $peserta->dusun->desa->kecamatan->nama ?? '',
            'desa_id' => $peserta->dusun->desa->id ?? null,
            'desa_nama' => $peserta->dusun->desa->nama ?? '',
            'dusun_id' => $peserta->dusun->id,
            'dusun_nama' => $peserta->dusun->nama,
        ]);
    }
    public function updateSinglePeserta(Request $request, $id)
    {
        $peserta = Meals_PrePostTestPeserta::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jeniskelamin' => 'required', // sesuaikan jika kamu pakai enum lain
            'notelp' => 'nullable|string|max:50',
            'prescore' => 'nullable|numeric|min:0',
            'filedbytraineepre' => 'required|in:0,1', // pakai in biar boolean form friendly
            'postscore' => 'nullable|numeric|min:0',
            'filedbytraineepost' => 'required|in:0,1',
            'valuechange' => 'nullable|numeric|min:0', // ini akan dihitung ulang di bawah
            'keterangan' => 'nullable|string|max:1000',
            'provinsi_id' => 'required|exists:provinsi,id',
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'desa_id' => 'required|exists:kelurahan,id',
            'dusun_id' => 'required|exists:dusun,id',
        ]);

        // Hitung selisih secara pasti
        $validated['valuechange'] = max(0, ($validated['postscore'] ?? 0) - ($validated['prescore'] ?? 0));

        // Update peserta
        $peserta->update($validated);

        return response()->json(['message' => 'Data peserta berhasil diperbarui.']);
    }
    public function deletePeserta($id)
    {
        $peserta = Meals_PrePostTestPeserta::find($id);

        if (!$peserta) {
            return response()->json([
                'success' => false,
                'message' => 'Peserta tidak ditemukan.',
            ], 404);
        }

        $peserta->delete();

        return response()->json([
            'success' => true,
            'message' => 'Peserta berhasil dihapus.',
        ]);
    }
    public function updatePrePostHeader(Request $request, $id)
    {
        $request->validate([
            //'program_id' => 'nullable|exists:msprogramoutcomeoutputactivity,id', // sesuaikan nama tabel jika beda
            'nama_pelatihan' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $prepost = Meals_PrePostTest::findOrFail($id);

        $prepost->update([
            'trainingname' => $request->nama_pelatihan,
            'tanggalmulai' => $request->tanggal_mulai,
            'tanggalselesai' => $request->tanggal_selesai,
            'user_id' => auth()->id(), // jika kamu ingin mencatat siapa yang update
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data pelatihan berhasil diperbarui.'
        ]);
    }




}
