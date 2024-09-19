<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJenisbantuanRequest extends FormRequest
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
            //'id' => ['string','max:15','required','unique:jenisbantuan,id,' . request()->route('jenisbantuan')->id,],
            'nama' => ['string','max:200','required',],
            'aktif' => ['integer'],
        ];
    }
}
