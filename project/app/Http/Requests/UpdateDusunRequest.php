<?php

namespace App\Http\Requests;

use App\Rules\MatchDesaID;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDusunRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return Gate::allows('dusun_update');
        return true;
    }

    public function rules(): array
    {
        return [
            'desa_id'      => ['required', 'integer'],
            'kode_desa'    => ['required','string', 'size:13'],
            'kode'         => ['required', 'string','size:16', new MatchDesaID($this->input('kode_desa')), 
                              Rule::unique('dusun')->ignore($this->route('dusun'))],
            'nama'         => ['required', 'string', 'max:200', 'min:3'],
            'kode_pos'     => ['nullable', 'size:5'],
            'aktif'        => ['integer'],
        ];
    }
}