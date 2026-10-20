<?php

namespace Database\Factories;

use App\Models\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;

class FacilityFactory extends Factory
{
    protected $model = Facility::class;

    public function definition(): array
    {
        return [
            'name' => 'Cơ sở '.$this->faker->unique()->city(),
            'district' => 'Quận ' . $this->faker->numberBetween(1, 12),
            'address' => $this->faker->address(),
            'phone' => '09'.$this->faker->numerify('########'),
            'open_time' => '06:00:00',
            'close_time' => '23:00:00',
            'status' => true,
            'version' => 1,
        ];
    }
}
