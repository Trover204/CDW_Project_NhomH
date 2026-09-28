<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $an   = DB::table('users')->where('email', 'an@example.com')->first();
        $binh = DB::table('users')->where('email', 'binh@example.com')->first();
        $sub  = DB::table('subscriptions')->where('user_id', $an->id)->value('id');

        $footballType = DB::table('sport_types')->where('name', 'Bóng đá mini')->value('id');
        $courts = DB::table('courts')->where('sport_type_id', $footballType)->orderBy('id')->limit(2)->pluck('id');
        [$court1, $court2] = [$courts[0], $courts[1]];

        // [user, court, ngày, giờ bắt đầu, giờ kết thúc, subtotal, giảm, status, phương thức TT, trạng thái TT]
        $rows = [
            [$an,   $court1, $now->copy()->subDays(5)->toDateString(), '18:00', '19:00', 300000, 30000, 'completed', 'vnpay', 'paid'],
            [$an,   $court1, $now->copy()->addDays(2)->toDateString(), '19:00', '20:00', 300000, 30000, 'confirmed', 'cod',   'pending'],
            [$binh, $court2, $now->copy()->addDays(3)->toDateString(), '17:00', '18:00', 300000, 0,     'pending',   'vnpay', 'pending'],
            [$binh, $court1, $now->copy()->subDays(2)->toDateString(), '20:00', '21:00', 300000, 0,     'cancelled', 'vnpay', 'failed'],
        ];

        foreach ($rows as $n => [$user, $courtId, $date, $start, $end, $subtotal, $discount, $status, $method, $payStatus]) {
            $bookingId = DB::table('bookings')->insertGetId([
                'user_id' => $user->id, 'court_id' => $courtId,
                'subscription_id' => $discount > 0 ? $sub : null,
                'customer_name' => $user->name, 'customer_phone' => $user->phone, 'customer_email' => $user->email,
                'booking_date' => $date, 'start_time' => "$start:00", 'end_time' => "$end:00",
                'subtotal' => $subtotal, 'discount_amount' => $discount, 'total_price' => $subtotal - $discount,
                'note' => null, 'status' => $status,
                'created_at' => $now, 'updated_at' => $now,
            ]);

            DB::table('payments')->insert([
                'booking_id' => $bookingId, 'user_id' => $user->id,
                'amount' => $subtotal - $discount, 'payment_method' => $method,
                'transaction_code' => 'PAY-DEMO-' . str_pad($n + 1, 4, '0', STR_PAD_LEFT),
                'status' => $payStatus, 'paid_at' => $payStatus === 'paid' ? $now : null,
                'created_at' => $now, 'updated_at' => $now,
            ]);

            DB::table('booking_logs')->insert([
                'booking_id' => $bookingId, 'user_id' => $user->id, 'action' => 'created',
                'created_at' => $now, 'updated_at' => $now,
            ]);

            // Đánh giá cho đơn đã hoàn thành
            if ($status === 'completed') {
                DB::table('reviews')->insert([
                    'user_id' => $user->id, 'court_id' => $courtId, 'booking_id' => $bookingId,
                    'rating' => 5, 'content' => 'Sân đẹp, mặt cỏ tốt, nhân viên thân thiện.',
                    'status' => 1, 'created_at' => $now, 'updated_at' => $now,
                ]);
            }
        }

        // Sân yêu thích
        DB::table('court_user_likes')->insertOrIgnore([
            ['user_id' => $an->id, 'court_id' => $court1, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => $an->id, 'court_id' => $court2, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
