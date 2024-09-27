<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TargetReinstra;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TargetReinstra>
 */
class TargetReinstraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TargetReinstra::class;

    public function definition(): array
    {
        return [
            'nama'       => $this->faker->name(),
            'aktif'      => $this->faker->boolean(),
        ];
    }
}
