<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreWebBookingRequest;
use App\Models\Booking;
use App\Models\Court;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function create(Court $court)
    {
        return view('web.bookings.create', compact('court'));
    }

    public function store(StoreWebBookingRequest $request, Court $court)
    {
        $validated = $request->validated();

        $startTime = Carbon::parse($validated['start_time']);
        $endTime = Carbon::parse($validated['end_time']);
        $hours =  $startTime->diffInMinutes($endTime) / 60;

        $subtotal = 120000 * $hours;
        $discountAmount = 0;
        $totalPrice = $subtotal - $discountAmount;

        DB::transaction(function () use ($validated, $court, $subtotal, $discountAmount, $totalPrice) {
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'court_id' => $court->id,
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'],
                'booking_date' => $validated['booking_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total_price' => $totalPrice,
                'note' => $validated['note'] ?? null,
                'status' => 'pending',
            ]);

            $this->paymentService->syncFromPayable(
                $booking,
                paymentMethod: $validated['payment_method'] ?? 'cod',
                status: 'pending',
            );
        });

        return redirect()->route('home')->with('success', 'Đặt sân thành công!');
    }
}
