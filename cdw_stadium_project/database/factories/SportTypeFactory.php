<?php

namespace Database\Factories;

use App\Models\SportType;
use Illuminate\Database\Eloquent\Factories\Factory;

class SportTypeFactory extends Factory
{
    protected $model = SportType::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Bóng đá', 'Cầu lông', 'Pickleball', 'Tennis', 'Bóng rổ']).' '.$this->faker->unique()->numberBetween(1, 9999),
            'image' => null,
            'description' => $this->faker->sentence(),
            'status' => true,
            'version' => 1,
        ];
    }
}
