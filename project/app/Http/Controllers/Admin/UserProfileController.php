<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Database\QueryException;
use App\Http\Requests\UpdatePasswordRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

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
        $user = auth()->user();
        // if (auth()->check() && auth()->user()->id ?? auth()->user()->username == $identifier)
        if ($user->id != $identifier && $user->username != $identifier) {
            // Redirect to their own profile if attempting to access someone else's data
            return redirect()->route('profile.show', ['username' => $user->username]);
        }
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
            //     $filename = str_replace(' ', '_', $user->username) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            //     $user->clearMediaCollection('userprofile');
            //     $user->addMediaFromRequest('profile_picture')
            //         ->usingFileName($filename)
            //         ->toMediaCollection('userprofile', 'userprofile');
            // }

            // if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            //     $filename = str_replace(' ', '_', $user->username) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            //     $user->clearMediaCollection($user->username);
            //     $user->addMedia($request->file('profile_picture'))
            //         ->usingFileName($filename)
            //         ->toMediaCollection($user->username, 'userprofile');
            // }

            if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
                $filename = str_replace(' ', '_', $user->username) . '.' . $request->file('profile_picture')->getClientOriginalExtension();
                $user->clearMediaCollection($user->username);
                $user->addMediaFromRequest('profile_picture')
                ->usingFileName($filename)
                ->toMediaCollection($user->username, 'userprofile');
            }
            $user->save();

            DB::commit();
            return response()->json([
                'success'   => true,
                'status'    => 'success',
                'message'   => 'Profile updated successfully',
                'data'      => $user,
                'user'      => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'description' => $user->description,
                    'adminlte_image' => $user->adminlte_image(),
                    'full_profile' => $user->full_profile(),
                ]

            ], 200);
        } catch (QueryException $e) {
            // Rollback transaction on database error
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage(),
                'status' => Response::HTTP_UNPROCESSABLE_ENTITY,
            ], 422);
        } catch (Exception $e) {
            // Rollback transaction on general error
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 400); // Bad Request
        }
    }
}
