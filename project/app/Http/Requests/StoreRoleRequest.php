<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Gate;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {

        if (auth()->user()->id == 1) {
            return true;
        }
        // Check if the user has the 'role_edit' permission
        return Gate::allows('role_create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama'          => ['string','required','max:100'],
            'permissions.*' => ['required','integer',],
            'permissions'   => ['required','array',],
            'aktif'         => ['integer'],
        ];
    }
}
