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
            ['Premium',    730, 1599000, 25],
            ['Gói 1 tuần', 7, 25000, 2],
            ['Gói 2 tuần', 14, 45000, 3],
            ['Gói 2 tháng', 60, 189000, 6],
            ['Gói 3 tháng', 90, 279000, 7],
            ['Gói 4 tháng', 120, 359000, 8],
            ['Gói 5 tháng', 150, 429000, 9],
            ['Gói 7 tháng', 210, 569000, 11],
            ['Gói 8 tháng', 240, 639000, 12],
            ['Gói 9 tháng', 270, 709000, 13],
            ['Gói 10 tháng', 300, 779000, 14],
            ['Gói 11 tháng', 330, 849000, 15],
            ['VIP 1.5 năm', 547, 1299000, 20],
            ['VIP 2.5 năm', 912, 1899000, 26],
            ['VIP 3 năm', 1095, 2199000, 28],
            ['Doanh nghiệp Nhỏ', 365, 3000000, 30],
            ['Doanh nghiệp Lớn', 365, 5000000, 35],
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
