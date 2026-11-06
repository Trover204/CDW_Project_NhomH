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

        $createdSubscription = null;
        DB::transaction(function () use ($user, $plan, $startsAt, $expiresAt, $price, $validated, &$createdSubscription) {
            $createdSubscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'starts_at' => $startsAt,
                'expires_at' => $expiresAt,
                'status' => true,
                'price' => $price,
            ]);

            $this->paymentService->syncFromPayable(
                $createdSubscription,
                paymentMethod: $validated['payment_method'],
                status: 'paid',
                transactionCode: strtoupper(uniqid('TXN_')),
                paidAt: $startsAt,
            );
        });

        $subscriptionSuccessData = [
            'subscription_id' => $createdSubscription->id ?? null,
            'reference' => 'SUB-' . str_pad((string) ($createdSubscription->id ?? rand(1, 9999)), 5, '0', STR_PAD_LEFT),
            'plan_name' => $plan->name,
            'duration_days' => $plan->duration_days,
            'discount_percent' => $plan->discount_percent,
            'total_price' => (float) $price,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'starts_at' => $startsAt->format('d/m/Y'),
            'expires_at' => $expiresAt->format('d/m/Y'),
            'payment_method' => $validated['payment_method'],
            'created_at' => now()->format('H:i - d/m/Y'),
        ];

        return redirect()->route('home')
            ->with('success', 'Bạn đã đăng ký thành công gói '.$plan->name.' (Thanh toán qua '.ucfirst($validated['payment_method']).')')
            ->with('subscription_success', $subscriptionSuccessData);
    }
}
