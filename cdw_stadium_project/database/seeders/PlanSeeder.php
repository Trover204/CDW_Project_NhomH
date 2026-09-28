<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $plans = [
            ['Cơ bản',     30,  99000,  5],
            ['Tiêu chuẩn', 180, 499000, 10],
            ['VIP',        365, 899000, 18],
        ];

        foreach ($plans as [$name, $days, $price, $discount]) {
            DB::table('plans')->updateOrInsert(
                ['name' => $name],
                [
                    'duration_days' => $days, 'price' => $price, 'discount_percent' => $discount,
                    'status' => 1, 'created_at' => $now, 'updated_at' => $now,
                ]
            );
        }

        // Cho khách "An" đăng ký gói Tiêu chuẩn để demo giảm giá
        $userId = DB::table('users')->where('email', 'an@example.com')->value('id');
        $plan = DB::table('plans')->where('name', 'Tiêu chuẩn')->first();

        DB::table('subscriptions')->where('user_id', $userId)->delete();
        DB::table('subscriptions')->insert([
            'user_id' => $userId, 'plan_id' => $plan->id,
            'starts_at' => $now, 'expires_at' => $now->copy()->addDays($plan->duration_days),
            'status' => 1, 'payment_method' => 'vnpay', 'amount_paid' => $plan->price,
            'transaction_code' => 'SUB-DEMO-0001',
            'created_at' => $now, 'updated_at' => $now,
        ]);
    }
}
