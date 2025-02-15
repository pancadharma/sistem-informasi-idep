<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKegiatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        if (auth()->user()->id === 1 || auth()->user()->can('kegiatan_create')) {
            return true;
        }
        return Gate::allows('kegiatan_create');
    }

    public function rules(): array
    {
        return [
            // field for trkegiatan table
            'programoutcomeoutputactivity_id'           => 'required|exists:trprogramoutcomeoutputactivity,id',
            'jeniskegiatan_id'                          => ['required', 'exists:mjeniskegiatan,id'],
            'user_id'                                   => ['required', 'exist:users,id'],
            'mitra_id'                                  => ['array'],
            'mitra_id.*'                                => ['nullable', 'integer', 'exists:mpartner,id'],
            'fasepelaporan'                             => ['required', 'integer'],
            'tanggalmulai'                              => ['nullable', 'date_format:Y-m-d'],
            'tanggalselesai'                            => ['nullable', 'date_format:Y-m-d', 'after_or_equal:tanggalmulai'],
            'status'                                    => ['string', 'max:50'],
            'deskripsilatarbelakang'                    => ['nullable', 'string', 'max:500'],
            'deskripsitujuan'                           => ['nullable', 'string', 'max:500'],
            'deskripsikeluaran'                         => ['nullable', 'string', 'max:500'],
            'deskripsiyangdikaji'                       => ['nullable', 'string', 'max:500'],
            'penerimamanfaatdewasaperempuan'            => ['nullable', 'integer'],
            'penerimamanfaatdewasalakilaki'             => ['nullable', 'integer'],
            'penerimamanfaatdewasatotal'                => ['nullable', 'integer'],
            'penerimamanfaatlansiaperempuan'            => ['nullable', 'integer'],
            'penerimamanfaatlansialakilaki'             => ['nullable', 'integer'],
            'penerimamanfaatlansiatotal'                => ['nullable', 'integer'],
            'penerimamanfaatremajaperempuan'            => ['nullable', 'integer'],
            'penerimamanfaatremajalakilaki'             => ['nullable', 'integer'],
            'penerimamanfaatremajatotal'                => ['nullable', 'integer'],
            'penerimamanfaatanakperempuan'              => ['nullable', 'integer'],
            'penerimamanfaatanaklakilaki'               => ['nullable', 'integer'],
            'penerimamanfaatanaktotal'                  => ['nullable', 'integer'],
            'penerimamanfaatdisabilitasperempuan'       => ['nullable', 'integer'],
            'penerimamanfaatdisabilitaslakilaki'        => ['nullable', 'integer'],
            'penerimamanfaatdisabilitastotal'           => ['nullable', 'integer'],
            'penerimamanfaatnondisabilitasperempuan'    => ['nullable', 'integer'],
            'penerimamanfaatnondisabilitaslakilaki'     => ['nullable', 'integer'],
            'penerimamanfaatnondisabilitastotal'        => ['nullable', 'integer'],
            'penerimamanfaatmarjinalperempuan'          => ['nullable', 'integer'],
            'penerimamanfaatmarjinallakilaki'           => ['nullable', 'integer'],
            'penerimamanfaatmarjinaltotal'              => ['nullable', 'integer'],
            'penerimamanfaatperempuantotal'             => ['nullable', 'integer'],
            'penerimamanfaatlakilakitotal'              => ['nullable', 'integer'],
            'penerimamanfaattotal'                      => ['nullable', 'integer'],

            // validation of trkegiatan_lokasi
            'desa_id'            => ['array'],
            'desa_id.*'          => ['nullable', 'integer', 'exists:kelurahan,id'],
            'kecamatan_id'       => ['array'],
            'kecamatan_id.*'     => ['nullable', 'integer', 'exists:kecamatan,id'],
            'lokasi'             => ['array'],
            'lokasi.*'           => ['nullable', 'string',],
            'lat'                => ['array'],
            'lat.*'              => ['nullable', 'string',],
            'long'               => ['array'],
            'long.*'             => ['nullable', 'string',],

            // validation of trkegiatan_penulis
            'penulis'         => ['array'],
            'penulis.*'       => ['nullable', 'integer', 'exists:mpendulo,id'],
            'peran_id'           => ['array'],
            'peran_id.*'         => ['nullable', 'integer', 'exists:mpendulo,id'],

        ];
    }
}
