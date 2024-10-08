<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKategoripendonorRequest extends FormRequest
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
            'nama.string' => 'Kolom nama kategori pendonor harus berupa text',
            'nama.max' => 'Kolom nama kategori pendonor maksimal 200 karakter',
            'nama.required' => 'Kolom kategori pendonor wajib diisi !',
            'aktif.boolean' => 'Check box wajib dipilih'
        ];
    }
}
