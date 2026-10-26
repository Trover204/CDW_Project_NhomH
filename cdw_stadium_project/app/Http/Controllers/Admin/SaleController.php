<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Services\PaymentService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function index()
    {
        $sales = Sale::with(['product', 'user', 'payment'])->latest()->paginate(10);

        return view('admin.sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('is_active', true)->get();
        $users = User::all();

        return view('admin.sales.create', compact('products', 'users'));
    }

    public function store(SaleRequest $request)
    {
        $validated = $request->validated();
        if (empty($validated['sale_date'])) {
            $validated['sale_date'] = now()->toDateString();
        }

        DB::transaction(function () use ($validated) {
            $sale = Sale::create(Arr::except($validated, ['payment_method', 'payment_status']));

            $this->paymentService->syncFromPayable(
                $sale,
                paymentMethod: $validated['payment_method'] ?? null,
                status: $validated['payment_status'] ?? null,
            );
        });

        return redirect()->route('admin.sales.index')->with('success', 'Đơn hàng bán đã được tạo và đồng bộ thanh toán.');
    }

    public function show(Sale $sale)
    {
        return redirect()->route('admin.sales.edit', $sale);
    }

    public function edit(Sale $sale)
    {
        $sale->load('payment');
        $products = Product::where('is_active', true)->get();
        $users = User::all();

        return view('admin.sales.edit', compact('sale', 'products', 'users'));
    }

    public function update(SaleRequest $request, Sale $sale)
    {
        $validated = $request->validated();
        if (empty($validated['sale_date'])) {
            $validated['sale_date'] = now()->toDateString();
        }

        DB::transaction(function () use ($sale, $validated) {
            $sale->update(Arr::except($validated, ['payment_method', 'payment_status']));

            $this->paymentService->syncFromPayable(
                $sale,
                paymentMethod: $validated['payment_method'] ?? null,
                status: $validated['payment_status'] ?? null,
            );
        });

        return redirect()->route('admin.sales.index')->with('success', 'Đơn hàng bán đã được cập nhật thành công.');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('admin.sales.index')->with('success', 'Đơn hàng bán đã được xóa thành công.');
    }
}
