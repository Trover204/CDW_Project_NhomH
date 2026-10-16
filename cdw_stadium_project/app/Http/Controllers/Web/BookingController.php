<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create(Court $court)
    {
        return view('web.bookings.create', compact('court'));
    }

    public function store(Request $request, Court $court)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:50',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'required|email|max:191',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'note' => 'nullable|string',
        ]);

        // Simple subtotal calculation (assuming 1 hour = 120000)
        // Ideally we fetch from court price rules, but simplify for now
        $startTime = \Carbon\Carbon::parse($validated['start_time']);
        $endTime = \Carbon\Carbon::parse($validated['end_time']);
        $hours = $endTime->diffInMinutes($startTime) / 60;

        $subtotal = 120000 * $hours;
        $discountAmount = 0;
        $totalPrice = $subtotal - $discountAmount;

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
            'note' => $validated['note'],
            'status' => 'pending',
        ]);

        return redirect()->route('home')->with('success', 'Đặt sân thành công!');
    }
}
