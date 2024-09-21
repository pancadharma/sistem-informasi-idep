<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Check if the authenticated user's ID is 1
        if (auth()->user()->id == 1) {
            return true;
        }
        // Check if the user has the 'role_edit' permission
        return \Gate::allows('role_edit');
    }
    public function rules(): array
    {
        return [
            'nama'          => ['string','required','max:100'],
            'permissions.*' => ['integer',],
            'permissions'   => ['required','array',],
            'aktif'         => ['integer'],
        ];
    }
}
