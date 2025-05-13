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
            ['nama' => 'Masyarakat Adat', 'aktif' => 1],
            ['nama' => 'Disabilitas', 'aktif' => 1],
            ['nama' => 'Komunitas LGBTQ+', 'aktif' => 1],
            ['nama' => 'Pengungsi dan Pencari Suaka', 'aktif' => 1],
            ['nama' => 'Tunawisma', 'aktif' => 1],
            ['nama' => 'Lansia', 'aktif' => 1],
            ['nama' => 'Perempuan dan Anak Perempuan', 'aktif' => 1],
            ['nama' => 'Etnis Minoritas', 'aktif' => 1],
            ['nama' => 'Pekerja Migran', 'aktif' => 1],
            ['nama' => 'Korban Kekerasan dan Eksploitasi', 'aktif' => 1],
            ['nama' => 'Ibu Tunggal Kepala Keluarga', 'aktif' => 1],
            ['nama' => 'Korban Konflik dan Perang', 'aktif' => 1],
            ['nama' => 'Ekonomi Rendah', 'aktif' => 1],
            ['nama' => 'Wilayah Sulit Akses', 'aktif' => 1],
            ['nama' => 'Orang Dengan HIV/AIDS', 'aktif' => 1],
            ['nama' => 'Perempuan Hamil', 'aktif' => 1],
            ['nama' => 'Bayi', 'aktif' => 1],
            ['nama' => 'Non-Kelompok Rentan', 'aktif' => 1],

        ];

        foreach ($data as $item) {
            DB::table('mkelompokmarjinal')->updateOrInsert(
                ['nama' => $item['nama']], // Condition to check if the row exists
                $item // Data to update or insert
            );
        }
    }
}