<?php

namespace Database\Seeders;

use App\Models\Jenis_Kegiatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Seed_Master_Jenis_Kegiatan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id' => 1, 'nama'  => __('cruds.kegiatan.basic.data_jenis_kegiatan.1'), 'aktif' => 1],
            ['id' => 2, 'nama'  => __('cruds.kegiatan.basic.data_jenis_kegiatan.2'), 'aktif' => 1],
            ['id' => 3, 'nama'  => __('cruds.kegiatan.basic.data_jenis_kegiatan.3'), 'aktif' => 1],
            ['id' => 4, 'nama'  => __('cruds.kegiatan.basic.data_jenis_kegiatan.4'), 'aktif' => 1],
            ['id' => 5, 'nama'  => __('cruds.kegiatan.basic.data_jenis_kegiatan.5'), 'aktif' => 1],
            ['id' => 6, 'nama'  => __('cruds.kegiatan.basic.data_jenis_kegiatan.6'), 'aktif' => 1],
            ['id' => 7, 'nama'  => __('cruds.kegiatan.basic.data_jenis_kegiatan.7'), 'aktif' => 1],
            ['id' => 8, 'nama'  => __('cruds.kegiatan.basic.data_jenis_kegiatan.8'), 'aktif' => 1],
            ['id' => 9, 'nama'  => __('cruds.kegiatan.basic.data_jenis_kegiatan.9'), 'aktif' => 1],
            ['id' => 10, 'nama' => __('cruds.kegiatan.basic.data_jenis_kegiatan.10'), 'aktif' => 1],
            ['id' => 11, 'nama' => __('cruds.kegiatan.basic.data_jenis_kegiatan.11'), 'aktif' => 1],
            ['id' => 12, 'nama' => __('cruds.kegiatan.basic.data_jenis_kegiatan.12'), 'aktif' => 1],
            ['id' => 13, 'nama' => __('cruds.kegiatan.basic.data_jenis_kegiatan.13'), 'aktif' => 1],
            ['id' => 14, 'nama' => __('cruds.kegiatan.basic.data_jenis_kegiatan.14'), 'aktif' => 1],
            ['id' => 15, 'nama' => __('cruds.kegiatan.basic.data_jenis_kegiatan.15'), 'aktif' => 1],
            ['id' => 16, 'nama' => __('cruds.kegiatan.basic.data_jenis_kegiatan.16'), 'aktif' => 1],
            ['id' => 17, 'nama' => __('cruds.kegiatan.basic.data_jenis_kegiatan.17'), 'aktif' => 1],
        ];
        foreach ($data as $seed) {
            Jenis_Kegiatan::updateOrInsert(
                ['nama' => $seed['nama']],
                $seed
            );
        }
    }
}