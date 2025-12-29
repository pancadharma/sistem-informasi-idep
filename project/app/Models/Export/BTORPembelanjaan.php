<?php

namespace App\Models\Export;

use App\Models\Kegiatan;

class BTORPembelanjaan extends BTOR
{
    protected function getSpecificData(Kegiatan $kegiatan): array
    {
        if (!$kegiatan->pembelanjaan) {
            return [];
        }

        return [
            'detail_barang' => $kegiatan->pembelanjaan->pembelanjaandetailbarang,
            'mulai' => $kegiatan->pembelanjaan->pembelanjaanmulai,
            'selesai' => $kegiatan->pembelanjaan->pembelanjaanselesai,
            'distribusi_mulai' => $kegiatan->pembelanjaan->pembelanjaandistribusimulai,
            'distribusi_selesai' => $kegiatan->pembelanjaan->pembelanjaandistribusiselesai,
            'terdistribusi' => $kegiatan->pembelanjaan->pembelanjaanterdistribusi,
            'kendala' => $kegiatan->pembelanjaan->pembelanjaankendala,
            'isu' => $kegiatan->pembelanjaan->pembelanjaanisu,
            'pembelajaran' => $kegiatan->pembelanjaan->pembelanjaanpembelajaran,
        ];
    }
}
