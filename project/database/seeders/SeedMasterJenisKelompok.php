<?php

namespace Database\Seeders;

use App\Models\Master_Jenis_Kelompok;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeedMasterJenisKelompok extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas = [
            ['id' => 1, 'nama' => "Masyarakat Umum"],
            ['id' => 2, 'nama' => "Kelompok Usaha"],
            ['id' => 3, 'nama' => "CBO"],
            ['id' => 4, 'nama' => "KMPB"],
            ['id' => 5, 'nama' => "Stakeholder Local"],
            ['id' => 6, 'nama' => "Stakeholder Provinsi"],
            ['id' => 7, 'nama' => "Stakeholder Nasional"],
            ['id' => 8, 'nama' => "NGO/CSO"],

        ];


        foreach ($datas as $data) {
            Master_Jenis_Kelompok::updateOrInsert(
                ['nama' => $data['nama']], // condition to find existing record
                $data // data to update or insert
            );
        }
    }
}