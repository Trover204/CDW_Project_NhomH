<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Subscription>
 */
class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

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
            'price' => $this->faker->numberBetween(10, 100) * 10000,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Subscription $subscription) {
            if ($subscription->payment()->exists()) {
                return;
            }

            $status = $subscription->status ? 'paid' : 'failed';

            $subscription->payment()->create([
                'user_id' => $subscription->user_id,
                'amount' => $subscription->price,
                'payment_method' => $this->faker->randomElement(['momo', 'vnpay', 'visa', 'paypal']),
                'transaction_code' => Payment::generateTransactionCode(Payment::TYPE_SUBSCRIPTION),
                'status' => $status,
                'paid_at' => $status === 'paid' ? ($subscription->starts_at ?? now()) : null,
            ]);
        });
    }
}
