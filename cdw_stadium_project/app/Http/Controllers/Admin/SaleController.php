<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['product', 'user'])->latest()->paginate(10);
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
        Sale::create($validated);
        return redirect()->route('admin.sales.index')->with('success', 'Đơn hàng bán đã được tạo thành công.');
    }

    public function edit(Sale $sale)
    {
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
        $sale->update($validated);
        return redirect()->route('admin.sales.index')->with('success', 'Đơn hàng bán đã được cập nhật thành công.');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('admin.sales.index')->with('success', 'Đơn hàng bán đã được xóa thành công.');
    }
}
