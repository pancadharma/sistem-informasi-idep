<?php

namespace Database\Factories;

use App\Models\Dusun;
use App\Models\Kelurahan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dusun>
 */
class DusunFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Dusun::class;
    public function definition(): array
    {
        $dusunNames = Kelurahan::pluck('nama')->toArray();
        $kelurahan = Kelurahan::inRandomOrder()->first();


        return [
            'kode'          => $this->faker->regexify('[A-Z0-9]{16}'),
            'nama'          => $this->faker->randomElement($dusunNames),
            'latitude'      => $this->faker->latitude(-10.1718, 5.88969),
            'longitude'     => $this->faker->longitude(95.31644, 140.71813),
            // 'coordinates'   => $this->faker->text,
            'kode_pos' => $this->faker->numerify('#####'), // Generates a 5-digit postal code
            'aktif'         => $this->faker->numberBetween(0, 1),
            'desa_id'       => $kelurahan->id,
        ];
    }
}
