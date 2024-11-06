<?php

namespace Database\Factories;

use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgramFactory extends Factory
{
    protected $model = Program::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nama'                                  => $this->faker->name,
            'kode'                                  => $this->faker->word,
            'tanggalmulai'                          => $this->faker->date,
            'tanggalselesai'                        => $this->faker->date,
            'totalnilai'                            => $this->faker->randomFloat(2, 1000, 100000),
            'ekspektasipenerimamanfaat'             => $this->faker->numberBetween(1, 1000),
            'ekspektasipenerimamanfaatwoman'        => $this->faker->numberBetween(1, 500),
            'ekspektasipenerimamanfaatman'          => $this->faker->numberBetween(1, 500),
            'ekspektasipenerimamanfaatgirl'         => $this->faker->numberBetween(1, 250),
            'ekspektasipenerimamanfaatboy'          => $this->faker->numberBetween(1, 250),
            'ekspektasipenerimamanfaattidaklangsung'=> $this->faker->numberBetween(1, 1000),
            'deskripsiprojek'                       => $this->faker->text(500),
            'analisamasalah'                        => $this->faker->text(500),
            'user_id'                               => \App\Models\User::factory(),
            'status'                                => $this->faker->word,
        ];
    }
}
