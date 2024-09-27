<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mperan')->insert([
            ['nama' => 'Project Manager', 'aktif' => 1],
            ['nama' => 'Penanggung Jawab Project', 'aktif' => 1],
            ['nama' => 'Staff', 'aktif' => 0],
        ]);
    }
}
