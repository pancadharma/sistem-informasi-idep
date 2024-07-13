<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Resources\ProvinsiResource;
use App\Http\Requests\StoreProvinsiRequest;
use App\Http\Requests\UpdateProvinsiRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;





class ProvinsiController extends Controller
{

    public function index()
    {
        // abort_if(Gate::denies('provinsi_access'), Response::HTTP_FORBIDDEN, '403 Forbidden'); //Uncomment to apply permission provinsi_access index
        return view('master.provinsi.index');
    }

    public function dataprovinsi(){
        $activeProvinsi = Provinsi::get();
        // Prepare data for DataTables (without modifying original collection)
        $data = DataTables::of($activeProvinsi)
            ->addColumn('action', function ($provinsi) {
                $editUrl = route('provinsi.edit', $provinsi->id);
                $viewUrl = route('provinsi.show', $provinsi->id);
                // return '<a href="'.$editUrl.'" class="btn btn-sm btn-info" title="'.__('global.edit') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-pencil-alt"></i></a> <a href="'.$viewUrl.'" class="btn btn-sm btn-primary" title="'.__('global.view') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-folder-open"></i></a>';
                //<button type="button" class="btn btn-sm btn-info edit-province-btn" data-province-id="{{ $province->id }}" title="'.__('global.edit') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-pencil-alt"></i></a>Edit</button>

                return '<button type="button" class="btn btn-sm btn-info edit-province-btn" data-action="edit" data-provinsi-id="'. $provinsi->id .'" title="'.__('global.edit') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-pencil-alt"></i> Edit</button>
                <button type="button" class="btn btn-sm btn-primary view-province-btn" data-action="view" data-province-id="'. $provinsi->id .'" value="'. $provinsi->id .'" title="'.__('global.view') .' '. __('cruds.provinsi.title') .' '. $provinsi->nama .'"><i class="fas fa-folder-open"></i> View</button>';
            })
            ->make(true);
        return $data;
    }

    public function create()
    {
        //
    }

    public function store(StoreProvinsiRequest $request)
    {
        try {
            $data = Provinsi::create($request->all());

            $status = 'success';
            $message = 'Data ' . $request->nama . ' submitted successfully!';

            return response()->json(['status' => $status, 'message' => $message], 201); // Use 201 Created for successful creation

        } catch (\Illuminate\Database\QueryException $e) {
            $status = 'error';

            // Handle specific MySQL error for numeric value out of range
            if ($e->getCode() === 22003) {
                $message = 'The provided code is too large for the database. Please enter a valid code within the allowed range.';
            } else {
                // Handle other database errors more generically
                $message = 'There was an error processing your data: ' . $e->getMessage();
            }

            return response()->json(['status' => $status, 'message' => $message], 400); // Use 400 Bad Request for validation errors
        }
    }

    public function show(Provinsi $provinsi)
    {
        return response()->json($provinsi); // Return province data as JSON
     
    }

    public function edit(Provinsi $provinsi)
    {
        // return response()->json($provinsi); // Return province data as JSON
    }
    public function get_edit(Provinsi $provinsi)
    {
        return response()->json($provinsi); // Return province data as JSON
    }

    public function update(UpdateProvinsiRequest $request, Provinsi $provinsi)
    {
        try {
            // Validate the request data
            $data = $request->validated();
            // Update the provinsi model with the validated data
            $provinsi->update($data);
            $status = "success";
            $message = "Data " . $request->nama . " was updated successfully!";
            return response()->json(['status' => $status, 'message' => $message, 'data' =>$data], 200); // Use 200 OK for successful updates

        } catch (ValidationException $e) {
            $status = 'error';
            $message = 'Validation failed: ' . implode(', ', $e->errors());
            return response()->json(['status' => $status, 'message' => $message], 422); // Use 422 Unprocessable Entity for validation errors

        } catch (QueryException $e) {
            // Handle specific database errors
            $status = 'error';
            if ($e->getCode() === '22003') {
                // MySQL numeric value out of range
                $message = 'The provided code is too large for the database. Please enter a valid code within the allowed range.';
            } else {
                // Other database errors
                $message = 'Database error: ' . $e->getMessage();
            }
            return response()->json(['status' => $status, 'message' => $message], 400); // Use 400 Bad Request for database errors

        } catch (Exception $e) {
            // Handle any other exceptions
            $status = 'error';
            $message = 'An unexpected error occurred: ' . $e->getMessage();
            return response()->json(['status' => $status, 'message' => $message], 500); // Use 500 Internal Server Error for general errors
        }
    }

    public function destroy(Provinsi $provinsi)
    {
        //
    }
}
