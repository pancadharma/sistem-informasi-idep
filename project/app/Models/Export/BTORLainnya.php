<?php

namespace App\Models\Export;

use App\Models\Kegiatan;

class BTORLainnya extends BTOR
{
    protected function getSpecificData(Kegiatan $kegiatan): array
    {
        if (!$kegiatan->lainnya) {
            return [];
        }

        return [
            'mengapa_dilakukan'   => $kegiatan->lainnya->lainnyamengapadilakukan,
            'dampak'              => $kegiatan->lainnya->lainnyadampak,
            'sumber_pendanaan'    => $kegiatan->lainnya->lainnyasumberpendanaan,
            'sumber_pendanaan_ket' => $kegiatan->lainnya->lainnyasumberpendanaan_ket,
            'yang_terlibat'       => $kegiatan->lainnya->lainnyayangterlibat,
            'rencana'             => $kegiatan->lainnya->lainnyarencana,
            'kendala'             => $kegiatan->lainnya->lainnyakendala,
            'isu'                 => $kegiatan->lainnya->lainnyaisu,
            'pembelajaran'        => $kegiatan->lainnya->lainnyapembelajaran,
        ];
    }
}
