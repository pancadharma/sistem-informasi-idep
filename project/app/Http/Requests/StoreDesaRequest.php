<?php

namespace App\Http\Requests;

use App\Rules\MatchKecamatanID;
use Illuminate\Foundation\Http\FormRequest;

class StoreDesaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return Gate::allows('desa_create');
        return true;
    }

    protected function prepareForValidation()
    {
        $kecamatanID = $this->input('kecamatan_kode');
        $desaID = str_replace('.', '', $kecamatanID);
        
        $this->merge([
            'id'    => $desaID,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $kecamatanID = $this->input('kecamatan_kode');
        $rule        = new MatchKecamatanID($kecamatanID);

        return [
            'kecamatan_id'      => ['required', 'integer'],
            'kecamatan_kode'    => ['required','string', 'size:8'],
            'kode'              => ['required', 'string', 'size:13', $rule, 'unique:kelurahan'], //use kelurahan instead of desa hehe
            'nama'              => ['required', 'string', 'max:200', 'min:3'],
            'aktif'             => ['integer'],
        ];
    }
}
