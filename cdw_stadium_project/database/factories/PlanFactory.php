<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Gói '.$this->faker->numberBetween(1, 12).' Tháng',
            'duration_days' => $this->faker->numberBetween(30, 365),
            'price' => $this->faker->numberBetween(100000, 1000000),
            'discount_percent' => $this->faker->numberBetween(0, 50),
            'status' => true,
        ];
    }
}
