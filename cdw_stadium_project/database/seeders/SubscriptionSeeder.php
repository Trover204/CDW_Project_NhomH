<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $users = DB::table('users')->where('role', 'customer')->orderBy('id')->get();
        $plans = DB::table('plans')->orderBy('id')->get();

        if ($users->isEmpty() || $plans->isEmpty()) {
            return;
        }

        $standardPlan = $plans->firstWhere('name', 'Tiêu chuẩn') ?? $plans->first();
        $methods = ['vnpay', 'momo', 'visa', 'paypal'];

        foreach ($users as $index => $user) {
            // Khách An mặc định đăng ký gói Tiêu chuẩn để demo giảm giá đặt sân
            $plan = $user->email === 'an@example.com'
                ? $standardPlan
                : $plans[$index % $plans->count()];

            $startsAt = $now->copy()->subDays(($index * 3) % 25);
            $expiresAt = $startsAt->copy()->addDays($plan->duration_days);

            $existingId = DB::table('subscriptions')
                ->where('user_id', $user->id)
                ->where('plan_id', $plan->id)
                ->value('id');

            if ($existingId) {
                DB::table('subscriptions')->where('id', $existingId)->update([
                    'starts_at' => $startsAt,
                    'expires_at' => $expiresAt,
                    'status' => 1,
                    'price' => $plan->price,
                    'updated_at' => $now,
                ]);
                $subscriptionId = $existingId;
            } else {
                $subscriptionId = DB::table('subscriptions')->insertGetId([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'starts_at' => $startsAt,
                    'expires_at' => $expiresAt,
                    'status' => 1,
                    'price' => $plan->price,
                    'created_at' => $startsAt,
                    'updated_at' => $startsAt,
                ]);
            }

            DB::table('payments')->updateOrInsert(
                [
                    'payable_type' => Payment::TYPE_SUBSCRIPTION,
                    'payable_id' => $subscriptionId,
                ],
                [
                    'user_id' => $user->id,
                    'amount' => $plan->price,
                    'payment_method' => $methods[$index % count($methods)],
                    'transaction_code' => 'SUB-DEMO-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'status' => 'paid',
                    'paid_at' => $startsAt,
                    'note' => 'Thanh toán đăng ký gói '.$plan->name,
                    'created_at' => $startsAt,
                    'updated_at' => $startsAt,
                ]
            );
        }
    }
}
