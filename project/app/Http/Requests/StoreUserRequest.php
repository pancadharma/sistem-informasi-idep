<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Gate;

class StoreUserRequest extends FormRequest
{

    public function authorize()
    {
        return Gate::allows('user_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama'      => ['string','required','max:100'],
            'username'  => ['required','unique:users','max:50'],
            'email'     => ['required','unique:users','max:100'],
            'password'  => ['required','max:100'],
            'roles.*'   => ['integer',],
            'roles'     => ['required','array',],
            'aktif'     => ['integer'],
        ];
    }
}
