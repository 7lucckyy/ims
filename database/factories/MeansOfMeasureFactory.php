<?php

namespace Database\Factories;

use App\Models\Indicator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MeansOfMeasure>
 */
class MeansOfMeasureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'indicator_id' => Indicator::factory(),
            'name' => fake()->name(),
            'value' => fake()->numberBetween(1, 100),
        ];
    }
}
