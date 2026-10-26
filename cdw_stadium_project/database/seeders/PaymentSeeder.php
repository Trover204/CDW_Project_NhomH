<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Sale;
use App\Models\Subscription;
use App\Services\PaymentService;
use Illuminate\Database\Seeder;

/**
 * Đảm bảo mọi Booking, Sale và Subscription chưa có bản ghi thanh toán
 * đều được đồng bộ sang bảng payments.
 */
class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(PaymentService::class);

        Booking::whereDoesntHave('payment')->each(fn (Booking $b) => $service->syncFromPayable($b));
        Sale::whereDoesntHave('payment')->each(fn (Sale $s) => $service->syncFromPayable($s));
        Subscription::whereDoesntHave('payment')->each(fn (Subscription $sub) => $service->syncFromPayable($sub));
    }
}
