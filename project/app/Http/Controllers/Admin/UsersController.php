<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\MassDestroyUserRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UsersController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $roles = Role::pluck('nama', 'id');
        return view('master.users.index', compact('roles'));
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 402);
        }
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user->load('roles');
        return response()->json($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));
        if ($request->input('image', false)) {
            if (! $user->image || $request->input('image') !== $user->image->file_name) {
                if ($user->image) {
                    $user->image->delete();
                }
                $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
            }
        } elseif ($user->image) {
            $user->image->delete();
        }

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user->load('roles');
        return view('master.users.show', compact('user'));
    }
    
    public function showModal($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json($user);
    }
    
    

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $user->delete();
        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        $users = User::find(request('ids'));

        foreach ($users as $user) {
            $user->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('user_create') && Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new User();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function api(Request $request) {
        if ($request->ajax()) {
            $query = User::with('roles')->select('users.*');
            $data = DataTables::of($query)
                ->addColumn('roles', function ($user) {
                    return $user->roles->map(function ($role) {
                        return "<span class=\"btn btn-warning btn-xs\">{$role->nama}</span>";
                    })->implode(' ');
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
                ->rawColumns(['roles', 'action', 'status'])
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
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username'
        ]);

        if ($validator->fails()) {
            return response()->json(__('cruds.user.fields.username').' '.$request->username.' '.__('cruds.user.validation.taken'));
        }

        return response()->json("true");
    }
    
    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json(__('cruds.user.fields.email').' '.$request->email.' '.__('cruds.user.validation.taken'));
        }

        return response()->json("true");
    }



}
