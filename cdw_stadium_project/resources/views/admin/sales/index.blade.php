@extends('admin.layouts.app')

@section('title', 'Quản lý Bán Hàng')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Danh sách Đơn hàng</h3>
        <a href="{{ route('admin.sales.create') }}" class="btn btn-primary"><i class="ti ti-plus"></i> Thêm Đơn hàng</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-vcenter">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sản phẩm</th>
                    <th>Khách hàng</th>
                    <th>Số lượng</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Ngày bán</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>#SL-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $sale->product ? $sale->product->name : 'N/A' }}</td>
                        <td>
                            @if($sale->user)
                                {{ $sale->user->name }}
                            @else
                                {{ $sale->customer_name ?? 'Khách lẻ' }}<br>
                                <small>{{ $sale->customer_phone }}</small>
                            @endif
                        </td>
                        <td>{{ $sale->quantity }}</td>
                        <td>{{ number_format($sale->total_price, 0, ',', '.') }}đ</td>
                        <td>
                            @if($sale->payment)
                                <a href="{{ route('admin.payments.show', $sale->payment) }}" style="text-decoration: none;">
                                    <span class="badge {{ $sale->payment->status_badge }}">
                                        {{ $sale->payment->method_label }}: {{ $sale->payment->status_label }}
                                    </span>
                                </a>
                            @else
                                <span class="badge warning">Chưa xuất TT</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $sale->status === 'completed' ? 'success' : ($sale->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($sale->status) }}
                            </span>
                        </td>
                        <td>{{ $sale->sale_date ? \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') : '' }}</td>
                        <td>
                            <a href="{{ route('admin.sales.edit', $sale) }}" class="btn btn-sm btn-outline-primary"><i class="ti ti-edit"></i> Sửa</a>
                            <form action="{{ route('admin.sales.destroy', $sale) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="ti ti-trash"></i> Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Không có dữ liệu</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($sales->hasPages())
        <div class="card-footer pb-0">
            {{ $sales->links() }}
        </div>
    @endif
</div>
@endsection
