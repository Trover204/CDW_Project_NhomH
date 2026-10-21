<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'plan_id' => Plan::factory(),
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
            'status' => true,
            'payment_method' => $this->faker->randomElement(['momo', 'visa', 'paypal']),
            'amount_paid' => $this->faker->numberBetween(100000, 1000000),
            'transaction_code' => 'TXN_' . strtoupper($this->faker->unique()->bothify('?????#####')),
        ];
    }
}
