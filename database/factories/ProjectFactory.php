<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $currencies = Currency::all()->pluck('id');

        return [
            'code' => fake()->hexColor(),
            'name' => fake()->company(),
            'duration' => fake()->numberBetween(1, 12),
            'budget' => 1000000,
            'currency_id' => $currencies->random(),
            'budget_code' => fake()->hexColor(),
            'status' => ProjectStatus::DEFAULT,
        ];
    }
}
