<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Court;
use App\Models\PriceRule;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingService
{
    /**
     * Tính giá theo từng giờ. Ưu tiên giá riêng của sân, sau đó tới giá chung theo loại môn.
     * $date: Y-m-d, $start/$end: H:i
     */
    public function calculatePrice(Court $court, string $date, string $start, string $end): int
    {
        $dayType = Carbon::parse($date)->isWeekend() ? 'weekend' : 'weekday';
        $total = 0;

        for ($t = Carbon::createFromFormat('H:i', $start);
             $t->lt(Carbon::createFromFormat('H:i', $end));
             $t->addHour()) {

            $hour = $t->format('H:i:s');

            $rule = PriceRule::where('status', 1)
                ->where('sport_type_id', $court->sport_type_id)
                ->where('day_type', $dayType)
                ->where('start_time', '<=', $hour)
                ->where('end_time', '>', $hour)
                ->where(fn ($q) => $q->where('court_id', $court->id)->orWhereNull('court_id'))
                ->orderByRaw('court_id IS NULL')   // giá riêng sân trước
                ->first();

            if (!$rule) {
                throw ValidationException::withMessages(['start_time' => "Chưa có bảng giá cho khung giờ {$t->format('H:i')}."]);
            }
            $total += (int) $rule->price;
        }

        return $total;
    }

    public function create(array $data, int $userId): Booking
    {
        $court = Court::with('facility')->findOrFail($data['court_id']);

        // --- Kiểm tra nghiệp vụ ở tầng ứng dụng ---
        if ($court->status !== 'available') {
            throw ValidationException::withMessages(['court_id' => 'Sân hiện không nhận đặt.']);
        }
        if (Carbon::parse($data['booking_date'] . ' ' . $data['start_time'])->isPast()) {
            throw ValidationException::withMessages(['booking_date' => 'Không thể đặt sân trong quá khứ.']);
        }
        if ($data['start_time'] >= $data['end_time']
            || $data['start_time'] < substr($court->facility->open_time, 0, 5)
            || $data['end_time'] > substr($court->facility->close_time, 0, 5)) {
            throw ValidationException::withMessages(['start_time' => 'Khung giờ nằm ngoài giờ hoạt động của cơ sở.']);
        }

        $subtotal = $this->calculatePrice($court, $data['booking_date'], $data['start_time'], $data['end_time']);

        // Giảm giá theo gói thành viên còn hiệu lực
        $subscription = Subscription::with('plan')
            ->where('user_id', $userId)
            ->where('status', 1)
            ->where('expires_at', '>', now())
            ->latest('expires_at')
            ->first();
        $discount = $subscription ? (int) round($subtotal * $subscription->plan->discount_percent / 100) : 0;

        try {
            return DB::transaction(function () use ($data, $userId, $subtotal, $discount, $subscription) {
                // Chặn chồng lấn khung giờ (bắt cả trường hợp đặt nhiều giờ liên tiếp)
                $conflict = Booking::where('court_id', $data['court_id'])
                    ->where('booking_date', $data['booking_date'])
                    ->whereNotIn('status', ['cancelled', 'rejected'])
                    ->where('start_time', '<', $data['end_time'])
                    ->where('end_time', '>', $data['start_time'])
                    ->lockForUpdate()
                    ->exists();

                if ($conflict) {
                    throw ValidationException::withMessages(['start_time' => 'Khung giờ vừa được người khác đặt trước.']);
                }

                $booking = Booking::create($data + [
                    'user_id'         => $userId,
                    'subscription_id' => $subscription?->id,
                    'subtotal'        => $subtotal,
                    'discount_amount' => $discount,
                    'total_price'     => $subtotal - $discount,
                    'status'          => 'pending',
                ]);

                $booking->logs()->create(['user_id' => $userId, 'action' => 'created']);

                return $booking;
            });
        } catch (QueryException $e) {
            // Unique uq_booking_slot bắt trường hợp 2 request đặt cùng lúc
            if ($e->getCode() === '23000') {
                throw ValidationException::withMessages(['start_time' => 'Khung giờ vừa được người khác đặt trước.']);
            }
            throw $e;
        }
    }

    public function cancel(Booking $booking, int $userId, int $freeCancelHours = 2): void
    {
        $startAt = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->start_time);

        if (now()->addHours($freeCancelHours)->gt($startAt)) {
            throw ValidationException::withMessages(['booking' => 'Không thể hủy vì đã quá thời gian quy định.']);
        }

        DB::transaction(function () use ($booking, $userId) {
            $booking->update(['status' => 'cancelled']);   // slot_lock -> NULL, khung giờ đặt lại được
            $booking->logs()->create(['user_id' => $userId, 'action' => 'cancelled']);
        });
    }
}
