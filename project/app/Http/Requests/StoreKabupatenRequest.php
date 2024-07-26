<?php

namespace App\Http\Requests;

use App\Rules\MatchProvinsiId;
use Illuminate\Foundation\Http\FormRequest;

class StoreKabupatenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //check permission if users allowed to update
        // return Gate::allows('kabupaten_create');
        return true;
    }

    public function rules(): array
    {
        return [
            'provinsi_id' => ['required', 'integer'],
            'kode' => [new MatchProvinsiId($this->input('provinsi_id')), 'required', 'string', 'max:15', 'min:4'],
            'nama' => ['required', 'string', 'max:200'],
            'type' => ['required', 'string'],
            'aktif' => ['integer'],
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'aktif.required'       => 'The aktif field is required.',
    //         'aktif.boolean'        => 'The aktif field must be true or false.',
    //         'kode.required'        => 'The kode field is required.',
    //         'kode.string'          => 'The kode field must be a string.',
    //         'kode.max'             => 'The kode field may not be greater than 5 characters.',
    //         'nama.required'        => 'The nama field is required.',
    //         'nama.string'          => 'The nama field must be a string.',
    //         'nama.max'             => 'The nama field may not be greater than 200 characters.',
    //         'provinsi_id.required' => 'The provinsi_id field is required.',
    //         'provinsi_id.integer'  => 'The provinsi_id field must be an integer.',
    //     ];
    // }
}
