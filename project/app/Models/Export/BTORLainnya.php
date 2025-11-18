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
            'deskripsi' => $kegiatan->lainnya->lainnyadeskrip,
            'kendala' => $kegiatan->lainnya->lainnyakendala,
            'isu' => $kegiatan->lainnya->lainnyaisu,
            'pembelajaran' => $kegiatan->lainnya->lainnyapembelajaran,
        ];
    }
}
