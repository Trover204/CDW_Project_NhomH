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
            'name' => $this->faker->randomElement(['Bóng đá', 'Cầu lông', 'Pickleball', 'Tennis', 'Bóng rổ', 'Bóng bàn', 'Bơi lội', 'Gym', 'Yoga']).' '.$this->faker->unique()->numberBetween(1, 999999),
            'image' => null,
            'description' => $this->faker->sentence(),
            'status' => true,
            'version' => 1,
        ];
    }
}
