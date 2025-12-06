<?php

namespace App\Models\Export;

use App\Models\Kegiatan;

class BTORPemetaan extends BTOR
{
    protected function getSpecificData(Kegiatan $kegiatan): array
    {
        if (!$kegiatan->pemetaan) {
            return [];
        }

        return [
            'yang_dihasilkan' => $kegiatan->pemetaan->pemetaanyangdihasilkan,
            'luasan' => $kegiatan->pemetaan->pemetaanluasan,
            'unit' => $kegiatan->pemetaan->pemetaanunit,
            'yang_terlibat' => $kegiatan->pemetaan->pemetaanyangterlibat,
            'rencana' => $kegiatan->pemetaan->pemetaanrencana,
            'isu' => $kegiatan->pemetaan->pemetaanisu,
            'pembelajaran' => $kegiatan->pemetaan->pemetaanpembelajaran,
        ];
    }
}
