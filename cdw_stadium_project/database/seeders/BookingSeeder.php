<?php

namespace Database\Seeders;

use App\Models\Payment;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $an = DB::table('users')->where('email', 'an@example.com')->first();
        $binh = DB::table('users')->where('email', 'binh@example.com')->first();
        $cuong = DB::table('users')->where('email', 'cuong@example.com')->first();

        $sub = DB::table('subscriptions')->where('user_id', $an->id)->value('id');

        $footballType = DB::table('sport_types')->where('name', 'Bóng đá mini')->value('id');
        $courts = DB::table('courts')->where('sport_type_id', $footballType)->orderBy('id')->limit(2)->pluck('id');
        [$court1, $court2] = [$courts[0], $courts[1]];

        // [user, court, ngày, giờ bắt đầu, giờ kết thúc, subtotal, giảm, status, phương thức TT, trạng thái TT]
        $rows = [
            [$an,    $court1, $now->copy()->subDays(5)->toDateString(), '18:00', '19:00', 300000, 30000, 'completed', 'vnpay', 'paid'],
            [$an,    $court1, $now->copy()->addDays(2)->toDateString(), '19:00', '20:00', 300000, 30000, 'confirmed', 'cod',   'pending'],
            [$binh,  $court2, $now->copy()->addDays(3)->toDateString(), '17:00', '18:00', 300000, 0,     'pending',   'vnpay', 'pending'],
            [$binh,  $court1, $now->copy()->subDays(2)->toDateString(), '20:00', '21:00', 300000, 0,     'cancelled', 'vnpay', 'failed'],
            [$cuong, $court1, $now->copy()->addDays(1)->toDateString(), '15:00', '17:00', 600000, 0,     'confirmed', 'momo',  'paid'],
            [$cuong, $court2, $now->copy()->addDays(4)->toDateString(), '18:00', '20:00', 600000, 0,     'pending',   'cod',   'pending'],
        ];

        foreach ($rows as $n => [$user, $courtId, $date, $start, $end, $subtotal, $discount, $status, $method, $payStatus]) {
            $bookingId = DB::table('bookings')->insertGetId([
                'user_id' => $user->id,
                'court_id' => $courtId,
                'subscription_id' => $discount > 0 ? $sub : null,
                'customer_name' => $user->name,
                'customer_phone' => $user->phone,
                'customer_email' => $user->email,
                'booking_date' => $date,
                'start_time' => "$start:00",
                'end_time' => "$end:00",
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'total_price' => $subtotal - $discount,
                'note' => null,
                'status' => $status,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $paidAt = $payStatus === 'paid' ? Carbon::parse($date)->setTime(18, 0) : null;

            DB::table('payments')->insert([
                'payable_type' => Payment::TYPE_BOOKING,
                'payable_id' => $bookingId,
                'user_id' => $user->id,
                'amount' => $subtotal - $discount,
                'payment_method' => $method,
                'transaction_code' => 'BK-DEMO-'.str_pad((string) ($n + 1), 4, '0', STR_PAD_LEFT),
                'status' => $payStatus,
                'paid_at' => $paidAt,
                'note' => 'Thanh toán đặt sân #'.$bookingId,
                'created_at' => $paidAt ?? $now,
                'updated_at' => $paidAt ?? $now,
            ]);

            DB::table('booking_logs')->insert([
                'booking_id' => $bookingId,
                'user_id' => $user->id,
                'action' => 'created',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            if ($status === 'completed') {
                DB::table('reviews')->insert([
                    'user_id' => $user->id,
                    'court_id' => $courtId,
                    'booking_id' => $bookingId,
                    'rating' => 5,
                    'content' => 'Sân đẹp, mặt cỏ tốt, nhân viên thân thiện.',
                    'status' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // Fake 40 additional bookings
        $faker = Factory::create('vi_VN');
        $allCustomers = DB::table('users')->where('role', 'customer')->get();
        $allCourts = DB::table('courts')->pluck('id')->toArray();

        for ($i = 1; $i <= 40; $i++) {
            $user = $faker->randomElement($allCustomers);
            $courtId = $faker->randomElement($allCourts);
            $sub = DB::table('subscriptions')->where('user_id', $user->id)->value('id');
            $date = $faker->dateTimeBetween('-6 months', '+1 months')->format('Y-m-d');

            $startHour = 6 + ($i % 15);
            $endHour = $startHour + 1;

            $start = str_pad((string) $startHour, 2, '0', STR_PAD_LEFT).':00';
            $end = str_pad((string) $endHour, 2, '0', STR_PAD_LEFT).':00';
            $subtotal = $faker->numberBetween(100, 1000) * 1000;
            $discount = $sub ? $faker->numberBetween(10, 50) * 1000 : 0;
            $status = $faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']);
            $method = $faker->randomElement(['vnpay', 'momo', 'cod', 'bank_transfer']);
            $payStatus = $method === 'cod' && $status !== 'completed'
                ? 'pending'
                : ($status === 'cancelled' ? 'failed' : 'paid');
            $paidAt = $payStatus === 'paid' ? Carbon::parse($date)->setTime($startHour, 0) : null;

            try {
                $bookingId = DB::table('bookings')->insertGetId([
                    'user_id' => $user->id,
                    'court_id' => $courtId,
                    'subscription_id' => $discount > 0 ? $sub : null,
                    'customer_name' => $user->name,
                    'customer_phone' => $user->phone,
                    'customer_email' => $user->email,
                    'booking_date' => $date,
                    'start_time' => $start.':00',
                    'end_time' => $end.':00',
                    'subtotal' => $subtotal,
                    'discount_amount' => $discount,
                    'total_price' => $subtotal - $discount,
                    'note' => $faker->realText(50),
                    'status' => $status,
                    'created_at' => $paidAt ?? $now,
                    'updated_at' => $paidAt ?? $now,
                ]);

                DB::table('payments')->insert([
                    'payable_type' => Payment::TYPE_BOOKING,
                    'payable_id' => $bookingId,
                    'user_id' => $user->id,
                    'amount' => $subtotal - $discount,
                    'payment_method' => $method,
                    'transaction_code' => 'BK-FAK-'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'status' => $payStatus,
                    'paid_at' => $paidAt,
                    'note' => 'Thanh toán đặt sân #'.$bookingId,
                    'created_at' => $paidAt ?? $now,
                    'updated_at' => $paidAt ?? $now,
                ]);

                DB::table('booking_logs')->insert([
                    'booking_id' => $bookingId,
                    'user_id' => $user->id,
                    'action' => 'created',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                if ($status === 'completed') {
                    DB::table('reviews')->insert([
                        'user_id' => $user->id,
                        'court_id' => $courtId,
                        'booking_id' => $bookingId,
                        'rating' => $faker->numberBetween(3, 5),
                        'content' => $faker->realText(100),
                        'status' => 1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            } catch (\Exception $e) {
                // Ignore unique constraint violation and continue
            }
        }

        // Sân yêu thích
        DB::table('court_user_likes')->insertOrIgnore([
            ['user_id' => $an->id, 'court_id' => $court1, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => $an->id, 'court_id' => $court2, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
