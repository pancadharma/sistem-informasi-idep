<?php

namespace App\Http\Requests;

use App\Rules\MatchKabupatenID;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreKecamatanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return Gate::allows('kecamatan_create');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $kabupatenID = $this->input('kabupaten_kode');
        $rule = new MatchKabupatenID($kabupatenID);

        return [
            'kabupaten_id'      => ['required', 'integer'],
            'kabupaten_kode'    => ['required','string', 'size:5'],
            // 'kode'              => [new MatchKabupatenID($this->input('kabupaten_id')), 'required', 'string', 'max:8', 'min:8'],
            'kode'              => ['required', 'string', 'size:8', $rule, 'unique:kecamatan'],
            'nama'              => ['required', 'string', 'max:200', 'min:2'],
            'aktif'             => ['integer'],
        ];
    }
}
