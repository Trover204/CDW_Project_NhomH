<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Sale;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function index(Request $request)
    {
        $baseQuery = Payment::query();

        $summary = [
            'total_paid' => (float) (clone $baseQuery)->where('status', 'paid')->sum('amount'),
            'booking_paid' => (float) (clone $baseQuery)->where('status', 'paid')->where('payable_type', Payment::TYPE_BOOKING)->sum('amount'),
            'sale_paid' => (float) (clone $baseQuery)->where('status', 'paid')->where('payable_type', Payment::TYPE_SALE)->sum('amount'),
            'subscription_paid' => (float) (clone $baseQuery)->where('status', 'paid')->where('payable_type', Payment::TYPE_SUBSCRIPTION)->sum('amount'),
            'pending_count' => (int) (clone $baseQuery)->where('status', 'pending')->count(),
            'total_count' => (int) (clone $baseQuery)->count(),
        ];

        $query = Payment::with(['user:id,name,email', 'payable'])->latest();

        if ($request->filled('payable_type')) {
            $query->where('payable_type', $request->string('payable_type')->toString());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->string('payment_method')->toString());
        }

        if ($request->filled('search')) {
            $keyword = trim($request->string('search')->toString());
            $query->where(function ($q) use ($keyword) {
                $q->where('transaction_code', 'like', "%{$keyword}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$keyword}%")->orWhere('email', 'like', "%{$keyword}%"));
            });
        }

        $payments = $query->paginate(12)->appends($request->query());

        return view('admin.payments.index', compact('payments', 'summary'));
    }

    public function create()
    {
        return view('admin.payments.create', $this->formOptions());
    }

    public function store(PaymentRequest $request)
    {
        $data = $this->preparePayload($request->validated());

        Payment::create($data);

        return redirect()->route('admin.payments.index')->with('success', 'Đã thêm thanh toán.');
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'payable']);

        return view('admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        return view('admin.payments.edit', [
            'payment' => $payment,
            ...$this->formOptions($payment),
        ]);
    }

    public function update(PaymentRequest $request, Payment $payment)
    {
        $data = $this->preparePayload($request->validated(), $payment);

        $payment->update($data);

        return redirect()->route('admin.payments.index')->with('success', 'Đã cập nhật thanh toán.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')->with('success', 'Đã xóa thanh toán.');
    }

    protected function preparePayload(array $data, ?Payment $payment = null): array
    {
        $payable = $this->paymentService->resolvePayable($data['payable_type'], (int) $data['payable_id']);

        if (empty($data['user_id']) && $payable) {
            $data['user_id'] = $payable->payableUserId();
        }

        if (empty($data['transaction_code'])) {
            $data['transaction_code'] = $payment?->transaction_code
                ?: Payment::generateTransactionCode($data['payable_type']);
        }

        if ($data['status'] === 'paid' && empty($data['paid_at'])) {
            $data['paid_at'] = $payment?->paid_at ?? now();
        }

        return $data;
    }

    protected function formOptions(?Payment $current = null): array
    {
        return [
            'bookings' => Booking::with('court:id,name')
                ->select('id', 'user_id', 'court_id', 'customer_name', 'booking_date', 'total_price')
                ->where(function ($q) use ($current) {
                    $q->whereDoesntHave('payment');
                    if ($current && $current->payable_type === Payment::TYPE_BOOKING) {
                        $q->orWhere('id', $current->payable_id);
                    }
                })
                ->latest()
                ->take(100)
                ->get(),
            'sales' => Sale::with('product:id,name')
                ->select('id', 'user_id', 'product_id', 'customer_name', 'quantity', 'total_price', 'sale_date')
                ->where(function ($q) use ($current) {
                    $q->whereDoesntHave('payment');
                    if ($current && $current->payable_type === Payment::TYPE_SALE) {
                        $q->orWhere('id', $current->payable_id);
                    }
                })
                ->latest()
                ->take(100)
                ->get(),
            'subscriptions' => Subscription::with(['user:id,name', 'plan:id,name'])
                ->select('id', 'user_id', 'plan_id', 'price', 'starts_at')
                ->where(function ($q) use ($current) {
                    $q->whereDoesntHave('payment');
                    if ($current && $current->payable_type === Payment::TYPE_SUBSCRIPTION) {
                        $q->orWhere('id', $current->payable_id);
                    }
                })
                ->latest()
                ->take(100)
                ->get(),
            'users' => User::select('id', 'name', 'email')->orderBy('name')->get(),
            'types' => Payment::TYPES,
            'methods' => Payment::METHODS,
            'statuses' => Payment::STATUSES,
        ];
    }
}
