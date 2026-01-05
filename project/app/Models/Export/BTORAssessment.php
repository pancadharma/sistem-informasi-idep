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
            'yang_terlibat' => $kegiatan->assessment->assessmentyangterlibat,
            'temuan'        => $kegiatan->assessment->assessmenttemuan,
            'tambahan'      => $kegiatan->assessment->assessmenttambahan,
            'tambahan_ket'  => $kegiatan->assessment->assessmenttambahan_ket,
            'kendala'       => $kegiatan->assessment->assessmentkendala,
            'isu'           => $kegiatan->assessment->assessmentisu,
            'pembelajaran'  => $kegiatan->assessment->assessmentpembelajaran,
        ];
    }
}