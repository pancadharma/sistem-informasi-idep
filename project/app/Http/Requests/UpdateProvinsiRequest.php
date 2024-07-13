<?php

namespace App\Http\Requests;

use App\Models\Provinsi;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
class UpdateProvinsiRequest extends FormRequest
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
            'kode' => ['string','max:15','required','unique:provinsi,kode,' . request()->route('provinsi')->id,],
            'nama' => ['string','max:200','required',],
            'aktif' => ['integer'],
        ];
    }
}
