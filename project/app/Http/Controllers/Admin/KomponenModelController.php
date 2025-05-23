<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\KomponenModel;
use Illuminate\Http\Response;
use App\Models\TargetReinstra;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Meals_Komponen_Model;
use App\Models\Meals_Komponen_Model_Lokasi;
use App\Models\Meals_Komponen_Moldel_Target_Reinstra;
use App\Models\Program;
use App\Models\Satuan;
use Illuminate\Support\Facades\Gate;

class KomponenModelController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('komponenmodel_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.komponenmodel.index');
    }

    public function create()
    {
        abort_if(Gate::denies('komponenmodel_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('tr.komponenmodel.create');
    }

    public function getSektor(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);

        if (!is_array($ids) && $ids !== null) {
            $ids = [$ids];
        }

        // Ambil data dari TargetReinstra
        $query = TargetReinstra::when(!empty($ids), function ($q) use ($ids) {
            return $q->whereIn('id', $ids);
        }, function ($q) use ($search) {
            return $q->where('nama', 'like', "%{$search}%");
        });

        // Ambil data sesuai Select2
        $data = $query->select('id', 'nama')->paginate(20, ['*'], 'page', $page);

        return response()->json([
            'results' => $data->items(), // hanya ambil data
            'pagination' => [
                'more' => $data->hasMorePages() // untuk infinite scroll select2
            ]
        ]);
    }

    public function getModel(Request $request)
    {
        $request->validate([
            'search'    => 'nullable|string|max:255',
            'page'      => 'nullable|integer|min:1',
            'id'        => 'nullable|array|min:1',
            'id.*'      => 'integer',
        ]);

        $search = $request->input('search', '');
        $page = $request->input('page', 1);
        $ids = $request->input('id', []);

        if (!is_array($ids) && $ids !== null) {
            $ids = [$ids];
        }

        // Ambil data dari TargetReinstra
        $query = KomponenModel::when(!empty($ids), function ($q) use ($ids) {
            return $q->whereIn('id', $ids);
        }, function ($q) use ($search) {
            return $q->where('nama', 'like', "%{$search}%");
        });

        // Ambil data sesuai Select2
        $data = $query->select('id', 'nama')->paginate(20, ['*'], 'page', $page);

        return response()->json([
            'results' => $data->items(), // hanya ambil data
            'pagination' => [
                'more' => $data->hasMorePages() // untuk infinite scroll select2
            ]
        ]);
    }
    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'program_id'         => 'required|integer|exists:trprogram,id',
            'user_id'            => 'required|integer|exists:users,id',
            'komponenmodel_id'   => 'required|integer|exists:mkomponenmodel,id',
            'sektor_ids'         => 'required|array',
            'sektor_ids.*'       => 'integer|exists:mtargetreinstra,id',
            'data'               => 'required|array|min:1',
            'data.*.provinsi_id' => 'required|integer|exists:provinsi,id',
            'data.*.kabupaten_id'=> 'required|integer|exists:kabupaten,id',
            'data.*.kecamatan_id'=> 'required|integer|exists:kecamatan,id',
            'data.*.desa_id'     => 'required|integer|exists:kelurahan,id',
            'data.*.dusun_id'    => 'required|integer|exists:dusun,id',
            'data.*.long'        => 'nullable|numeric',
            'data.*.lat'         => 'nullable|numeric',
            'data.*.jumlah'      => 'required|numeric|min:0',
            'data.*.satuan_id'   => 'required|integer|exists:msatuan,id',
        ]);

        DB::beginTransaction();
        try {
            // Hitung total jumlah dari semua baris data
            $totalJumlah = collect($validated['data'])->sum('jumlah');

            // Simpan ke tabel trmeals_komponen_model
            $komodel = Meals_Komponen_Model::create([
                'program_id'        => $validated['program_id'],
                'user_id'           => $validated['user_id'],
                'komponenmodel_id'  => $validated['komponenmodel_id'],
                'totaljumlah'       => $totalJumlah,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // Loop tiap baris data lokasi
            foreach ($validated['data'] as $row) {
                $lokasi = Meals_Komponen_Model_Lokasi::create([
                    'mealskomponenmodel_id' => $komodel->id,
                    'provinsi_id'           => $row['provinsi_id'],
                    'kabupaten_id'          => $row['kabupaten_id'],
                    'kecamatan_id'          => $row['kecamatan_id'],
                    'desa_id'               => $row['desa_id'],
                    'dusun_id'              => $row['dusun_id'],
                    'long'                  => $row['long'],
                    'lat'                   => $row['lat'],
                    'satuan_id'             => $row['satuan_id'],
                    'jumlah'                => $row['jumlah'],
                ]);
            }
            if (!empty($validated['sektor_ids'])) {
                $sektors = collect($validated['sektor_ids'])->map(function ($sektor) use ($komodel) {
                    return [
                        'mealskomponenmodel_id' => $komodel->id,
                        'targetreinstra_id'     => $sektor,
                        'created_at'            => now(),
                        'updated_at'            => now(),
                    ];
                })->toArray();
    
                Meals_Komponen_Moldel_Target_Reinstra::insert($sektors);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.',
                'data'    => $komodel,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $komodel = Meals_Komponen_Model::with([
            'lokasi' => function ($query) {
                $query->select(
                    'id', 'mealskomponenmodel_id',
                    'provinsi_id', 'kabupaten_id', 'kecamatan_id', 'desa_id', 'dusun_id',
                    'long', 'lat', 'jumlah', 'satuan_id'
                );
            },
            'sektors:id,nama' // pakai relasi sektors, bukan targetreinstra
        ])->findOrFail($id);

        $program   = Program::select('id', 'nama')->findOrFail($komodel->program_id);
        $komponen = KomponenModel::select('id', 'nama')->findOrFail($komodel->komponenmodel_id);
        $satuan   = Satuan::select('id', 'nama')->get();

        return view('tr.komponenmodel.edit', [
            'komodel'         => $komodel,
            'program'         => $program,
            'komponen'        => $komponen,
            'satuan'          => $satuan,
            'lokasiData'      => $komodel->lokasi,
            'sektorTerpilih'  => $komodel->sektors->pluck('id')->toArray(), // sesuaikan juga disini
        ]);
    }

    public function storeSingleLokasi(Request $request)
    {
        $validated = $request->validate([
            'mealskomponenmodel_id' => 'required|exists:trmeals_komponen_model,id',
            'provinsi_id' => 'required|exists:provinsi,id',
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'desa_id' => 'required|exists:kelurahan,id',
            'dusun_id' => 'required|exists:dusun,id',
            'long' => 'nullable|string|max:50',
            'lat' => 'nullable|string|max:50',
            'jumlah' => 'required|numeric|min:0',
            'satuan_id' => 'required|exists:msatuan,id',
        ]);

        $lokasi = Meals_Komponen_Model_Lokasi::create($validated);
        $this->recalculateTotalJumlah($lokasi->mealskomponenmodel_id); // <- tambahkan ini untuk menghitung ulang total jumlah

        return response()->json([
            'message' => 'Data lokasi berhasil disimpan.',
            'data' => $lokasi
        ]);
    }

    public function getLokasiById($id)
    {
        $lokasi = Meals_Komponen_Model_Lokasi::with([
            'provinsi:id,nama',
            'kabupaten:id,nama',
            'kecamatan:id,nama',
            'desa:id,nama',
            'dusun:id,nama',
            'satuan:id,nama',
        ])->findOrFail($id);

        return response()->json($lokasi);
    }

    public function updateSingleLokasi(Request $request, $id)
    {
        $lokasi = Meals_Komponen_Model_Lokasi::findOrFail($id);

        $validated = $request->validate([
            'provinsi_id' => 'required|exists:provinsi,id',
            'kabupaten_id' => 'required|exists:kabupaten,id',
            'kecamatan_id' => 'required|exists:kecamatan,id',
            'desa_id' => 'required|exists:kelurahan,id',
            'dusun_id' => 'required|exists:dusun,id',
            'long' => 'nullable|string|max:50',
            'lat' => 'nullable|string|max:50',
            'jumlah' => 'required|numeric|min:0',
            'satuan_id' => 'required|exists:msatuan,id',
        ]);

        $lokasi->update($validated);

        $this->recalculateTotalJumlah($lokasi->mealskomponenmodel_id); // <- tambahkan ini

        return response()->json(['message' => 'Data lokasi berhasil diperbarui.']);
    }

    public function updateModelSektor(Request $request, $id)
    {
        $request->validate([
            'model_id' => 'required|exists:mkomponenmodel,id',
            'sektor_ids' => 'required|array',
            'sektor_ids.*' => 'exists:mtargetreinstra,id',
            'totaljumlah' => 'required|numeric|min:0',
        ]);

        $komodel = Meals_Komponen_Model::findOrFail($id);
        $komodel->komponenmodel_id = $request->model_id;
        $komodel->totaljumlah = $request->totaljumlah; // ← Tambahan penting
        $komodel->user_id = auth()->id(); // pencatat
        $komodel->save();

        // Sinkronisasi relasi sektor
        $komodel->sektors()->sync($request->sektor_ids);

        return response()->json(['message' => 'Data berhasil diperbarui.']);
    }

    public function deleteLokasi($id)
    {
        $lokasi = Meals_Komponen_Model_Lokasi::find($id);

        if (!$lokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi tidak ditemukan.',
            ], 404);
        }

        $komponenModelId = $lokasi->mealskomponenmodel_id;
        $lokasi->delete();

        $this->recalculateTotalJumlah($komponenModelId); // <- tambahkan ini

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil dihapus.',
        ]);
    }

    private function recalculateTotalJumlah($komponenModelId)
    {
        $total = Meals_Komponen_Model_Lokasi::where('mealskomponenmodel_id', $komponenModelId)->sum('jumlah');

        $komodel = Meals_Komponen_Model::find($komponenModelId);
        if ($komodel) {
            $komodel->totaljumlah = $total;
            $komodel->save();
        }
    }


}
