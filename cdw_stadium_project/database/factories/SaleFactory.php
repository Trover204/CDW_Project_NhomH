<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id ?? Product::factory(),
            'user_id' => $this->faker->boolean(70) ? User::inRandomOrder()->first()->id : null,
            'customer_name' => $this->faker->name,
            'customer_phone' => $this->faker->numerify('09########'),
            'quantity' => $this->faker->numberBetween(1, 5),
            'total_price' => $this->faker->numberBetween(10, 50) * 10000,
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
            'sale_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
