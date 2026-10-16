<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use App\Models\Booking;
use App\Models\User;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['booking', 'user'])->latest()->paginate(10);
        return view('admin.payments.index', compact('payments'));
    }

    public function create()
    {
        return view('admin.payments.create', [
            'bookings' => Booking::all(),
            'users' => User::all(),
        ]);
    }

    public function store(PaymentRequest $request)
    {
        Payment::create($request->validated());
        return redirect()->route('admin.payments.index')->with('success', 'Đã thêm thanh toán.');
    }

    public function show(Payment $payment)
    {
        return redirect()->route('admin.payments.edit', $payment);
    }

    public function edit(Payment $payment)
    {
        return view('admin.payments.edit', [
            'payment' => $payment,
            'bookings' => Booking::all(),
            'users' => User::all(),
        ]);
    }

    public function update(PaymentRequest $request, Payment $payment)
    {
        $payment->update($request->validated());
        return redirect()->route('admin.payments.index')->with('success', 'Đã cập nhật thanh toán.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Đã xóa thanh toán.');
    }
}
