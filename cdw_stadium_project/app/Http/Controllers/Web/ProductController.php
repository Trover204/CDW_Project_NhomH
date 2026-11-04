<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\PurchaseProductRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function index(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where('is_in_stock', true);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('team', 'like', "%{$search}%")
                    ->orWhere('season', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('team')) {
            $query->where('team', $request->input('team'));
        }

        $sort = $request->input('sort', 'newest');
        if ($sort === 'price_asc') {
            $query->orderBy('cost_price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('cost_price', 'desc');
        } elseif ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        $teams = Product::where('is_active', true)
            ->whereNotNull('team')
            ->where('team', '!=', '')
            ->distinct()
            ->orderBy('team')
            ->pluck('team');

        return view('web.products.index', compact('products', 'teams'));
    }

    public function checkout(Product $product)
    {
        if (! $product->is_active || ! $product->is_in_stock) {
            return redirect()->route('products.index')->with('error', 'Sản phẩm này hiện không khả dụng.');
        }

        return view('web.products.checkout', compact('product'));
    }

    public function purchase(PurchaseProductRequest $request, Product $product)
    {
        if (! $product->is_active || ! $product->is_in_stock) {
            return redirect()->route('products.index')->with('error', 'Sản phẩm này hiện không khả dụng.');
        }

        $validated = $request->validated();
        $user = Auth::user();

        try {
            DB::beginTransaction();

            $totalPrice = $product->cost_price * $validated['quantity'];
            $customerName = !empty($validated['customer_name']) ? trim($validated['customer_name']) : $user->name;
            $customerPhone = !empty($validated['customer_phone']) ? trim($validated['customer_phone']) : ($user->phone ?? '');

            $sale = Sale::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'quantity' => $validated['quantity'],
                'total_price' => $totalPrice,
                'status' => 'completed',
                'sale_date' => now()->toDateString(),
            ]);

            $noteDetails = [];
            if (!empty($validated['shipping_address'])) {
                $noteDetails[] = 'Địa chỉ: ' . trim($validated['shipping_address']);
            }
            if (!empty($validated['note'])) {
                $noteDetails[] = 'Ghi chú: ' . trim($validated['note']);
            }
            $noteText = !empty($noteDetails)
                ? 'Đơn #' . $sale->id . ' (' . $sale->quantity . 'x ' . $product->name . ') - ' . implode(' | ', $noteDetails)
                : 'Đơn #' . $sale->id . ' (' . $sale->quantity . 'x ' . $product->name . ')';

            $this->paymentService->syncFromPayable(
                $sale,
                paymentMethod: $validated['payment_method'],
                status: 'paid',
                paidAt: now(),
                note: $noteText,
            );

            $product->stock_quantity -= $validated['quantity'];
            if ($product->stock_quantity <= 0) {
                $product->is_in_stock = false;
            }
            $product->save();

            DB::commit();

            $orderSuccessData = [
                'order_id' => $sale->id,
                'reference' => 'SL-' . str_pad((string) $sale->id, 5, '0', STR_PAD_LEFT),
                'product_name' => $product->name,
                'product_image' => $product->image_url,
                'team' => $product->team,
                'season' => $product->season,
                'quantity' => $validated['quantity'],
                'unit_price' => (float) $product->cost_price,
                'total_price' => $totalPrice,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'shipping_address' => $validated['shipping_address'] ?? '',
                'note' => $validated['note'] ?? '',
                'payment_method' => $validated['payment_method'],
                'created_at' => now()->format('H:i - d/m/Y'),
            ];

            return redirect()->route('home')
                ->with('success', 'Bạn đã mua thành công '.$validated['quantity'].' x '.$product->name.' (Thanh toán qua '.ucfirst($validated['payment_method']).')')
                ->with('order_success', $orderSuccessData);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại sau.');
        }
    }
}
