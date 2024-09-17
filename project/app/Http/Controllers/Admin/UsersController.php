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
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UsersController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // if ($request->ajax()) {
        //     $query = User::with(['roles'])->select(sprintf('%s.*', (new User)->table));
        //     $table = Datatables::of($query);

        //     $table->addColumn('placeholder', '&nbsp;');
        //     $table->addColumn('actions', '&nbsp;');

        //     $table->editColumn('actions', function ($row) {
        //         $viewGate      = 'user_show';
        //         $editGate      = 'user_edit';
        //         $deleteGate    = 'user_delete';
        //         $crudRoutePart = 'users';

        //         return view('partials.datatablesActions', compact(
        //             'viewGate',
        //             'editGate',
        //             'deleteGate',
        //             'crudRoutePart',
        //             'row'
        //         ));
        //     });

        //     $table->editColumn('name', function ($row) {
        //         return $row->name ? $row->name : '';
        //     });
        //     $table->editColumn('email', function ($row) {
        //         return $row->email ? $row->email : '';
        //     });

        //     $table->editColumn('roles', function ($row) {
        //         $labels = [];
        //         foreach ($row->roles as $role) {
        //             $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $role->title);
        //         }

        //         return implode(' ', $labels);
        //     });
        //     $table->editColumn('image', function ($row) {
        //         if ($photo = $row->image) {
        //             return sprintf(
        //                 '<a href="%s" target="_blank"><img src="%s" width="50px" height="50px"></a>',
        //                 $photo->url,
        //                 $photo->thumbnail
        //             );
        //         }

        //         return '';
        //     });
        //     $table->editColumn('aktif', function ($row) {
        //         return '<input type="checkbox" disabled ' . ($row->aktif ? 'checked' : null) . '>';
        //     });

        //     $table->rawColumns(['actions', 'placeholder', 'roles', 'image', 'aktif']);

        //     return $table->make(true);
        // }

        return view('master.users.index');
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        return view('master.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));
        if ($request->input('image', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('image'))))->toMediaCollection('image');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $user->id]);
        }

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::pluck('title', 'id');

        $user->load('roles');

        return view('master.users.edit', compact('roles', 'user'));
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

    public function getUsers(Request $request){
        if ($request->ajax()) {
            $query = User::select('users.id', 'users.nama', 'users.username', 'users.description', 'users.email', 'users.aktif')->with('roles:id,nama');
            // $query = User::with('roles');
            $data = DataTables::of($query)
            ->addColumn('action', function ($user) {
                return '<button type="button" class="btn btn-sm btn-info edit-user-btn" data-action="edit"
                data-user-id="'. $user->id .'" title="'.__('global.edit') .' '. __('cruds.user.title') .' '. $user->nama .'">
                <i class="fas fa-pencil-alt"></i> Edit</button>
                <button type="button" class="btn btn-sm btn-primary view-user-btn" data-action="view"
                data-user-id="'. $user->id .'" value="'. $user->id .'" title="'.__('global.view') .' '. __('cruds.user.title') .' '. $user->nama .'">
                <i class="fas fa-folder-open"></i> View</button>';
            })
            ->make(true);
            return $data;
        }
    }

    public function api(Request $request) {
        if ($request->ajax()) {
            $query = User::with('roles');
            $data = DataTables::of($query)
                ->addColumn('roles', function ($user) {
                    return $user->roles->map(function($role) {
                        return '<span class="btn btn-warning btn-xs">' . $role->nama . '</span>';
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
    
}
