<?php

namespace Database\Seeders;

use App\Models\mSektor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SektorSeederData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sektors = [
            ['id' => 1, 'nama' => __('cruds.kegiatan.basic.data_jenis_kegiatan.12')],
            ['id' => 2, 'nama' => __('cruds.kegiatan.basic.data_jenis_kegiatan.13')],
            ['id' => 3, 'nama' => __('cruds.kegiatan.basic.data_jenis_kegiatan.14')],
            ['id' => 4, 'nama' => __('cruds.kegiatan.basic.data_jenis_kegiatan.15')],
            ['id' => 5, 'nama' => __('cruds.kegiatan.basic.data_jenis_kegiatan.16')],
            ['id' => 6, 'nama' => __('cruds.kegiatan.basic.data_jenis_kegiatan.17')],

        ];


        foreach ($sektors as $sektor) {
            mSektor::updateOrInsert(
                ['nama' => $sektor['nama']], // condition to find existing record
                $sektor // data to update or insert
            );
        }
    }
}
