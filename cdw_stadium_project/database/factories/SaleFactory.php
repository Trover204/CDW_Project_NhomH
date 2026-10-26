<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'user_id' => $this->faker->boolean(70) ? (User::inRandomOrder()->first()?->id ?? User::factory()) : null,
            'customer_name' => $this->faker->name(),
            'customer_phone' => $this->faker->numerify('09########'),
            'quantity' => $this->faker->numberBetween(1, 5),
            'total_price' => $this->faker->numberBetween(10, 50) * 10000,
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled']),
            'sale_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Sale $sale) {
            if ($sale->payment()->exists()) {
                return;
            }

            $status = match ($sale->status) {
                'completed' => 'paid',
                'cancelled' => 'failed',
                default => 'pending',
            };

            $paidAt = $status === 'paid'
                ? ($sale->sale_date ? Carbon::parse($sale->sale_date)->setTime(14, 0) : now())
                : null;

            $sale->payment()->create([
                'user_id' => $sale->user_id,
                'amount' => $sale->total_price,
                'payment_method' => $this->faker->randomElement(['cash', 'momo', 'vnpay', 'visa']),
                'transaction_code' => Payment::generateTransactionCode(Payment::TYPE_SALE),
                'status' => $status,
                'paid_at' => $paidAt,
            ]);
        });
    }
}
