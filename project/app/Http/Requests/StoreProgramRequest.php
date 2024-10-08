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
            'nama'                                  => ['string', 'max:200'],
            'user_id'                               => ['exists:users,id'],
            'kode'                                  => ['string', 'max:50'],
            'tanggalmulai'                          => ['date_format:Y-m-d'],
            'tanggalselesai'                        => ['date_format:Y-m-d', 'after_or_equal:tanggalmulai'],
            'totalnilai'                            => ['numeric'],
            'ekspektasipenerimamanfaat'             => ['integer'],
            'ekspektasipenerimamanfaatwoman'        => ['integer'],
            'ekspektasipenerimamanfaatman'          => ['integer'],
            'ekspektasipenerimamanfaatgirl'         => ['integer'],
            'ekspektasipenerimamanfaatboy'          => ['integer'],
            'ekspektasipenerimamanfaattidaklangsung'=> ['string', 'max:100'],
            'deskripsiprojek'                       => ['string', 'max:500'],
            'analisamasalah'                        => ['string', 'max:500'],
            'targetreinstra'                        => ['array'],
            'targetreinstra.*'                      => ['integer'],
            'kelompokmarjinal'                      => ['array'],
            'kelompokmarjinal.*'                    => ['integer'],
            'kaitansdg'                             => ['array'],
            'kaitansdg.*'                           => ['exists:mkaitansdg,id'],
            'file_pendukung'                        => ['array'],
            'file_pendukung.*'                      => ['file', 'mimes:jpg,png,pdf,docx', 'max:2048'],
            'status'                                => ['string', 'max:50'],
        ];
    }
}
