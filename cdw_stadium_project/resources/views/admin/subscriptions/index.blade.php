@extends('layouts.admin')
@section('title', 'Quản lý Subscriptions')

@section('content')
<div class="topbar">
    <h1>Quản lý Subscriptions</h1>
    <div class="right">
        <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm Subscription
        </a>
    </div>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Người dùng</th>
                <th>Plan</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày hết hạn</th>
                <th>Giá gói</th>
                <th>Thanh toán</th>
                <th>Trạng thái</th>
                <th style="width: 130px; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($subscriptions as $s)
            <tr>
                <td>#SUB-{{ str_pad($s->id, 5, '0', STR_PAD_LEFT) }}</td>
                <td style="font-weight: 600; color: var(--text);">{{ $s->user->name ?? '—' }}</td>
                <td>{{ $s->plan->name ?? '—' }}</td>
                <td>{{ $s->starts_at->format('d/m/Y H:i') }}</td>
                <td>{{ $s->expires_at->format('d/m/Y H:i') }}</td>
                <td>{{ number_format($s->price, 0, ',', '.') }} VNĐ</td>
                <td>
                    @if ($s->payment)
                        <a href="{{ route('admin.payments.show', $s->payment) }}" style="text-decoration: none;">
                            <span class="badge {{ $s->payment->status_badge }}">
                                {{ $s->payment->method_label }}: {{ $s->payment->status_label }}
                            </span>
                        </a>
                    @else
                        <span class="badge warning">Chưa xuất TT</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $s->status ? 'success' : 'danger' }}">
                        {{ $s->status ? 'Đang hoạt động' : 'Hết hạn/Hủy' }}
                    </span>
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.subscriptions.edit', $s) }}" class="action-link">Sửa</a>
                    <form method="POST" action="{{ route('admin.subscriptions.destroy', $s) }}"
                          style="display:inline" onsubmit="return confirm('Xóa subscription này?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có subscription nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $subscriptions->links() }}</div>
@endsection
