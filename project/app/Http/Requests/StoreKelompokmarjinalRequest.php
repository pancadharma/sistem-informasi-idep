<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKelompokmarjinalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => ['string','max:200','required',],
            'aktif' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return[
            'nama.string' => 'Kolom nama kelompok marjinal harus berupa text',
            'nama.max' => 'Kolom nama kelompok marjinal maksimal 200 karakter',
            'nama.required' => 'Kolom kelompok marjinal wajib diisi !',
            // 'aktif.integer' => 'Check box wajib dipilih',
        ];
    }
}
