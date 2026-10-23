@extends('web.layouts.app')

@section('title', 'Thanh toán sản phẩm')

@section('content')

<div style="overflow:hidden; background:#FFFFFF; max-width: 600px; margin: 40px auto; padding: 20px; border: 1px solid #E1E8E3; border-radius: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">

    <div style="padding:22px 20px;">
        <div style="text-align:center;margin-bottom:30px;">
            <div style="font-size:24px;font-weight:700;color:#16241C;margin-bottom:6px;">Thanh toán đơn hàng</div>
        </div>

        @if(session('error'))
            <div style="background: #E53E3E; color: white; padding: 10px 20px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                {{ session('error') }}
            </div>
        @endif

        <div style="background:#F9FAFB; padding:20px; border-radius:12px; margin-bottom: 24px; border: 1px solid #E5E7EB;">
            <div style="font-size:18px;font-weight:700;color:#16241C;margin-bottom:10px;">Chi tiết sản phẩm</div>

            <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                @else
                    <div style="width: 80px; height: 80px; background-color: #E5E7EB; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #9CA3AF;">No Image</div>
                @endif

                <div>
                    <div style="font-weight:600; color:#111827; font-size: 16px; margin-bottom: 4px;">{{ $product->name }}</div>
                    @if($product->team)
                        <div style="color:#6B7280; font-size: 14px; margin-bottom: 4px;">{{ $product->team }} {{ $product->season ? "({$product->season})" : '' }}</div>
                    @endif
                    <div style="color:#178A4C; font-weight: 600;">Còn lại: {{ $product->stock_quantity }} sản phẩm</div>
                </div>
            </div>

            <div style="display:flex; justify-content:space-between; border-top: 1px solid #E5E7EB; padding-top: 10px; margin-top: 10px;">
                <span style="font-weight:600; color:#111827;">Đơn giá:</span>
                <span style="font-weight:700; font-size:18px; color:#F97316;">{{ number_format($product->cost_price, 0, ',', '.') }}đ</span>
            </div>
        </div>

        <form action="{{ route('products.purchase', $product->id) }}" method="POST" id="purchase-form" onsubmit="return confirm('Bạn có chắc chắn muốn mua sản phẩm này không?');">
            @csrf

            <div style="margin-bottom: 24px;">
                <div style="font-size:16px;font-weight:600;color:#16241C;margin-bottom:12px;">Số lượng</div>
                <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" required
                       style="width: 100%; padding: 10px; border: 1px solid #D1D5DB; border-radius: 6px; font-size: 16px;">
                @error('quantity')
                    <div style="color: red; font-size: 14px; margin-top: 8px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-bottom: 24px;">
                <div style="font-size:16px;font-weight:600;color:#16241C;margin-bottom:12px;">Phương thức thanh toán</div>

                <label style="display:flex; align-items:center; padding: 12px; border: 1px solid #E1E8E3; border-radius: 8px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s;">
                    <input type="radio" name="payment_method" value="momo" required style="margin-right: 12px; width: 18px; height: 18px;">
                    <span style="font-weight: 500; color: #374151;">Ví MoMo</span>
                </label>

                <label style="display:flex; align-items:center; padding: 12px; border: 1px solid #E1E8E3; border-radius: 8px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s;">
                    <input type="radio" name="payment_method" value="visa" required style="margin-right: 12px; width: 18px; height: 18px;">
                    <span style="font-weight: 500; color: #374151;">Thẻ Visa/Mastercard</span>
                </label>

                <label style="display:flex; align-items:center; padding: 12px; border: 1px solid #E1E8E3; border-radius: 8px; cursor: pointer; transition: all 0.2s;">
                    <input type="radio" name="payment_method" value="paypal" required style="margin-right: 12px; width: 18px; height: 18px;">
                    <span style="font-weight: 500; color: #374151;">PayPal</span>
                </label>

                @error('payment_method')
                    <div style="color: red; font-size: 14px; margin-top: 8px;">{{ $message }}</div>
                @enderror
            </div>

            <div style="display:flex; justify-content:space-between; align-items: center; margin-bottom: 24px; padding: 15px; background: #FFF7ED; border-radius: 8px; border: 1px solid #FDBA74;">
                <span style="font-weight:600; color:#9A3412;">Tổng tiền thanh toán:</span>
                <span style="font-weight:700; font-size:22px; color:#C2410C;" id="total-price-display">{{ number_format($product->cost_price, 0, ',', '.') }}đ</span>
            </div>

            <button type="submit" style="width:100%;height:48px;border-radius:8px;background:#178A4C;color:#fff;border:none;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:600;cursor:pointer;">
                Xác nhận thanh toán
            </button>

            <div style="text-align: center; margin-top: 16px;">
                <a href="{{ route('products.index') }}" style="color: #6B7280; text-decoration: none; font-size: 14px;">Quay lại cửa hàng</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qtyInput = document.getElementById('quantity');
        const totalDisplay = document.getElementById('total-price-display');
        const unitPrice = {{ $product->cost_price }};

        qtyInput.addEventListener('input', function() {
            let qty = parseInt(this.value);
            if (isNaN(qty) || qty < 1) qty = 1;

            const total = qty * unitPrice;
            totalDisplay.textContent = new Intl.NumberFormat('vi-VN', { style: 'decimal' }).format(total) + 'đ';
        });
    });
</script>

@endsection
