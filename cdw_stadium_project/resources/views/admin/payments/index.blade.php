@extends('layouts.admin')
@section('title', 'Quản lý Thanh toán')

@section('content')
<div class="topbar">
    <h1>Quản lý Thanh toán</h1>
    <div class="right">
        <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm thanh toán
        </a>
    </div>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Người dùng</th>
                <th>Số tiền</th>
                <th>Phương thức</th>
                <th>Trạng thái</th>
                <th style="text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($payments as $p)
            <tr>
                <td style="font-weight: 600; color: var(--text);">#{{ $p->booking_id }}</td>
                <td>{{ $p->user->name ?? '—' }}</td>
                <td>{{ number_format($p->amount) }} đ</td>
                <td>{{ $p->payment_method }}</td>
                <td>
                    <span class="badge {{ ['paid'=>'success','pending'=>'warning','failed'=>'danger','refunded'=>'info'][$p->status] ?? 'info' }}">
                        {{ ['paid'=>'Đã thanh toán','pending'=>'Chờ xử lý','failed'=>'Thất bại','refunded'=>'Đã hoàn tiền'][$p->status] ?? $p->status }}
                    </span>
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.payments.edit', $p) }}" class="action-link">Sửa</a>
                    <form method="POST" action="{{ route('admin.payments.destroy', $p) }}" style="display:inline" onsubmit="return confirm('Xóa thanh toán này?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có thanh toán nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $payments->links() }}</div>
@endsection
