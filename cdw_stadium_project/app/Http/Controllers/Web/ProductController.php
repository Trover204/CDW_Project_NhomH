<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)
            ->where('is_in_stock', true)
            ->paginate(12);

        return view('web.products.index', compact('products'));
    }

    public function checkout(Product $product)
    {
        if (!$product->is_active || !$product->is_in_stock) {
            return redirect()->route('products.index')->with('error', 'Sản phẩm này hiện không khả dụng.');
        }

        return view('web.products.checkout', compact('product'));
    }

    public function purchase(Request $request, Product $product)
    {
        if (!$product->is_active || !$product->is_in_stock) {
            return redirect()->route('products.index')->with('error', 'Sản phẩm này hiện không khả dụng.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:momo,visa,paypal',
            'quantity' => 'required|integer|min:1|max:' . $product->stock_quantity,
        ]);

        $user = Auth::user();

        try {
            DB::beginTransaction();

            $totalPrice = $product->cost_price * $validated['quantity'];

            $sale = Sale::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_phone' => $user->phone,
                'quantity' => $validated['quantity'],
                'total_price' => $totalPrice,
                'status' => 'completed',
                'sale_date' => now(),
            ]);

            $product->stock_quantity -= $validated['quantity'];
            if ($product->stock_quantity <= 0) {
                $product->is_in_stock = false;
            }
            $product->save();

            DB::commit();

            return redirect()->route('home')->with('success', 'Bạn đã mua thành công ' . $validated['quantity'] . ' x ' . $product->name . ' (Thanh toán qua ' . ucfirst($validated['payment_method']) . ')');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau.');
        }
    }
}
