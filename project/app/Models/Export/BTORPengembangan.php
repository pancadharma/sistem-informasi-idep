<?php

namespace App\Models\Export;

use App\Models\Kegiatan;

class BTORPengembangan extends BTOR
{
    protected function getSpecificData(Kegiatan $kegiatan): array
    {
        if (!$kegiatan->pengembangan) {
            return [];
        }

        return [
            'jenis_komponen'     => $kegiatan->pengembangan->pengembanganjeniskomponen,
            'berapa_komponen'    => $kegiatan->pengembangan->pengembanganberapakomponen,
            'lokasi_komponen'    => $kegiatan->pengembangan->pengembanganlokasikomponen,
            'yang_terlibat'      => $kegiatan->pengembangan->pengembanganyangterlibat,
            'rencana'            => $kegiatan->pengembangan->pengembanganrencana,
            'kendala'            => $kegiatan->pengembangan->pengembangankendala,
            'isu'                => $kegiatan->pengembangan->pengembanganisu,
            'pembelajaran'       => $kegiatan->pengembangan->pengembanganpembelajaran,
        ];
    }
}
