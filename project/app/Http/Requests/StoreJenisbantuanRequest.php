<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJenisbantuanRequest extends FormRequest
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
            'nama.string' => 'Kolom nama jenis bantuan harus berupa text',
            'nama.max' => 'Kolom nama jenis bantuan maksimal 200 karakter',
            'nama.required' => 'Kolom nama jenis bantuan wajib diisi !',
            // 'aktif.integer' => 'Check box wajib dipilih',
        ];
    }
}
