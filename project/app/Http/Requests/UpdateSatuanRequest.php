<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSatuanRequest extends FormRequest
{

    public function authorize(): bool
    {
        if (auth()->user()->id == 1) {
            return true;
        }
        return Gate::allows('satuan_edit');
    }


    public function rules(): array
    {
        return [
            'nama'          => ['string','required','max:200', Rule::unique('msatuan')->ignore($this->route('satuan'))],
            'aktif'         => ['integer'],
        ];
    }
}
