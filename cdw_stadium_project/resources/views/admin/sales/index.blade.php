@extends('layouts.admin')
@section('title', 'Quản lý Đơn bán hàng')

@section('content')
<div class="topbar">
    <h1>Quản lý Đơn bán hàng</h1>
    <div class="right">
        <a href="{{ route('admin.sales.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm đơn hàng
        </a>
    </div>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 110px;">Mã đơn</th>
                <th>Sản phẩm</th>
                <th>Khách hàng</th>
                <th style="width: 90px;">Số lượng</th>
                <th>Tổng tiền</th>
                <th>Thanh toán</th>
                <th style="width: 130px;">Trạng thái</th>
                <th style="width: 110px;">Ngày bán</th>
                <th style="width: 130px; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse($sales as $sale)
            <tr>
                <td style="font-weight: 600; color: var(--text);">
                    #SL-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}
                </td>
                <td style="font-weight: 600; color: var(--text);">
                    {{ $sale->product ? $sale->product->name : '—' }}
                </td>
                <td>
                    @if($sale->user)
                        <div style="font-weight: 600; color: var(--text);">{{ $sale->user->name }}</div>
                        @if($sale->customer_phone || $sale->user->phone)
                            <small style="color: var(--muted);">{{ $sale->customer_phone ?: $sale->user->phone }}</small>
                        @endif
                    @else
                        <div style="font-weight: 600; color: var(--text);">{{ $sale->customer_name ?? 'Khách lẻ' }}</div>
                        @if($sale->customer_phone)
                            <small style="color: var(--muted);">{{ $sale->customer_phone }}</small>
                        @endif
                    @endif
                </td>
                <td>{{ $sale->quantity }}</td>
                <td style="font-weight: 600; color: var(--text);">
                    {{ number_format($sale->total_price, 0, ',', '.') }} đ
                </td>
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
                    @php
                        $statusBadge = match($sale->status) {
                            'completed' => 'success',
                            'pending' => 'warning',
                            default => 'danger',
                        };
                        $statusLabel = match($sale->status) {
                            'completed' => 'Hoàn thành',
                            'pending' => 'Chờ xử lý',
                            'cancelled' => 'Đã hủy',
                            default => ucfirst($sale->status),
                        };
                    @endphp
                    <span class="badge {{ $statusBadge }}">
                        {{ $statusLabel }}
                    </span>
                </td>
                <td>{{ $sale->sale_date ? \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') : '—' }}</td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.sales.edit', $sale) }}" class="action-link">Sửa</a>
                    <form action="{{ route('admin.sales.destroy', $sale) }}" method="POST" style="display: inline;" data-confirm="Bạn có chắc chắn muốn xóa đơn hàng này?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có dữ liệu đơn hàng nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $sales->links() }}</div>
@endsection
