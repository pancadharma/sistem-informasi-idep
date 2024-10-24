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
    //         'kode.required'        => 'The kode field is required.',
    //         'kode.string'          => 'The kode field can be a string/integer combine.',
    //         'kode.max'             => 'The kode field may not be greater than 15 characters.',
    //         'nama.required'        => 'The nama field is required.',
    //         'nama.string'          => 'The nama field must be a string.',
    //         'nama.max'             => 'The nama field may not be greater than 200 characters.',
    //         'provinsi_id.required' => 'Silahkan Pilih Provinsi.',
    //         'provinsi_id.integer'  => 'The Province field must be selected.',
    //     ];
    // }

    public function messages()
    {
        return [
            'kode.required' => trans('request.validation.kode_required'),
            'kode.string' => trans('request.validation.kode_string'),
            'kode.max' => trans('request.validation.kode_max'),
            'nama.required' => trans('request.validation.nama_required'),
            'nama.string' => trans('request.validation.nama_string'),
            'nama.max' => trans('request.validation.nama_max'),
            'provinsi_id.required' => trans('request.validation.provinsi_id_required'),
            'provinsi_id.integer' => trans('request.validation.provinsi_id_integer'),
        ];
    }
}
