<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
class UpdateUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Gate::allows('user_edit');
    }

    public function rules(): array
    {
        $userId = $this->input('id');

        return [
            'nama'      => ['string','required','max:100'],
            'username'  => ["required", "unique:users,username,{$userId}", "max:50"],
            'email'     => ["required", "unique:users,email,{$userId}", "max:100"],
            'jabatan_id'=> ['required',],
            'password'  => ['nullable','max:100'],
            'roles.*'   => ['integer',],
            'roles'     => ['required','array',],
            'aktif'     => ['integer'],
        ];
    }
}
