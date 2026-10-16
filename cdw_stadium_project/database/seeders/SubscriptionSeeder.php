<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        $users = DB::table('users')->where('role', 'customer')->get();
        $plans = DB::table('plans')->get();

        if ($users->isEmpty() || $plans->isEmpty()) {
            return;
        }

        foreach ($users as $index => $user) {
            // Assign different plans based on user index
            $plan = $plans[$index % $plans->count()];

            DB::table('subscriptions')->updateOrInsert(
                ['user_id' => $user->id, 'plan_id' => $plan->id],
                [
                    'starts_at' => $now,
                    'expires_at' => $now->copy()->addDays($plan->duration_days),
                    'status' => 1,
                    'payment_method' => 'vnpay',
                    'amount_paid' => $plan->price,
                    'transaction_code' => 'SUB-DEMO-' . str_pad($index + 2, 4, '0', STR_PAD_LEFT), // Avoid conflict with PlanSeeder
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
