<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreProgramRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Pastikan ini sesuai dengan kebutuhan otorisasi Anda
        if (auth()->user()->id == 1) {
            return true;
        }
        // Check if the user has the 'role_edit' permission
        return Gate::allows('program_create');

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama'                                  => ['required|string|max:200',],
            'user_id'                               => ['required|exists:users,id',],
            'kode'                                  => ['required|string|max:50',],
            'tanggalmulai'                          => ['required|date_format:Y-m-d H:i:s',],
            'tanggalselesai'                        => ['required|date_format:Y-m-d H:i:s|after_or_equal:tanggalmulai',],
            'totalnilai'                            => ['required|numeric',],
            'ekspektasipenerimamanfaat'             => ['required|integer',],
            'ekspektasipenerimamanfaatwoman'        => ['required|integer',],
            'ekspektasipenerimamanfaatman'          => ['required|integer',],
            'ekspektasipenerimamanfaatgirl'         => ['required|integer',],
            'ekspektasipenerimamanfaatboy'          => ['required|integer',],
            'ekspektasipenerimamanfaattidaklangsung'=> ['nullable|string|max:100',],
            'deskripsiprojek'                       => ['nullable|string|max:500',],
            'analisamasalah'                        => ['nullable|string|max:500',],
            'targetreinstra.*'                      => ['integer',],
            'targetreinstra'                        => ['array',],
            'kelompokmarjinal.*'                    => ['integer',],
            'kelompokmarjinal'                      => ['required','array',],
            'kaitansdg.*'                           => ['exists:mkaitansdg,id',],
            'kaitansdg'                             => ['array',],
            'files.*'                               => ['file|mimes:jpg,png,pdf,docx|max:2048',],
            'status'                                => ['string|max:50',],
        ];
    }
}
