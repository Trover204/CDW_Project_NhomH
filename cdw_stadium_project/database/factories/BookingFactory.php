<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Court;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $subtotal = $this->faker->numberBetween(15, 60) * 10000;
        $discount = 0;
        $totalPrice = $subtotal - $discount;

        return [
            'user_id' => User::factory(),
            'court_id' => Court::factory(),
            'subscription_id' => null,
            'customer_name' => $this->faker->name(),
            'customer_phone' => '09'.$this->faker->numerify('########'),
            'customer_email' => $this->faker->safeEmail(),
            'booking_date' => now()->toDateString(),
            'start_time' => '17:00:00',
            'end_time' => '18:00:00',
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'total_price' => $totalPrice,
            'note' => $this->faker->sentence(),
            'status' => 'completed',
        ];
    }
}
