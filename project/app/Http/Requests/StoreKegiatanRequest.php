<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreKegiatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->id === 1 || auth()->user()->can('kegiatan_create') || Gate::allows('kegiatan_create');
    }

    public function rules(): array
    {
        $rules = [
            'programoutcomeoutputactivity_id'           => ['required', 'exists:trprogramoutcomeoutputactivity,id'],
            'jeniskegiatan_id'                          => ['required', 'exists:mjeniskegiatan,id'],
            'user_id'                                   => ['nullable', 'exists:users,id'],
            'fasepelaporan'                             => ['required', 'integer'],
            'tanggalmulai'                              => ['nullable', 'date_format:Y-m-d'],
            'tanggalselesai'                            => ['nullable', 'date_format:Y-m-d', 'after_or_equal:tanggalmulai'],
            'status'                                    => ['string', 'max:50'],
            'deskripsilatarbelakang'                    => ['nullable', 'string'],
            'deskripsitujuan'                           => ['nullable', 'string'],
            'deskripsikeluaran'                         => ['nullable', 'string'],
            'deskripsiyangdikaji'                       => ['nullable', 'string'],
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

            // mitra id
            'mitra_id'          => ['array'],
            'mitra_id.*'        => ['nullable', 'integer', 'exists:mpartner,id'],
            // sektor id
            'sektor_id'         => ['array'],
            'sektor_id.*'       => ['nullable', 'integer', 'exists:mtargetreinstra,id'],

            // 'penulis'           => ['array'],
            // 'penulis.*'         => ['nullable', 'integer', 'exists:users,id'],
            // 'peran_id'          => ['array'],
            // 'peran_id.*'        => ['nullable', 'integer', 'exists:mperan,id'],

            'penulis'           => ['array'],
            'penulis.*'         => ['required', 'integer', 'exists:users,id'], // Changed from nullable to required
            'jabatan'           => ['array'],
            'jabatan.*'         => ['required', 'integer', 'exists:mperan,id'], // Changed from nullable to required

            // lokasi validations
            'desa_id'           => ['array'],
            'desa_id.*'         => ['nullable', 'integer', 'exists:kelurahan,id'],
            'kecamatan_id'      => ['array'],
            'kecamatan_id.*'    => ['nullable', 'integer', 'exists:kecamatan,id'],
            'lokasi'            => ['array'],
            'lokasi.*'          => ['nullable', 'string'],
            'lat'               => ['array'],
            'lat.*'             => ['nullable', 'string'],
            'long'              => ['array'],
            'long.*'            => ['nullable', 'string'],

            // media validations
            'dokumen_pendukung'                         => ['nullable', 'array', 'max:50'],
            'dokumen_pendukung.*'                       => ['file', 'mimes:pdf,doc,docx,xls,xlsx,pptx', 'max:48960'],
            'media_pendukung'                           => ['nullable', 'array', 'max:50'],
            'media_pendukung.*'                         => ['file', 'mimes:jpg,jpeg,png', 'max:48960'],
            'keterangan'                                => ['nullable', 'array'],
            'keterangan.*'                              => ['nullable', 'string'],
        ];

        $jenisKegiatan = (int)$this->input('jeniskegiatan_id');

        $rules = match ($jenisKegiatan) {
            1 => array_merge($rules, [
                'assessmentyangterlibat'        => ['nullable', 'string'],
                'assessmenttemuan'              => ['nullable', 'string'],
                'assessmenttambahan'            => ['nullable', 'string'],
                'assessmenttambahan_ket'        => ['nullable', 'string'],
                'assessmentkendala'             => ['nullable', 'string'],
                'assessmentisu'                 => ['nullable', 'string'],
                'assessmentpembelajaran'        => ['nullable', 'string'],
            ]),
            2 => array_merge($rules, [
                'sosialisasiyangterlibat'       => ['nullable', 'string'],
                'sosialisasitemuan'             => ['nullable', 'string'],
                'sosialisasitambahan'           => ['nullable', 'string'],
                'sosialisasitambahan_ket'       => ['nullable', 'string'],
                'sosialisasikendala'            => ['nullable', 'string'],
                'sosialisasiisu'                => ['nullable', 'string'],
                'sosialisasipembelajaran'       => ['nullable', 'string'],
            ]),
            3 => array_merge($rules, [
                'pelatihanpelatih'              => ['nullable', 'string'],
                'pelatihanhasil'                => ['nullable', 'string'],
                'pelatihandistribusi'           => ['nullable', 'string'],
                'pelatihandistribusi_ket'       => ['nullable', 'string'],
                'pelatihanrencana'              => ['nullable', 'string'],
                'pelatihanunggahan'             => ['nullable', 'string'],
                'pelatihanisu'                  => ['nullable', 'string'],
                'pelatihanpembelajaran'         => ['nullable', 'string'],
            ]),
            4 => array_merge($rules, [
                'pembelanjaandetailbarang'      => ['nullable', 'string'],
                'pembelanjaanmulai'             => ['nullable', 'date'],
                'pembelanjaanselesai'           => ['nullable', 'date'],
                'pembelanjaandistribusimulai'   => ['nullable', 'date'],
                'pembelanjaandistribusiselesai' => ['nullable', 'date'],
                'pembelanjaanterdistribusi'     => ['nullable', 'integer'],
                'pembelanjaanakandistribusi'    => ['nullable', 'integer'],
                'pembelanjaanakandistribusi_ket' => ['nullable', 'string'],
                'pembelanjaankendala'           => ['nullable', 'string'],
                'pembelanjaanisu'               => ['nullable', 'string'],
                'pembelanjaanpembelajaran'      => ['nullable', 'string'],
            ]),
            5 => array_merge($rules, [
                'pengembanganjeniskomponen'     => ['nullable', 'string'],
                'pengembanganberapakomponen'    => ['nullable', 'integer'],
                'pengembanganlokasikomponen'    => ['nullable', 'string'],
                'pengembanganyangterlibat'      => ['nullable', 'string'],
                'pengembanganrencana'           => ['nullable', 'string'],
                'pengembangankendala'           => ['nullable', 'string'],
                'pengembanganisu'               => ['nullable', 'string'],
                'pengembanganpembelajaran'      => ['nullable', 'string'],
            ]),
            6 => array_merge($rules, [
                'kampanyeyangdikampanyekan'     => ['nullable', 'string'],
                'kampanyejenis'                 => ['nullable', 'string'],
                'kampanyebentukkegiatan'        => ['nullable', 'string'],
                'kampanyeyangterlibat'          => ['nullable', 'string'],
                'kampanyeyangdisasar'           => ['nullable', 'string'],
                'kampanyejangkauan'             => ['nullable', 'string'],
                'kampanyerencana'               => ['nullable', 'string'],
                'kampanyekendala'               => ['nullable', 'string'],
                'kampanyeisu'                   => ['nullable', 'string'],
                'kampanyepembelajaran'          => ['nullable', 'string'],
            ]),
            7 => array_merge($rules, [
                'pemetaanyangdihasilkan'        => ['nullable', 'string'],
                'pemetaanluasan'                => ['nullable', 'string'],
                'pemetaanunit'                  => ['nullable', 'string'],
                'pemetaanyangterlibat'          => ['nullable', 'string'],
                'pemetaanrencana'               => ['nullable', 'string'],
                'pemetaanisu'                   => ['nullable', 'string'],
                'pemetaanpembelajaran'          => ['nullable', 'string', 'max:4294967295'],
            ]),
            8 => array_merge($rules, [
                'monitoringyangdipantau'        => ['nullable', 'string'],
                'monitoringdata'                => ['nullable', 'string'],
                'monitoringyangterlibat'        => ['nullable', 'string'],
                'monitoringmetode'              => ['nullable', 'string'],
                'monitoringhasil'               => ['nullable', 'string'],
                'monitoringkegiatanselanjutnya' => ['nullable', 'string'],
                'monitoringkegiatanselanjutnya_ket' => ['nullable', 'string'],
                'monitoringkendala'             => ['nullable', 'string'],
                'monitoringisu'                 => ['nullable', 'string'],
                'monitoringpembelajaran'        => ['nullable', 'string'],
            ]),
            9 => array_merge($rules, [
                'kunjunganlembaga'              => ['nullable', 'string'],
                'kunjunganpeserta'              => ['nullable', 'string'],
                'kunjunganyangdilakukan'        => ['nullable', 'string'],
                'kunjunganhasil'                => ['nullable', 'string'],
                'kunjunganpotensipendapatan'    => ['nullable', 'string'],
                'kunjunganrencana'              => ['nullable', 'string'],
                'kunjungankendala'              => ['nullable', 'string'],
                'kunjunganisu'                  => ['nullable', 'string'],
                'kunjunganpembelajaran'         => ['nullable', 'string'],
            ]),
            10 => array_merge($rules, [
                'konsultasilembaga'             => ['nullable', 'string'],
                'konsultasikomponen'            => ['nullable', 'string'],
                'konsultasiyangdilakukan'       => ['nullable', 'string'],
                'konsultasihasil'               => ['nullable', 'string'],
                'konsultasipotensipendapatan'   => ['nullable', 'string'],
                'konsultasirencana'             => ['nullable', 'string'],
                'konsultasikendala'             => ['nullable', 'string'],
                'konsultasiisu'                 => ['nullable', 'string'],
                'konsultasipembelajaran'        => ['nullable', 'string'],
            ]),
            11 => array_merge($rules, [
                'lainnyamengapadilakukan'       => ['nullable', 'string'],
                'lainnyadampak'                 => ['nullable', 'string'],
                'lainnyasumberpendanaan_ket'    => ['nullable', 'string'],
                'lainnyayangterlibat'           => ['nullable', 'string'],
                'lainnyarencana'                => ['nullable', 'string'],
                'lainnyakendala'                => ['nullable', 'string'],
                'lainnyaisu'                    => ['nullable', 'string'],
                'lainnyapembelajaran'           => ['nullable', 'string'],
            ]),
            default => $rules,
        };

        return $rules;
    }
}
