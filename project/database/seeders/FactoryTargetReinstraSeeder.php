<?php

namespace Database\Seeders;

use App\Models\TargetReinstra;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FactoryTargetReinstraSeeder extends Seeder
{

    public function run(): void
    {
        DB::transaction(function () {
            TargetReinstra::factory()->count(50)->create();
        });
    }
}
