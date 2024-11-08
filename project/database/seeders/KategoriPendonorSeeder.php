<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori_Pendonor;

class KategoriPendonorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['nama' => 'Individual', 'aktif' => 1],
            ['nama' => 'Organization', 'aktif' => 1],
            ['nama' => 'Corporate', 'aktif' => 1],
            ['nama' => 'Government', 'aktif' => 0],
        ];

        foreach ($categories as $category) {
            Kategori_Pendonor::updateOrInsert(
                ['nama' => $category['nama']], // condition to find existing record
                $category // data to update or insert
            );
        }
    }
}
