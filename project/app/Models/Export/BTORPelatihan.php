<?php

namespace App\Models\Export;

use App\Models\Kegiatan;

class BTORPelatihan extends BTOR
{
    protected function getSpecificData(Kegiatan $kegiatan): array
    {
        if (!$kegiatan->pelatihan) {
            return [];
        }

        return [
            'pelatih' => $kegiatan->pelatihan->pelatihanpelatih,
            'hasil' => $kegiatan->pelatihan->pelatihanhasil,
            'distribusi' => $kegiatan->pelatihan->pelatihandistribusi,
            'distribusi_ket' => $kegiatan->pelatihan->pelatihandistribusi_ket,
            'rencana' => $kegiatan->pelatihan->pelatihanrencana,
            'unggahan' => $kegiatan->pelatihan->pelatihanunggahan,
            'isu' => $kegiatan->pelatihan->pelatihanisu,
            'pembelajaran' => $kegiatan->pelatihan->pelatihanpembelajaran,
        ];
    }
}
