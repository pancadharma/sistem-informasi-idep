<?php

namespace App\Models\Export;

use App\Models\Kegiatan;

class BTORSosialisasi extends BTOR
{
    protected function getSpecificData(Kegiatan $kegiatan): array
    {
        if (!$kegiatan->sosialisasi) {
            return [];
        }

        return [
            'yang_terlibat' => $kegiatan->sosialisasi->sosialisasiyangterlibat,
            'temuan' => $kegiatan->sosialisasi->sosialisasitemuan,
            'sosialisasi_tambahan' => $kegiatan->sosialisasi->sosialisasitambahan,
            'sosialisasi_tambahan_ket' => $kegiatan->sosialisasi->sosialisasitambahan_ket,
            'kendala' => $kegiatan->sosialisasi->sosialisasikendala,
            'isu' => $kegiatan->sosialisasi->sosialisasiisu,
            'pembelajaran' => $kegiatan->sosialisasi->sosialisasipembelajaran,
        ];
    }
}
