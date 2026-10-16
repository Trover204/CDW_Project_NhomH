<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'user_id' => User::factory(),
            'amount' => 200000,
            'payment_method' => 'vnpay',
            'transaction_code' => 'TXN-'.$this->faker->unique()->numerify('######'),
            'status' => 'paid',
            'paid_at' => now(),
        ];
    }
}
