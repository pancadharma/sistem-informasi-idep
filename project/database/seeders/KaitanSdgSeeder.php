<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaitanSdgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mkaitan_sdg')->insert([
            ['nama' => 'No Poverty', 'aktif' => 1],
            ['nama' => 'Zero Hunger', 'aktif' => 1],
            ['nama' => 'Good Health and Well-Being', 'aktif' => 1],
            ['nama' => 'Quality Education', 'aktif' => 1],
            ['nama' => 'Climate Action', 'aktif' => 1],
            ['nama' => 'Peace, Justice, and Strong Institutions', 'aktif' => 0],
        ]);
    }
}
