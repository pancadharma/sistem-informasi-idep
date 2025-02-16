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
        $rules = [
            'programoutcomeoutputactivity_id'           => ['required', 'exists:trprogramoutcomeoutputactivity,id'],
            'jeniskegiatan_id'                          => ['required', 'exists:mjeniskegiatan,id'],
            'user_id'                                   => ['required', 'exist:users,id'],
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

            'penulis'           => ['array'],
            'penulis.*'         => ['nullable', 'integer', 'exists:users,id'],
            'peran_id'          => ['array'],
            'peran_id.*'        => ['nullable', 'integer', 'exists:mperan,id'],

            // lokasi validations
            'desa_id'           => ['array'],
            'desa_id.*'         => ['nullable', 'integer', 'exists:kelurahan,id'],
            'kecamatan_id'      => ['array'],
            'kecamatan_id.*'    => ['nullable', 'integer', 'exists:kecamatan,id'],
            'lokasi'            => ['array'],
            'lokasi.*'          => ['nullable', 'string',],
            'lat'               => ['array'],
            'lat.*'             => ['nullable', 'string',],
            'long'              => ['array'],
            'long.*'            => ['nullable', 'string',],
        ];

        $jenisKegiatan = (int)$this->input('jeniskegiatan_id');

        switch ($jenisKegiatan) {
            case 1: // Assessment
                $rules = array_merge($rules, [
                    'assessmentyangterlibat'        => ['nullable', 'string'],
                    'assessmenttemuan'              => ['nullable', 'string'],
                    'assessmenttambahan'            => ['nullable', 'string'],
                    'assessmenttambahan_ket'        => ['nullable', 'string'],
                    'assessmentkendala'             => ['nullable', 'string'],
                    'assessmentisu'                 => ['nullable', 'string'],
                    'assessmentpembelajaran'        => ['nullable', 'string'],
                ]);
                break;
            case 2: // Sosialisasi
                $rules = array_merge($rules, [
                    'sosialisasiyangterlibat'       => ['nullable', 'string'],
                    'sosialisasitemuan'             => ['nullable', 'string'],
                    'sosialisasitambahan'           => ['nullable', 'string'],
                    'sosialisasitambahan_ket'       => ['nullable', 'string'],
                    'sosialisasikendala'            => ['nullable', 'string'],
                    'sosialisasiisu'                => ['nullable', 'string'],
                    'sosialisasipembelajaran'       => ['nullable', 'string'],
                ]);
                break;
            case 3: // Pelatihan
                $rules = array_merge($rules, [
                    'pelatihanpelatih'              => ['nullable', 'string'],
                    'pelatihanhasil'                => ['nullable', 'string'],
                    'pelatihandistribusi'           => ['nullable', 'string'],
                    'pelatihandistribusi_ket'       => ['nullable', 'string'],
                    'pelatihanrencana'              => ['nullable', 'string'],
                    'pelatihanunggahan'             => ['nullable', 'string'],
                    'pelatihanisu'                  => ['nullable', 'string'],
                    'pelatihanpembelajaran'         => ['nullable', 'string'],
                ]);
                break;
            case 4: // Pembelanjaan
                $rules = array_merge($rules, [
                    'pembelanjaandetailbarang'          => ['nullable', 'string',],
                    'pembelanjaanmulai'                 => ['nullable', 'date'],
                    'pembelanjaanselesai'               => ['nullable', 'date'],
                    'pembelanjaandistribusimulai'       => ['nullable', 'date'],
                    'pembelanjaandistribusiselesai'     => ['nullable', 'date'],
                    'pembelanjaanterdistribusi'         => ['nullable', 'integer'],
                    'pembelanjaanakandistribusi'        => ['nullable', 'integer'],
                    'pembelanjaanakandistribusi_ket'    => ['nullable', 'string'],
                    'pembelanjaankendala'               => ['nullable', 'string'],
                    'pembelanjaanisu'                   => ['nullable', 'string'],
                    'pembelanjaanpembelajaran'          => ['nullable', 'string'],
                ]);
                break;
            case 5: // Pengembangan
                $rules = array_merge($rules, [
                    'pengembanganjeniskomponen'     => ['nullable', 'string'],
                    'pengembanganberapakomponen'    => ['nullable', 'integer'],
                    'pengembanganlokasikomponen'    => ['nullable', 'string'],
                    'pengembanganyangterlibat'      => ['nullable', 'string'],
                    'pengembanganrencana'           => ['nullable', 'string'],
                    'pengembangankendala'           => ['nullable', 'string'],
                    'pengembanganisu'               => ['nullable', 'string'],
                    'pengembanganpembelajaran'      => ['nullable', 'string'],
                ]);
                break;
            case 6: // Kampanye
                $rules = array_merge($rules, [
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
                ]);
                break;
            case 7: // Pemetaan
                $rules = array_merge($rules, [
                    'pemetaanyangdihasilkan'        => ['nullable', 'string'],
                    'pemetaanluasan'                => ['nullable', 'string'],
                    'pemetaanunit'                  => ['nullable', 'string'],
                    'pemetaanyangterlibat'          => ['nullable', 'string'],
                    'pemetaanrencana'               => ['nullable', 'string'],
                    'pemetaanisu'                   => ['nullable', 'string'],
                    'pemetaanpembelajaran'          => ['nullable', 'string'],
                ]);
                break;
            case 8: // Monitoring
                $rules = array_merge($rules, [
                    'monitoringyangdipantau'         => ['nullable', 'string'],
                    'monitoringdata'                 => ['nullable', 'string'],
                    'monitoringyangterlibat'         => ['nullable', 'string'],
                    'monitoringmetode'               => ['nullable', 'string'],
                    'monitoringhasil'                => ['nullable', 'string'],
                    'monitoringkegiatanselanjutnya'  => ['nullable', 'string'],
                    'monitoringkegiatanselanjutnya_ket' => ['nullable', 'string'],
                    'monitoringkendala'                 => ['nullable', 'string'],
                    'monitoringisu'                 => ['nullable', 'string'],
                    'monitoringpembelajaran'        => ['nullable', 'string'],
                ]);
                break;
            case 9: // Kunjungan
                $rules = array_merge($rules, [
                    'kunjunganlembaga'              => ['nullable', 'string'],
                    'kunjunganpeserta'              => ['nullable', 'string'],
                    'kunjunganyangdilakukan'        => ['nullable', 'string'],
                    'kunjunganhasil'                => ['nullable', 'string'],
                    'kunjunganpotensipendapatan'    => ['nullable', 'string'],
                    'kunjunganrencana'              => ['nullable', 'string'],
                    'kunjungankendala'              => ['nullable', 'string'],
                    'kunjunganisu'                  => ['nullable', 'string'],
                    'kunjunganpembelajaran'         => ['nullable', 'string'],
                ]);
                break;
            case 10: // Konsultasi
                $rules = array_merge($rules, [
                    'konsultasilembaga'             => ['nullable', 'string'],
                    'konsultasikomponen'            => ['nullable', 'string'],
                    'konsultasiyangdilakukan'       => ['nullable', 'string'],
                    'konsultasihasil'               => ['nullable', 'string'],
                    'konsultasipotensipendapatan'   => ['nullable', 'string'],
                    'konsultasirencana'             => ['nullable', 'string'],
                    'konsultasikendala'             => ['nullable', 'string'],
                    'konsultasiisu'                 => ['nullable', 'string'],
                    'konsultasipembelajaran'        => ['nullable', 'string'],
                ]);
                break;
            case 11: // Lainnya
                $rules = array_merge($rules, [
                    'lainnyamengapadilakukan'       => ['nullable', 'string'],
                    'lainnyadampak'                 => ['nullable', 'string'],
                    'lainnyasumberpendanaan_ket'    => ['nullable', 'string'],
                    'lainnyayangterlibat'           => ['nullable', 'string'],
                    'lainnyarencana'                => ['nullable', 'string'],
                    'lainnyakendala'                => ['nullable', 'string'],
                    'lainnyaisu'                    => ['nullable', 'string'],
                    'lainnyapembelajaran'           => ['nullable', 'string'],
                ]);
                break;
        }

        return $rules;
    }
}
