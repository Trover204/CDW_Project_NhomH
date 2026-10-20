<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::where('status', true)->get();
        return view('web.plans.index', compact('plans'));
    }

    public function checkout(Plan $plan)
    {
        return view('web.plans.checkout', compact('plan'));
    }

    public function subscribe(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:momo,visa,paypal',
        ]);

        $user = Auth::user();

        // Optional logic: Check if user already has an active subscription to this plan, etc.
        // Or deactivate old subscriptions, but for now we just create a new one.

        $startsAt = now();
        $expiresAt = now()->addDays($plan->duration_days);
        $amountPaid = $plan->price; // you could apply discounts here based on user

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => $startsAt,
            'expires_at' => $expiresAt,
            'status' => true,
            'amount_paid' => $amountPaid,
            'payment_method' => $validated['payment_method'],
            'transaction_code' => strtoupper(uniqid('TXN_')),
        ]);

        return redirect()->route('home')->with('success', 'Bạn đã đăng ký thành công gói ' . $plan->name . ' (Thanh toán qua ' . ucfirst($validated['payment_method']) . ')');
    }
}
