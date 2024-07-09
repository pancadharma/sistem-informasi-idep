<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Provinsi;
use Gate;
use Illuminate\Http\Response;

class StoreCountryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return true;
        return Gate::allows('country_create');

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => [
                'string',
                'required',
            ],
            'iso1' => [
                'string',
                'required',
            ],
            'iso2' => [
                'string',
                'required',
            ],
        ];
    }
}
