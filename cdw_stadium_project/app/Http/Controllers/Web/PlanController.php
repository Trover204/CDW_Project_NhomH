<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\SubscribePlanRequest;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PlanController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function index()
    {
        $plans = Plan::where('status', true)->get();

        return view('web.plans.index', compact('plans'));
    }

    public function checkout(Plan $plan)
    {
        return view('web.plans.checkout', compact('plan'));
    }

    public function subscribe(SubscribePlanRequest $request, Plan $plan)
    {
        $validated = $request->validated();
        $user = Auth::user();

        $startsAt = now();
        $expiresAt = now()->addDays($plan->duration_days);
        $price = $plan->price;

        DB::transaction(function () use ($user, $plan, $startsAt, $expiresAt, $price, $validated) {
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'status' => true,
                'price' => $price,
            ]);

            $this->paymentService->syncFromPayable(
                $subscription,
                paymentMethod: $validated['payment_method'],
                status: 'paid',
                transactionCode: strtoupper(uniqid('TXN_')),
                paidAt: $startsAt,
            );
        });

        return redirect()->route('home')->with('success', 'Bạn đã đăng ký thành công gói '.$plan->name.' (Thanh toán qua '.ucfirst($validated['payment_method']).')');
    }
}
