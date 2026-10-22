@extends('admin.layouts.app')
@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="topbar">
    <h1>Danh sách sản phẩm</h1>
    <div class="right">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm sản phẩm mới
        </a>
    </div>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 70px;">Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Đội / CLB</th>
                <th>Mùa giải</th>
                <th>Giá gốc</th>
                <th style="width: 100px;">Tồn kho</th>
                <th style="width: 130px;">Trạng thái</th>
                <th style="width: 130px; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>
                    @if ($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" style="width:44px;height:44px;object-fit:cover;border-radius:8px;border:1px solid var(--border);">
                    @else
                        <span style="color:var(--muted);">—</span>
                    @endif
                </td>
                <td style="font-weight: 600; color: var(--text);">{{ $product->name }}</td>
                <td>{{ $product->team ?: '—' }}</td>
                <td>{{ $product->season ?: '—' }}</td>
                <td>{{ number_format($product->cost_price) }} đ</td>
                <td>{{ $product->stock_quantity }}</td>
                <td>
                    @if ($product->is_active)
                        <span class="badge success">Hoạt động</span>
                    @else
                        <span class="badge danger">Tạm ẩn</span>
                    @endif
                    @if ($product->is_in_stock)
                        <span class="badge success">Còn hàng</span>
                    @else
                        <span class="badge danger">Hết hàng</span>
                    @endif
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.products.edit', $product) }}" class="action-link">Sửa</a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có dữ liệu sản phẩm nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $products->links() }}</div>
@endsection
