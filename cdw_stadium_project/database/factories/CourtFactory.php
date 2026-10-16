<?php

namespace Database\Factories;

use App\Models\Court;
use App\Models\Facility;
use App\Models\SportType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourtFactory extends Factory
{
    protected $model = Court::class;

    public function definition(): array
    {
        return [
            'facility_id' => Facility::factory(),
            'sport_type_id' => SportType::factory(),
            'name' => 'Sân '.$this->faker->unique()->numberBetween(1, 999),
            'capacity' => 10,
            'description' => $this->faker->sentence(),
            'status' => 'available',
            'version' => 1,
        ];
    }
}
