<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Exception;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use Illuminate\Http\Request;
use App\Rules\MatchProvinsiId;
use PhpParser\Node\Stmt\TryCatch;
use Flasher\Prime\FlasherInterface;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use App\Http\Requests\StoreKabupatenRequest;
use App\Http\Requests\UpdateKabupatenRequest;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;

class KabupatenController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('kabupaten_acceess'), Response::HTTP_FORBIDDEN, '403 Forbidden'); //Uncomment to apply permission provinsi_access index
        return view("master.kabupaten.index");
    }

    public function figma()
    {
        // abort_if(Gate::denies('kabupaten_acceess'), Response::HTTP_FORBIDDEN, '403 Forbidden'); //Uncomment to apply permission provinsi_access index
        $provinsi = Provinsi::withActive()->get(['id', 'nama']);
        return view("master.kabupaten.figma");
    }

    public function create()
    {
        $provinsi = Provinsi::withActive()->get(['id', 'nama']);
        return response()->json($provinsi);
    }

    public function dataKabupaten(Request $request){
        if ($request->ajax()) {

            $kabupaten = Kabupaten::with('provinsi');
            $data = DataTables::of($kabupaten)
            ->addIndexColumn()
            ->addColumn('action', function ($kabupaten) {
                return '<button type="button" class="btn btn-sm btn-info edit-kab-btn" data-action="edit" data-kabupaten-id="'. $kabupaten->id .'" title="'.__('global.edit') .' '. __('cruds.kabupaten.title') .' '. $kabupaten->nama .'"><i class="fas fa-pencil-alt"></i> Edit</button>

                <button type="button" class="btn btn-sm btn-primary view-kabupaten-btn" data-action="view" data-kabupaten-id="'. $kabupaten->id .'" value="'. $kabupaten->id .'" title="'.__('global.view') .' '. __('cruds.kabupaten.title') .' '. $kabupaten->nama .'"><i class="fas fa-folder-open"></i> View</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
            return $data;
        }
    }
    public function store(StoreKabupatenRequest $request)
    {

        // $request->validate([
        //     'provinsi_id' => ['required', 'integer'],
        //     'kode' => ['required', new MatchProvinsiId($request->provinsi_id)],
        //     'nama' => ['required', 'string', 'max:200'],
        //     'type' => ['required', 'string'],
        //     'aktif' => ['integer'],
        // ]);

        try {
            $data = $request->validated();
            Kabupaten::create($data);

            return response()->json([
                "success"    => true,
                "message"   => __('cruds.data.data') .' '.__('cruds.kabupaten.title') .' '. $request->nama .' '. __('cruds.data.added'),
                "status"    => 201,
                "data"      => $data,
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                "status"    => 400,
                "success"   => false,
                "message"   => $th,
                "data"      => $request->all(),
            ]);
        }catch (ValidationException $e) {
            $status = 'error';
            $message = 'Validation failed: ' . implode(', ', $e->errors());
            return response()->json(['status' => $status, 'message' => $message], 422); // Use 422 Unprocessable Entity for validation errors

        } catch (QueryException $e) {
            $status = 'error';
            if ($e->getCode() === '22003') {
                $message = 'The provided code is too large for the database. Please enter a valid code within the allowed range.';
            } else {
                $message = 'Database error: ' . $e->getMessage();
            }
            return response()->json(['status' => $status, 'message' => $message], 400); // Use 400 Bad Request for database errors

        } catch (Exception $e) {
            $status = 'error';
            $message = 'An unexpected error occurred: ' . $e->getMessage();
            return response()->json(['status' => $status, 'message' => $message], 500); // Use 500 Internal Server Error for general errors
        } catch (Exception $e) {
            $status = 'error';
            $message = 'An unexpected error occurred: ' . $e->getMessage();
            return response()->json(['status' => $status, 'message' => $message], 419); // Use 500 Internal Server Error for general errors
        }
    }


    public function show(Kabupaten $kabupaten)
    {
        $kabupaten->load('provinsi');
        return response()->json($kabupaten); // Return province data as JSON
    }


    public function edit(Kabupaten $kabupaten)
    {
        $provinsi = Provinsi::withActive()->get(['id', 'nama']);
        $kabupaten->load('provinsi');
        return [$kabupaten, "results" => $provinsi];
    }


    public function update(UpdateKabupatenRequest $request, Kabupaten $kabupaten)
    {
        $data = $request->all();
        try {
            $data = $request->validated();
            $kabupaten->update($data);

            return response()->json([
                'status'    => 'success',
                // 'message'   => "Data ". $request->nama ." Updated Successfully",
                "message"   => __('cruds.data.data') .' '.__('cruds.kabupaten.title') .' '. $request->nama .' '. __('cruds.data.updated'),
                'data'      => $kabupaten,
            ],201);

        } catch (Exception $e) {
            $status = 'error';
            $message = $e->getMessage();
            return response()->json([
                'status'    => $status,
                'message'   => $message,
                'data'      => $data,
            ], 400);
        }
    }

    public function destroy(Kabupaten $kabupaten)
    {
        //
    }
}
