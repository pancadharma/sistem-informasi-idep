<?php

namespace App\Models\Export;

use App\Models\Kegiatan;

class BTORKunjungan extends BTOR
{
    protected function getSpecificData(Kegiatan $kegiatan): array
    {
        if (!$kegiatan->kunjungan) {
            return [];
        }

        return [
            'lembaga'            => $kegiatan->kunjungan->kunjunganlembaga,
            'peserta'            => $kegiatan->kunjungan->kunjunganpeserta,
            'yang_dilakukan'     => $kegiatan->kunjungan->kunjunganyangdilakukan,
            'hasil'              => $kegiatan->kunjungan->kunjunganhasil,
            'potensi_pendapatan' => $kegiatan->kunjungan->kunjunganpotensipendapatan,
            'rencana'            => $kegiatan->kunjungan->kunjunganrencana,
            'kendala'            => $kegiatan->kunjungan->kunjungankendala,
            'isu'                => $kegiatan->kunjungan->kunjunganisu,
            'pembelajaran'       => $kegiatan->kunjungan->kunjunganpembelajaran,
        ];
    }
}
