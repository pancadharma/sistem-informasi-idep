<?php

namespace App\Models\Export;

use App\Models\Kegiatan;

class BTORAssessment extends BTOR
{
    /**
     * Get specific data for Assessment type
     */
    protected function getSpecificData(Kegiatan $kegiatan): array
    {
        if (!$kegiatan->assessment) {
            return [];
        }

        return [
            'yang_dipantau' => $kegiatan->assessment->assessmentyangdipantau,
            'data' => $kegiatan->assessment->assessmentdata,
            'yang_terlibat' => $kegiatan->assessment->assessmentyangterlibat,
            'metode' => $kegiatan->assessment->assessmentmetode,
            'hasil' => $kegiatan->assessment->assessmenthasil,
            'kendala' => $kegiatan->assessment->assessmentkendala,
            'isu' => $kegiatan->assessment->assessmentisu,
            'pembelajaran' => $kegiatan->assessment->assessmentpembelajaran,
        ];
    }
}
