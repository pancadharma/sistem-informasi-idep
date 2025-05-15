<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelompokMarjinalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['id'=>1 ,'nama' => 'Masyarakat Adat', 'aktif' => 1],
            ['id'=>2 ,'nama' => 'Bayi', 'aktif' => 1],
            ['id'=>3 ,'nama' => 'Disabilitas', 'aktif' => 1], // Disabilitas for Dashboard to Count, id should be 3
            ['id'=>4 ,'nama' => 'Lansia', 'aktif' => 1],
            ['id'=>5 ,'nama' => 'Komunitas LGBTQ+', 'aktif' => 1],
            ['id'=>6 ,'nama' => 'Pengungsi dan Pencari Suaka', 'aktif' => 1],
            ['id'=>7 ,'nama' => 'Tunawisma', 'aktif' => 1],
            ['id'=>8 ,'nama' => 'Perempuan dan Anak Perempuan', 'aktif' => 1],
            ['id'=>9 ,'nama' => 'Etnis Minoritas', 'aktif' => 1],
            ['id'=>10 ,'nama' => 'Pekerja Migran', 'aktif' => 1],
            ['id'=>11 ,'nama' => 'Korban Kekerasan dan Eksploitasi', 'aktif' => 1],
            ['id'=>12 ,'nama' => 'Ibu Tunggal Kepala Keluarga', 'aktif' => 1],
            ['id'=>13 ,'nama' => 'Korban Konflik dan Perang', 'aktif' => 1],
            ['id'=>14 ,'nama' => 'Ekonomi Rendah', 'aktif' => 1],
            ['id'=>15 ,'nama' => 'Wilayah Sulit Akses', 'aktif' => 1],
            ['id'=>16 ,'nama' => 'Orang Dengan HIV/AIDS', 'aktif' => 1],
            ['id'=>17 ,'nama' => 'Perempuan Hamil', 'aktif' => 1],
            ['id'=>18 ,'nama' => 'Non-Kelompok Rentan', 'aktif' => 1],

        ];

        foreach ($data as $item) {
            DB::table('mkelompokmarjinal')->updateOrInsert(
                ['nama' => $item['nama']], // Condition to check if the row exists
                $item // Data to update or insert
            );
        }
    }
}
