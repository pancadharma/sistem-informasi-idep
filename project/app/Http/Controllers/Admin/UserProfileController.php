<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Exception;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Database\QueryException;
use App\Http\Requests\UpdatePasswordRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserProfileController extends Controller
{
    public function showProfile($identifier)
    {
        $user = User::where('username', $identifier)->first();
        if (!$user) {
            $user = User::findOrFail($identifier);
        }

        return view('master.users.profile.update', compact('user'));
    }
    public function show($identifier)
    {
        $user = User::where('username', $identifier)->first();
        if (!$user) {
            $user = User::findOrFail($identifier);
        }

        return view('master.users.profile.update', compact('user'));
    }

    public function update(UpdateProfileRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = auth()->user();

            $user->nama = $request->input('nama');
            $user->email = $request->input('email');
            $user->description = $request->input('description');

            // if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            //     $filename = str_replace(' ', '_', $user->username);
            //     $user->clearMediaCollection('userprofile');
            //     $user->addMediaFromRequest('profile_picture')
            //         ->usingFileName($filename)
            //         ->toMediaCollection('userprofile', 'userprofile');
            // }

            if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
                $filename = str_replace(' ', '_', $user->username) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                $user->clearMediaCollection('userprofile');
                $user->addMediaFromRequest('profile_picture')
                    ->usingFileName($filename)
                    ->toMediaCollection('userprofile', 'userprofile');
            }
            $user->save();

            DB::commit();
            return response()->json([
                'success'   => true,
                'status'    => 'success',
                'message'   => 'Profile updated successfully',
                'data'      => $user,
                'user'      => [
                    'adminlte_image' => $user->adminlte_image(),
                ]

            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            // Rollback transaction on database error
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], 422);
        } catch (\Exception $e) {
            // Rollback transaction on general error
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 400); // Bad Request
        }
    }

    public function password(UpdatePasswordRequest $request)
    {
        auth()->user()->update($request->validated());

        return redirect()->route('frontend.profile.index')->with('message', __('global.change_password_success'));
    }
}
