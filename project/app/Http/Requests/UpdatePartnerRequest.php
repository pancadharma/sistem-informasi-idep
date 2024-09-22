<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
                // Check if the authenticated user's ID is 1
        if (auth()->user()->id == 1) {
            return true;
        }
        // Check if the user has the 'role_edit' permission
        return Gate::allows(ability: 'partner_edit');
    }

    public function rules(): array
    {
        return [
            'nama'          => ['required','string','max:200', Rule::unique('mpartner')->ignore($this->route('partner'))],
            'keterangan'    => ['string','max:200'],
            'aktif'         => ['integer'],
            // 'no_telp'       => ['required','string','max:15'],
            // 'email'         => ['required','email','max:255'],
            // 'website'       => ['nullable','string','max:255'],
        ];
    }
}
