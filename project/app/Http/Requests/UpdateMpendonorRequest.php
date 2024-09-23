<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMpendonorRequest extends FormRequest
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
            'aktif' => ['integer'],
        ];
    }
}
