@extends('layouts.admin')
@section('title', 'Quản lý Đặt sân')

@section('content')
<div class="topbar">
    <h1>Quản lý Đặt sân</h1>
    <div class="right">
        <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm đặt sân
        </a>
    </div>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Khách hàng</th>
                <th>Sân</th>
                <th>Thời gian</th>
                <th>Tổng tiền</th>
                <th>Thanh toán</th>
                <th>Trạng thái</th>
                <th style="text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($bookings as $b)
            <tr>
                <td style="font-weight: 600; color: var(--text);">{{ $b->customer_name }} <br><small>{{ $b->customer_phone }}</small></td>
                <td>{{ $b->court->name ?? '—' }}</td>
                <td>{{ $b->booking_date }} <br><small>{{ $b->start_time }} - {{ $b->end_time }}</small></td>
                <td>{{ number_format($b->total_price) }} đ</td>
                <td>
                    @if ($b->payment)
                        <a href="{{ route('admin.payments.show', $b->payment) }}" style="text-decoration: none;">
                            <span class="badge {{ $b->payment->status_badge }}">
                                {{ $b->payment->method_label }}: {{ $b->payment->status_label }}
                            </span>
                        </a>
                    @else
                        <span class="badge warning">Chưa xuất TT</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ ['confirmed'=>'success','pending'=>'warning','cancelled'=>'danger', 'checked_in' => 'info', 'completed' => 'success', 'rejected' => 'danger'][$b->status] ?? 'info' }}">
                        {{ ['confirmed'=>'Đã xác nhận','pending'=>'Chờ xử lý','cancelled'=>'Đã hủy', 'checked_in' => 'Đã check-in', 'completed' => 'Hoàn thành', 'rejected' => 'Bị từ chối'][$b->status] ?? $b->status }}
                    </span>
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.bookings.edit', $b) }}" class="action-link">Sửa</a>
                    <form method="POST" action="{{ route('admin.bookings.destroy', $b) }}" style="display:inline" data-confirm="Xóa đặt sân này?">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có đặt sân nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $bookings->links() }}</div>
@endsection
