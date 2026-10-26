<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Áo thi đấu '.$this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'team' => $this->faker->company(),
            'season' => $this->faker->randomElement(['2023/24', '2024/25']),
            'cost_price' => $this->faker->randomFloat(2, 50000, 500000),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'is_in_stock' => $this->faker->boolean(80),
            'image' => null,
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
