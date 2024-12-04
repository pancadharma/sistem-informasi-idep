<?php

namespace Database\Factories;

use App\Models\Kategori_Lokasi_Kegiatan;
use App\Models\Kelurahan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kategori_Lokasi_Kegiatan>
 */
class Kategori_Lokasi_KegiatanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kategori_Lokasi_Kegiatan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dusunNames = Kelurahan::pluck('nama')->toArray();

        return [
            'nama'          => $this->faker->randomElement($dusunNames),
            'aktif'         => $this->faker->boolean(),
            'created_at'    => $this->faker->dateTime(),
            'updated_at'    => $this->faker->dateTime(),
        ];
    }
}
