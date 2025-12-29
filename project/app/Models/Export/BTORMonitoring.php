<?php

namespace App\Models\Export;

use App\Models\Kegiatan;

class BTORMonitoring extends BTOR
{
    protected function getSpecificData(Kegiatan $kegiatan): array
    {
        if (!$kegiatan->monitoring) {
            return [];
        }

        return [
            'yang_dipantau' => $kegiatan->monitoring->monitoringyangdipantau,
            'data' => $kegiatan->monitoring->monitoringdata,
            'yang_terlibat' => $kegiatan->monitoring->monitoringyangterlibat,
            'metode' => $kegiatan->monitoring->monitoringmetode,
            'hasil' => $kegiatan->monitoring->monitoringhasil,
            'kegiatan_selanjutnya' => $kegiatan->monitoring->monitoringkegiatanselanjutnya,
            'kegiatan_selanjutnya_ket' => $kegiatan->monitoring->monitoringkegiatanselanjutnyaket,
            'kendala' => $kegiatan->monitoring->monitoringkendala,
            'isu' => $kegiatan->monitoring->monitoringisu,
            'pembelajaran' => $kegiatan->monitoring->monitoringpembelajaran,
        ];
    }
}
