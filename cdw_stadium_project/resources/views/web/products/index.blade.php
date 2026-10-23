@extends('web.layouts.app')

@section('title', 'Merchandise')

@section('content')

<div style="overflow:hidden; background:#FFFFFF; max-width: 1000px; margin: 40px auto; padding: 20px; border: 1px solid #E1E8E3; border-radius: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">

    @if(session('success'))
        <div style="background: #178A4C; color: white; padding: 10px 20px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #E53E3E; color: white; padding: 10px 20px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
            {{ session('error') }}
        </div>
    @endif

    <div style="padding:22px 20px;">
        <div style="text-align:center;margin-bottom:40px;">
            <div style="font-size:24px;font-weight:700;color:#16241C;margin-bottom:6px;">Sản phẩm chính hãng (Merch)</div>
            <div style="font-size:16px;color:#6B7A70;">Các mẫu áo đấu và đồ dùng thể thao chất lượng cao</div>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(220px, 1fr));gap:20px;">
            @forelse($products as $product)
                <div style="border: 1px solid #E1E8E3; border-radius: 12px; padding: 16px; display: flex; flex-direction: column;">

                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px; margin-bottom: 12px;">
                    @else
                        <div style="width: 100%; height: 200px; background-color: #F3F4F6; border-radius: 8px; margin-bottom: 12px; display: flex; align-items: center; justify-content: center; color: #9CA3AF;">
                            Không có ảnh
                        </div>
                    @endif

                    <div style="font-size:16px;font-weight:700;color:#16241C;margin-bottom:4px; flex-grow: 1;">{{ $product->name }}</div>
                    @if($product->team)
                        <div style="font-size:13px;color:#6B7A70;margin-bottom:8px;">{{ $product->team }} {{ $product->season ? "({$product->season})" : '' }}</div>
                    @endif

                    <div style="font-size:18px;font-weight:700;color:#F97316;margin-bottom:12px;">
                        {{ number_format($product->cost_price, 0, ',', '.') }}đ
                    </div>

                    <div>
                        @auth
                            <a href="{{ route('products.checkout', $product->id) }}" style="width:100%;height:36px;border-radius:6px;background:#178A4C;color:#fff;border:none;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:600;cursor:pointer;text-decoration:none;">
                                Mua ngay
                            </a>
                        @else
                            <a href="{{ route('login') }}" style="width:100%;height:36px;border-radius:6px;background:#fff;border:1px solid #C7D3CA;color:#3A453E;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:600;cursor:pointer;text-decoration:none;">
                                Đăng nhập để mua
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; color: #6B7A70; padding: 40px 0;">
                    Hiện chưa có sản phẩm nào.
                </div>
            @endforelse
        </div>

        @if($products->hasPages())
            <div style="margin-top: 30px; display: flex; justify-content: center;">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
