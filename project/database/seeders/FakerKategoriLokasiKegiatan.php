<?php

namespace Database\Seeders;

use App\Models\Kategori_Lokasi_Kegiatan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakerKategoriLokasiKegiatan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            Kategori_Lokasi_Kegiatan::factory()->count(50)->create();
        });
    }
}
