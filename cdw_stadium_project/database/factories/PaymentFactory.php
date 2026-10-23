<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'payable_type' => Payment::TYPE_BOOKING,
            'payable_id' => Booking::factory(),
            'user_id' => User::factory(),
            'amount' => 200000,
            'payment_method' => 'vnpay',
            'transaction_code' => 'TXN-'.$this->faker->unique()->numerify('########'),
            'status' => 'paid',
            'paid_at' => now(),
            'note' => null,
        ];
    }

    public function forBooking(?Booking $booking = null): static
    {
        return $this->state(function () use ($booking) {
            $booking ??= Booking::factory()->create();

            return [
                'payable_type' => Payment::TYPE_BOOKING,
                'payable_id' => $booking->id,
                'user_id' => $booking->user_id,
                'amount' => $booking->total_price,
            ];
        });
    }

    public function forSale(?Sale $sale = null): static
    {
        return $this->state(function () use ($sale) {
            $sale ??= Sale::factory()->create();

            return [
                'payable_type' => Payment::TYPE_SALE,
                'payable_id' => $sale->id,
                'user_id' => $sale->user_id,
                'amount' => $sale->total_price,
            ];
        });
    }

    public function forSubscription(?Subscription $subscription = null): static
    {
        return $this->state(function () use ($subscription) {
            $subscription ??= Subscription::factory()->create();

            return [
                'payable_type' => Payment::TYPE_SUBSCRIPTION,
                'payable_id' => $subscription->id,
                'user_id' => $subscription->user_id,
                'amount' => $subscription->price,
            ];
        });
    }
}
