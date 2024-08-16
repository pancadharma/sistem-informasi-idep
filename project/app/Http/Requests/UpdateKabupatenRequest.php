<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKabupatenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
        //check permission if users allowed to update
        // return Gate::allows('kabupaten_edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'kode'          => ['string','max:15','required','unique:provinsi,kode,' . request()->route('kabupaten')->id,],
            'nama'          => ['required','string', 'max:255',],
            'provinsi_id'   => ['required','integer',],
            'type'          => ['required','string'],
            'aktif'         => ['integer',],
        ];
    }

    public function messages(): array
    {
        return [
            'aktif.required'       => 'The aktif field is required.',
            'aktif.boolean'        => 'The aktif field must be true or false.',
            'kode.required'        => 'The kode field is required.',
            'kode.string'          => 'The kode field must be a string.',
            'kode.max'             => 'The kode field may not be greater than 5 characters.',
            'nama.required'        => 'The nama field is required.',
            'nama.string'          => 'The nama field must be a string.',
            'nama.max'             => 'The nama field may not be greater than 200 characters.',
            'provinsi_id.required' => 'The Provinsi field is required.',
            'provinsi_id.integer'  => 'The Provinsi field must be an integer.',
        ];
    }
}
