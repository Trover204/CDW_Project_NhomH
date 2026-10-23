<?php

namespace Database\Seeders;

use App\Models\Payment;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $products = DB::table('products')->get();
        $customers = DB::table('users')->where('role', 'customer')->get();

        if ($products->isEmpty()) {
            return;
        }

        $faker = Factory::create('vi_VN');
        $methods = ['cash', 'momo', 'vnpay', 'visa', 'paypal'];

        for ($i = 1; $i <= 25; $i++) {
            $product = $products[$i % $products->count()];
            $user = $customers->isNotEmpty() && $faker->boolean(75)
                ? $faker->randomElement($customers)
                : null;

            $quantity = $faker->numberBetween(1, 4);
            $unitPrice = (float) $product->cost_price > 0 ? (float) $product->cost_price : 150000;
            $totalPrice = $unitPrice * $quantity;
            $status = $i <= 18 ? 'completed' : $faker->randomElement(['pending', 'completed', 'cancelled']);
            $saleDate = $faker->dateTimeBetween('-4 months', 'now')->format('Y-m-d');
            $method = $methods[$i % count($methods)];

            $payStatus = match ($status) {
                'completed' => 'paid',
                'cancelled' => 'failed',
                default => 'pending',
            };
            $paidAt = $payStatus === 'paid' ? Carbon::parse($saleDate)->setTime(15, 0) : null;

            $saleId = DB::table('sales')->insertGetId([
                'product_id' => $product->id,
                'user_id' => $user?->id,
                'customer_name' => $user?->name ?? $faker->name(),
                'customer_phone' => $user?->phone ?? $faker->numerify('09########'),
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'status' => $status,
                'sale_date' => $saleDate,
                'created_at' => $paidAt ?? now(),
                'updated_at' => $paidAt ?? now(),
            ]);

            DB::table('payments')->insert([
                'payable_type' => Payment::TYPE_SALE,
                'payable_id' => $saleId,
                'user_id' => $user?->id,
                'amount' => $totalPrice,
                'payment_method' => $method,
                'transaction_code' => 'SL-DEMO-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                'status' => $payStatus,
                'paid_at' => $paidAt,
                'note' => "Thanh toán đơn hàng #{$saleId} ({$quantity}x {$product->name})",
                'created_at' => $paidAt ?? now(),
                'updated_at' => $paidAt ?? now(),
            ]);
        }
    }
}
