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
            'lembaga'            => $kegiatan->konsultasi->konsultasilembaga,
            'komponen'           => $kegiatan->konsultasi->konsultasikomponen,
            'yang_dilakukan'     => $kegiatan->konsultasi->konsultasiyangdilakukan,
            'hasil'              => $kegiatan->konsultasi->konsultasihasil,
            'potensi_pendapatan' => $kegiatan->konsultasi->konsultasipotensipendapatan,
            'rencana'            => $kegiatan->konsultasi->konsultasirencana,
            'kendala'            => $kegiatan->konsultasi->konsultasikendala,
            'isu'                => $kegiatan->konsultasi->konsultasiisu,
            'pembelajaran'       => $kegiatan->konsultasi->konsultasipembelajaran,
        ];
    }
}