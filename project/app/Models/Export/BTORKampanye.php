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
            'yang_dikampanyekan' => $kegiatan->kampanye->kampanyeyangdikampanyekan,
            'jenis'              => $kegiatan->kampanye->kampanyejenis,
            'bentuk_kegiatan'    => $kegiatan->kampanye->kampanyebentukkegiatan,
            'yang_terlibat'      => $kegiatan->kampanye->kampanyeyangterlibat,
            'yang_disasar'       => $kegiatan->kampanye->kampanyeyangdisasar,
            'jangkauan'          => $kegiatan->kampanye->kampanyejangkauan,
            'rencana'            => $kegiatan->kampanye->kampanyerencana,
            'hasil'              => $kegiatan->kampanye->kampanyehasil,
            'kendala'            => $kegiatan->kampanye->kampanyekendala,
            'isu'                => $kegiatan->kampanye->kampanyeisu,
            'pembelajaran'       => $kegiatan->kampanye->kampanyepembelajaran,
        ];
    }
}
