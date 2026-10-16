<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Subscription;
use App\Models\User;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'court', 'subscription', 'payment'])->latest()->paginate(10);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function create()
    {
        return view('admin.bookings.create', [
            'users' => User::all(),
            'courts' => Court::all(),
            'subscriptions' => Subscription::all(),
        ]);
    }

    public function store(BookingRequest $request)
    {
        Booking::create($request->validated());
        return redirect()->route('admin.bookings.index')->with('success', 'Đã thêm đặt sân.');
    }

    public function show(Booking $booking)
    {
        return redirect()->route('admin.bookings.edit', $booking);
    }

    public function edit(Booking $booking)
    {
        return view('admin.bookings.edit', [
            'booking' => $booking,
            'users' => User::all(),
            'courts' => Court::all(),
            'subscriptions' => Subscription::all(),
        ]);
    }

    public function update(BookingRequest $request, Booking $booking)
    {
        $booking->update($request->validated());
        return redirect()->route('admin.bookings.index')->with('success', 'Đã cập nhật đặt sân.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')->with('success', 'Đã xóa đặt sân.');
    }
}
