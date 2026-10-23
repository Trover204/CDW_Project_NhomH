<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function index()
    {
        $subscriptions = Subscription::with(['user:id,name', 'plan:id,name,price', 'payment'])->latest()->paginate(10);

        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        return view('admin.subscriptions.create', [
            'users' => User::select('id', 'name', 'email')->orderBy('name')->get(),
            'plans' => Plan::select('id', 'name', 'price', 'duration_days')->get(),
        ]);
    }

    public function store(SubscriptionRequest $request)
    {
        $validated = $request->validated();
        $plan = Plan::findOrFail($validated['plan_id']);
        $price = $validated['price'] ?? $validated['amount_paid'] ?? $plan->price;

        DB::transaction(function () use ($validated, $price) {
            $subscription = Subscription::create([
                'user_id' => $validated['user_id'],
                'plan_id' => $validated['plan_id'],
                'starts_at' => $validated['starts_at'],
                'expires_at' => $validated['expires_at'],
                'status' => $validated['status'] ?? true,
                'price' => $price,
            ]);

            $this->paymentService->syncFromPayable(
                $subscription,
                paymentMethod: $validated['payment_method'] ?? null,
                status: $validated['payment_status'] ?? null,
                transactionCode: $validated['transaction_code'] ?? null,
            );
        });

        return redirect()->route('admin.subscriptions.index')->with('success', 'Đã thêm subscription và đồng bộ thanh toán.');
    }

    public function show(Subscription $subscription)
    {
        return redirect()->route('admin.subscriptions.edit', $subscription);
    }

    public function edit(Subscription $subscription)
    {
        $subscription->load('payment');

        return view('admin.subscriptions.edit', [
            'subscription' => $subscription,
            'users' => User::select('id', 'name', 'email')->orderBy('name')->get(),
            'plans' => Plan::select('id', 'name', 'price', 'duration_days')->get(),
        ]);
    }

    public function update(SubscriptionRequest $request, Subscription $subscription)
    {
        $validated = $request->validated();
        $plan = Plan::findOrFail($validated['plan_id']);
        $price = $validated['price'] ?? $validated['amount_paid'] ?? $subscription->price ?? $plan->price;

        DB::transaction(function () use ($subscription, $validated, $price) {
            $subscription->update([
                'user_id' => $validated['user_id'],
                'plan_id' => $validated['plan_id'],
                'starts_at' => $validated['starts_at'],
                'expires_at' => $validated['expires_at'],
                'status' => $validated['status'] ?? true,
                'price' => $price,
            ]);

            $this->paymentService->syncFromPayable(
                $subscription,
                paymentMethod: $validated['payment_method'] ?? null,
                status: $validated['payment_status'] ?? null,
                transactionCode: $validated['transaction_code'] ?? null,
            );
        });

        return redirect()->route('admin.subscriptions.index')->with('success', 'Đã cập nhật subscription và thanh toán.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')->with('success', 'Đã xóa subscription.');
    }
}
