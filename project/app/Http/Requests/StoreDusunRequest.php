<?php

namespace App\Http\Requests;

use App\Rules\MatchDesaID;
use Illuminate\Foundation\Http\FormRequest;

class StoreDusunRequest extends FormRequest
{
    public function authorize(): bool
    {
        // return Gate::allows('dusun_create');
        return true;
    }

    public function rules(): array
    {
        return [
            'desa_id'      => ['required', 'integer'],
            'kode_desa'    => ['required','string', 'size:13'],
            'kode'         => ['required', 'string', new MatchDesaID($this->input('kode_desa')), 'unique:dusun'],
            'nama'         => ['required', 'string', 'max:200', 'min:3'],
            'kode_pos'     => ['string', 'max:5'],
            'aktif'        => ['integer'],
        ];
    }
}
