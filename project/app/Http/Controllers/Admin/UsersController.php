<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Database\QueryException;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\MassDestroyUserRequest;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Models\Mjabatan;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UsersController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::pluck('nama', 'id');
        // $jabatans = Mjabatan::pluck('nama', 'id');
        $jabatans = Mjabatan::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');
        return view('master.users.index', compact('jabatans', 'roles'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::pluck('nama', 'id');
        return view('master.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $data = $request->validated();
            $user = User::create($data);
            $user->roles()->sync($request->input('roles', []));

            return response()->json([
                'success'   => true,
                'data'      => $data,
                "message"   => __('cruds.data.data') .' '.__('cruds.user.title') .' '. $request->nama .' '. __('cruds.data.updated'),
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'status'    => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 402);
        }
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $jabatans = Mjabatan::pluck('nama', 'id');
        $user->load('roles','jabatans');
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            // Get the validated data
            $data = $request->validated();

            // Update user data excluding roles and password
            $user->fill([
                'nama'      => $data['nama'],
                'username'  => $data['username'],
                'email'     => $data['email'],
                'jabatan_id'=> $data['jabatan_id'],
                'aktif'     => $data['aktif'],
            ]);

            // Handle password if provided (nullable in validation)
            if ($request->filled('password')) {
                $user->password = bcrypt($data['password']);
            }

            // Save the updated user details
            $user->save();

            // Sync roles only if they are provided in the request
            if (isset($data['roles'])) {
                $user->roles()->sync($data['roles']);
            }

            // Return JSON success response
            return response()->json([
                'success'   => true,
                'message'   =>  __('cruds.data.data') .' '.__('cruds.user.title') .' '. $request->nama .' '. __('cruds.data.updated'),
                'status'    => Response::HTTP_CREATED,
                'data'      => $user
            ], 200);

        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $e->errors(),
            ], 422);

        } catch (ModelNotFoundException $e) {
            // Handle model not found errors
            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
            ], 404);

        } catch (HttpException $e) {
            // Handle HTTP-specific exceptions
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getStatusCode());

        } catch (Exception $e) {
            // Handle all other exceptions
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user->load('roles', 'jabatans');
        return view('master.users.show', compact('user'));
    }

    public function showModal($id)
    {
        $user = User::with('roles', 'jabatans')->findOrFail($id);
        return response()->json($user);
    }



    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // $user->delete();
        // return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        // $users = User::find(request('ids'));

        // foreach ($users as $user) {
        //     $user->delete();
        // }

        // return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('user_create') && Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $model         = new User();
        // $model->id     = $request->input('crud_id', 0);
        // $model->exists = true;
        // $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        // return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function api(Request $request) {
        if ($request->ajax()) {
            $query = User::with('roles', "jabatans")->select('users.*');
            $data = DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('roles', function ($user) {
                    return $user->roles->map(function ($role) {
                        return "<span class=\"btn btn-warning btn-xs\">{$role->nama}</span>";
                    })->implode(' ');
                })
                ->addColumn('jabatans', function ($user) {
                    $jabatan = $user->jabatans;
                    if ($jabatan) {
                        return "<span class=\"btn btn-info btn-xs\">{$jabatan->nama}</span>";
                    }
                    return '';
                })
                ->addColumn('status', function($user){
                    return match ($user->aktif) {
                        1 => '<div class="icheck-primary d-inline">
                                <input id="aktif_' . $user->id . '" data-aktif-id="' . $user->id . '" class="icheck-primary" alt="☑️ aktif" title="' . __("cruds.status.aktif") . '" type="checkbox" checked>
                                <label for="aktif_' . $user->id . '"></label>
                              </div>',
                        0 => '<div class="icheck-primary d-inline">
                                <input id="aktif_' . $user->id . '" data-aktif-id="' . $user->id . '" class="icheck-primary" alt="aktif" title="' . __("cruds.status.aktif") . '" type="checkbox">
                                <label for="aktif_' . $user->id . '"></label>
                              </div>',
                    };
                })
                ->addColumn('action', function ($user) {
                    return '<button type="button" class="btn btn-sm btn-info edit-user-btn" data-action="edit"
                            data-user-id="'. $user->id .'" title="'.__('global.edit') .' '. __('cruds.user.title') .' '. $user->nama .'">
                            <i class="fas fa-pencil-alt"></i> Edit</button>
                            <button type="button" class="btn btn-sm btn-primary view-user-btn" data-action="view"
                            data-user-id="'. $user->id .'" value="'. $user->id .'" title="'.__('global.view') .' '. __('cruds.user.title') .' '. $user->nama .'">
                            <i class="fas fa-folder-open"></i> View</button>';
                })
                ->rawColumns(['roles', 'action', 'status', 'jabatans'])
                ->make(true);

            return $data;
        }
    }

    //get data to datatable
    public function getUsers(Request $request) {
        if ($request->ajax()) {
            $query = User::with('roles');
            $data = DataTables::of($query)
                ->addColumn('roles', function ($user) {
                    return $user->roles->map(function($role) {
                        return "<span class=\"btn btn-warning btn-xs\">{$role->name}</span>";
                    })->join(' - ');
                })
                ->addColumn('action', function ($user) {
                    return '<button type="button" class="btn btn-sm btn-info edit-user-btn" data-action="edit"
                    data-user-id="'. $user->id .'" title="'.__('global.edit') .' '. __('cruds.user.title') .' '. $user->nama .'">
                    <i class="fas fa-pencil-alt"></i> Edit</button>
                    <button type="button" class="btn btn-sm btn-primary view-user-btn" data-action="view"
                    data-user-id="'. $user->id .'" value="'. $user->id .'" title="'.__('global.view') .' '. __('cruds.user.title') .' '. $user->nama .'">
                    <i class="fas fa-folder-open"></i> View</button>';
                })
                ->rawColumns(['roles', 'action'])
                ->make(true);

            return $data;
        }
    }

    //check username
    public function checkUsername(Request $request)
    {
        // \Log::info("check username", $request->all()); // Log the request data for debugging
        $rules = [
            'username' => 'required|unique:users,username'
        ];
        if ($request->has('id')) {
            $userId = $request->input('id');
            $rules['username'] = "required|unique:users,username,{$userId},id";
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(__('cruds.user.fields.username').' '.$request->username.' '.__('cruds.user.validation.taken'));
        }
        return response()->json("true");
    }

    public function checkEmail(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:users,email'
        ];
        if ($request->has('id')) {
            $userId = $request->input('id');
            $rules['email'] = "required|email|unique:users,email,{$userId},id";
        }
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(__('cruds.user.fields.email').' '.$request->email.' '.__('cruds.user.validation.taken'));
        }
        return response()->json("true");
    }

    public function password(UpdatePasswordRequest $request)
    {
        auth()->user()->update($request->validated());
        return response()->json([
            'success'   => true,
            'status'    => 'success',
            'message'   => __('global.change_password_success'),
        ], 201);
    }
}
