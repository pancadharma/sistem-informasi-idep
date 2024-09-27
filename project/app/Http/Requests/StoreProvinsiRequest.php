<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProvinsiRequest extends FormRequest
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
            'kode' => ['string','max:15','required','unique:provinsi',],
            'nama' => ['string','max:200','required',],
            'aktif' => ['integer','accepted'],
        ];
    }
    public function messages(): array
{
    return [
        'kode.required' => 'Kolom kode provinsi wajib diisi!',
        'kode.string' => 'Kolom kode provinsi harus berupa teks.',
        'kode.max' => 'Kolom kode provinsi maksimal 15 karakter.',
        'kode.unique' => 'Kode provinsi sudah ada, gunakan kode yang berbeda.',
        'nama.required' => 'Kolom nama provinsi wajib diisi!',
        'nama.string' => 'Kolom nama provinsi harus berupa teks.',
        'nama.max' => 'Kolom nama provinsi maksimal 200 karakter.',
        'aktif.accepted' => 'Check box wajib dipilih',
        'aktif.integer' => 'Check box wajib dipilih',
    ];
}
}
