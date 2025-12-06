<?php

namespace App\Models\Export;

use App\Models\Kegiatan;

class BTORKampanye extends BTOR
{
    protected function getSpecificData(Kegiatan $kegiatan): array
    {
        if (!$kegiatan->kampanye) {
            return [];
        }

        return [
            'yang_dipantau' => $kegiatan->kampanye->kampanyeyangdipantau,
            'data' => $kegiatan->kampanye->kampanyedata,
            'yang_terlibat' => $kegiatan->kampanye->kampanyeyangterlibat,
            'hasil' => $kegiatan->kampanye->kampanyehasil,
            'kendala' => $kegiatan->kampanye->kampanyekendala,
            'isu' => $kegiatan->kampanye->kampanyeisu,
            'pembelajaran' => $kegiatan->kampanye->kampanyepembelajaran,
        ];
    }
}
