<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreWebBookingRequest;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Subscription;
use App\Services\BookingService;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected BookingService $bookingService
    ) {}

    public function create(Court $court)
    {
        $court->load(['facility', 'sportType', 'images']);

        $selectedDate = request('date', now()->format('Y-m-d'));

        // Lấy danh sách booking đã được đặt của sân trong ngày được chọn để view hiển thị cảnh báo
        $bookedSlots = Booking::where('court_id', $court->id)
            ->whereDate('booking_date', $selectedDate)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->get(['start_time', 'end_time'])
            ->flatMap(function ($b) {
                $slots = [];
                $startH = (int) substr((string) $b->start_time, 0, 2);
                $endH = (int) substr((string) $b->end_time, 0, 2);
                $endM = (int) substr((string) $b->end_time, 3, 2);
                if ($endM > 0) {
                    $endH++;
                }
                if ($endH <= $startH) {
                    return [substr((string) $b->start_time, 0, 5)];
                }
                for ($h = $startH; $h < $endH; $h++) {
                    $slots[] = str_pad((string) $h, 2, '0', STR_PAD_LEFT) . ':00';
                }

                return $slots;
            })
            ->unique()
            ->values()
            ->all();

        // Kiểm tra xem user hiện tại có gói thành viên hoạt động không
        $user = Auth::user();
        $subscription = null;
        if ($user) {
            $subscription = Subscription::with('plan')
                ->where('user_id', $user->id)
                ->where('status', 1)
                ->where('expires_at', '>', now())
                ->latest('expires_at')
                ->first();
        }

        // Lấy các price rules áp dụng cho sân này để tính giá real-time
        $priceRules = \App\Models\PriceRule::where('status', 1)
            ->where('sport_type_id', $court->sport_type_id)
            ->where(fn ($q) => $q->where('court_id', $court->id)->orWhereNull('court_id'))
            ->orderByRaw('court_id IS NULL')
            ->get(['day_type', 'start_time', 'end_time', 'price']);

        return view('web.bookings.create', compact('court', 'bookedSlots', 'subscription', 'selectedDate', 'priceRules'));
    }

    public function store(StoreWebBookingRequest $request, Court $court)
    {
        $validated = $request->validated();
        $user = Auth::user();

        // 1. Kiểm tra trạng thái sân
        if ($court->status !== 'available') {
            return back()->withInput()->withErrors([
                'court_id' => 'Sân bóng hiện tại không mở nhận đặt lịch (Bảo trì hoặc tạm ngưng).'
            ]);
        }

        // 2. Chuẩn hóa format giờ (H:i)
        try {
            $startTime = Carbon::parse($validated['start_time'])->format('H:i');
            $endTime = Carbon::parse($validated['end_time'])->format('H:i');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors([
                'start_time' => 'Định dạng khung giờ không hợp lệ. Vui lòng chọn lại.'
            ]);
        }

        if ($startTime >= $endTime) {
            return back()->withInput()->withErrors([
                'end_time' => 'Giờ kết thúc phải sau giờ bắt đầu.'
            ]);
        }

        // Kiểm tra giờ hoạt động của cơ sở nếu có
        if ($court->facility) {
            $openTime = substr($court->facility->open_time, 0, 5);
            $closeTime = substr($court->facility->close_time, 0, 5);
            if ($openTime && $closeTime) {
                if ($startTime < $openTime || $endTime > $closeTime) {
                    return back()->withInput()->withErrors([
                        'start_time' => "Khung giờ đặt sân phải nằm trong giờ mở cửa của cơ sở ({$openTime} - {$closeTime})."
                    ]);
                }
            }
        }

        // 3. Tính toán đơn giá chính xác
        $bookingDate = $validated['booking_date'];
        $subtotal = 0;
        try {
            $subtotal = $this->bookingService->calculatePrice($court, $bookingDate, $startTime, $endTime);
        } catch (\Exception $e) {
            // Dự phòng nếu khung giờ chưa có trong price_rules
            $startObj = Carbon::parse($startTime);
            $endObj = Carbon::parse($endTime);
            $hours = max(1, $startObj->diffInMinutes($endObj) / 60);
            $courtUnitPrice = $court->price ?? 120000;
            $subtotal = (int) round($courtUnitPrice * $hours);
        }

        // 4. Áp dụng ưu đãi gói hội viên VIP nếu có
        $subscription = Subscription::with('plan')
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->where('expires_at', '>', now())
            ->latest('expires_at')
            ->first();

        $discountAmount = 0;
        if ($subscription && $subscription->plan && $subscription->plan->discount_percent > 0) {
            $discountAmount = (int) round($subtotal * ($subscription->plan->discount_percent / 100));
        }

        $totalPrice = max(0, $subtotal - $discountAmount);
        $paymentMethod = $validated['payment_method'] ?? 'cod';
        $paymentMemo = ! empty($validated['payment_memo']) ? trim($validated['payment_memo']) : null;
        $isOnlinePaid = in_array($paymentMethod, ['qr', 'bank_transfer', 'momo', 'visa', 'paypal', 'vnpay'], true);
        $paymentStatus = $isOnlinePaid ? 'paid' : 'pending';

        // 5. Kiểm tra trùng lặp lịch & Transaction lưu đơn
        $createdPayment = null;
        try {
            $createdBooking = DB::transaction(function () use ($court, $user, $validated, $bookingDate, $startTime, $endTime, $subtotal, $discountAmount, $totalPrice, $subscription, $paymentMethod, $paymentMemo, $paymentStatus, $isOnlinePaid, &$createdPayment) {
                // Khóa kiểm tra chồng lấn
                $conflict = Booking::where('court_id', $court->id)
                    ->whereDate('booking_date', $bookingDate)
                    ->whereNotIn('status', ['cancelled', 'rejected'])
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime)
                    ->lockForUpdate()
                    ->exists();

                if ($conflict) {
                    throw ValidationException::withMessages([
                        'start_time' => 'Rất tiếc! Khung giờ này vừa có người nhanh tay đặt trước. Vui lòng chọn khung giờ khác hoặc ngày khác.'
                    ]);
                }

                $booking = Booking::create([
                    'user_id' => $user->id,
                    'court_id' => $court->id,
                    'subscription_id' => $subscription?->id,
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'customer_email' => $validated['customer_email'],
                    'booking_date' => $bookingDate,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discountAmount,
                    'total_price' => $totalPrice,
                    'note' => $validated['note'] ?? null,
                    'status' => 'pending',
                ]);

                // Ghi log hoạt động
                if (method_exists($booking, 'logs')) {
                    $booking->logs()->create([
                        'user_id' => $user->id,
                        'action' => 'created'
                    ]);
                }

                $paymentNote = $paymentMemo
                    ? "Đặt sân #{$booking->id} ({$court->name}) - Nội dung CK: {$paymentMemo}"
                    : ($validated['note'] ?? null);

                // Đồng bộ sang bảng payments
                $createdPayment = $this->paymentService->syncFromPayable(
                    $booking,
                    paymentMethod: $paymentMethod,
                    status: $paymentStatus,
                    paidAt: $isOnlinePaid ? now() : null,
                    note: $paymentNote,
                );

                return $booking;
            });
        } catch (ValidationException $ve) {
            return back()->withInput()->withErrors($ve->errors());
        } catch (QueryException $qe) {
            if ($qe->getCode() === '23000') {
                return back()->withInput()->withErrors([
                    'start_time' => 'Khung giờ này vừa được người khác đặt thành công. Vui lòng chọn một khung giờ khác.'
                ]);
            }
            throw $qe;
        }

        $methodLabels = [
            'qr' => 'Quét mã QR Ngân Hàng (VietQR 24/7)',
            'bank_transfer' => 'Chuyển khoản Ngân Hàng (VietQR 24/7)',
            'momo' => 'Ví điện tử MoMo (Napas 247)',
            'visa' => 'Thẻ Quốc tế (Visa / MasterCard)',
            'paypal' => 'Cổng thanh toán PayPal',
            'vnpay' => 'Cổng thanh toán VNPay',
            'cod' => 'Thanh toán trực tiếp tại sân (COD)',
            'cash' => 'Tiền mặt tại sân',
        ];

        // Chuẩn bị thông tin vé đặt sân hiển thị popup xác nhận chuyên nghiệp
        $bookingSuccessData = [
            'booking_id' => $createdBooking->id,
            'reference' => 'BK-' . str_pad((string) $createdBooking->id, 5, '0', STR_PAD_LEFT),
            'transaction_code' => $createdPayment?->transaction_code ?? ('BK-' . now()->format('ymdHis')),
            'payment_memo' => $paymentMemo ?? ('BK' . str_pad((string) $createdBooking->id, 5, '0', STR_PAD_LEFT)),
            'court_name' => $court->name,
            'facility_name' => $court->facility->name ?? 'Cơ sở Sân Nhóm H',
            'facility_address' => $court->facility->address ?? '',
            'sport_type' => $court->sportType->name ?? 'Thể thao',
            'booking_date' => Carbon::parse($bookingDate)->format('d/m/Y'),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'total_price' => $totalPrice,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'],
            'payment_method' => $paymentMethod,
            'payment_method_label' => $methodLabels[$paymentMethod] ?? strtoupper($paymentMethod),
            'payment_status' => $paymentStatus,
            'created_at' => now()->format('H:i - d/m/Y'),
        ];

        return redirect()->route('home')
            ->with('success', 'Đặt sân thành công!')
            ->with('booking_success', $bookingSuccessData);
    }
}
