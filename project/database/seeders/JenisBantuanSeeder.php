<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Jenis_Bantuan;

class JenisBantuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisBantuans = [
            ['nama' => 'Bantuan Sosial', 'aktif' => 1],
            ['nama' => 'Bantuan Pendidikan', 'aktif' => 1],
            ['nama' => 'Bantuan Kesehatan', 'aktif' => 1],
            ['nama' => 'Bantuan Ekonomi', 'aktif' => 1],
            ['nama' => 'Bantuan Bencana', 'aktif' => 1],
            ['nama' => 'Bantuan Perumahan', 'aktif' => 1],
            ['nama' => 'Bantuan Infrastruktur', 'aktif' => 1],
            ['nama' => 'Bantuan Hukum', 'aktif' => 0],
            ['nama' => 'Bantuan Pelatihan', 'aktif' => 1],
            ['nama' => 'Bantuan Usaha', 'aktif' => 1],
        ];

        foreach ($jenisBantuans as $jenisBantuan) {
            Jenis_Bantuan::updateOrInsert(
                ['nama' => $jenisBantuan['nama']], // condition to find existing record
                $jenisBantuan // data to update or insert
            );
        }
    }
}
