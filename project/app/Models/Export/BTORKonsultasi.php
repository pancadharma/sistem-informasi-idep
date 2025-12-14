<?php

namespace App\Models\Export;

use App\Models\Kegiatan;

class BTORKonsultasi extends BTOR
{
    protected function getSpecificData(Kegiatan $kegiatan): array
    {
        if (!$kegiatan->konsultasi) {
            return [];
        }

        return [
            'konsultan' => $kegiatan->konsultasi->konsultasikonsultan,
            'hal' => $kegiatan->konsultasi->konsultasihal,
            'hasil' => $kegiatan->konsultasi->konsultasihasil,
            'rencana' => $kegiatan->konsultasi->konsultasirencana,
            'rencana_ket' => $kegiatan->konsultasi->konsultasirencana_ket,
            'kendala' => $kegiatan->konsultasi->konsultasikendala,
            'isu' => $kegiatan->konsultasi->konsultasiisu,
            'pembelajaran' => $kegiatan->konsultasi->konsultasipembelajaran,
        ];
    }
}
