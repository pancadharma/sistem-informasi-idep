<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProgramRequest extends FormRequest
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
        return Gate::allows('program_edit');
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
            'kode'                                  => ['nullable', 'string', 'max:50'],
            'tanggalmulai'                          => ['nullable', 'date_format:Y-m-d'],
            'tanggalselesai'                        => ['nullable', 'date_format:Y-m-d', 'after_or_equal:tanggalmulai'],
            'totalnilai'                            => ['nullable', 'numeric'],
            'ekspektasipenerimamanfaat'             => ['nullable', 'integer'],
            'ekspektasipenerimamanfaatwoman'        => ['nullable', 'integer'],
            'ekspektasipenerimamanfaatman'          => ['nullable', 'integer'],
            'ekspektasipenerimamanfaatgirl'         => ['nullable', 'integer'],
            'ekspektasipenerimamanfaatboy'          => ['nullable', 'integer'],
            'ekspektasipenerimamanfaattidaklangsung' => ['nullable', 'integer'],
            'deskripsiprojek'                       => ['nullable', 'string', 'max:500'],
            'analisamasalah'                        => ['nullable', 'string', 'max:500'],
            'targetreinstra'                        => ['array'],
            'targetreinstra.*'                      => ['nullable', 'integer', 'exists:mtargetreinstra,id'],
            'kelompokmarjinal'                      => ['array'],
            'kelompokmarjinal.*'                    => ['nullable', 'integer', 'exists:mkelompokmarjinal,id'],
            'kaitansdg'                             => ['array'],
            'kaitansdg.*'                           => ['nullable', 'exists:mkaitansdg,id'],
            'file_pendukung'                        => ['array'],
            'file_pendukung.*'                      => ['nullable', 'file', 'mimes:jpg,png,pdf,docx', 'max:4096'],
            'status'                                => ['string', 'max:50'],
            'keterangan.*'                          => ['nullable', 'string', 'max:255'],
            'pendonor_id'                           => ['array'],
            'pendonor_id.*'                         => ['nullable', 'integer', 'exists:mpendonor,id'],
            'nilaidonasi'                           => ['array'],
            'nilaidonasi.*'                         => ['nullable', 'numeric'],
            'lokasi'                                => ['array'],
            'lokasi.*'                              => ['nullable', 'integer', 'exists:provinsi,id'],

        ];
    }
}
