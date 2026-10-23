<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function index()
    {
        $bookings = Booking::with(['user', 'court', 'subscription', 'payment'])->latest()->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        return view('admin.bookings.create', [
            'users' => User::select('id', 'name', 'email')->orderBy('name')->get(),
            'courts' => Court::select('id', 'name')->orderBy('name')->get(),
            'subscriptions' => Subscription::with('plan:id,name')->select('id', 'user_id', 'plan_id')->latest()->get(),
        ]);
    }

    public function store(BookingRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            $booking = Booking::create(Arr::except($validated, ['payment_method', 'payment_status']));

            $this->paymentService->syncFromPayable(
                $booking,
                paymentMethod: $validated['payment_method'] ?? null,
                status: $validated['payment_status'] ?? null,
            );
        });

        return redirect()->route('admin.bookings.index')->with('success', 'Đã thêm đặt sân và đồng bộ thanh toán.');
    }

    public function show(Booking $booking)
    {
        return redirect()->route('admin.bookings.edit', $booking);
    }

    public function edit(Booking $booking)
    {
        $booking->load('payment');

        return view('admin.bookings.edit', [
            'booking' => $booking,
            'users' => User::select('id', 'name', 'email')->orderBy('name')->get(),
            'courts' => Court::select('id', 'name')->orderBy('name')->get(),
            'subscriptions' => Subscription::with('plan:id,name')->select('id', 'user_id', 'plan_id')->latest()->get(),
        ]);
    }

    public function update(BookingRequest $request, Booking $booking)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($booking, $validated) {
            $booking->update(Arr::except($validated, ['payment_method', 'payment_status']));

            $this->paymentService->syncFromPayable(
                $booking,
                paymentMethod: $validated['payment_method'] ?? null,
                status: $validated['payment_status'] ?? null,
            );
        });

        return redirect()->route('admin.bookings.index')->with('success', 'Đã cập nhật đặt sân và thanh toán.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Đã xóa đặt sân.');
    }
}
