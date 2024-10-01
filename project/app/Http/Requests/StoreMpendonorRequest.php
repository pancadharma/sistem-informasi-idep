<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMpendonorRequest extends FormRequest
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
            'mpendonorkategori_id' => ['required'],
            // 'kategoripendonor_id' => ['required', 'exists:mpendonorkategori,id'],
            'nama' => ['string', 'max:200', 'required'],
            'pic' => ['string', 'max:200', 'required'],
            'email' => ['required', 'email', 'max:200'],
            'phone' => ['required', 'regex:/^\+?[0-9]{10,20}$/'],
            'aktif' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'mpendonorkategori_id.required' => 'Kolom kategori pendonor wajib dipilih!',
            'nama.string' => 'Kolom nama pendonor harus berupa teks.',
            'nama.max' => 'Kolom nama pendonor maksimal 200 karakter.',
            'nama.required' => 'Kolom nama pendonor wajib diisi!',
            'pic.string' => 'Kolom PIC harus berupa teks.',
            'pic.max' => 'Kolom PIC maksimal 200 karakter.',
            'pic.required' => 'Kolom PIC wajib diisi!',
            'email.required' => 'Kolom email wajib diisi!',
            'email.email' => 'Kolom email harus berupa format email yang valid.',
            'email.max' => 'Kolom email maksimal 200 karakter.',
            'phone.required' => 'Kolom telepon wajib diisi!',
            'phone.regex' => 'Kolom telepon harus berupa angka dengan panjang antara 10 hingga 20 karakter.',
            'aktif.accepted' => 'Check box wajib dipilih.',
        ];
    }
}
