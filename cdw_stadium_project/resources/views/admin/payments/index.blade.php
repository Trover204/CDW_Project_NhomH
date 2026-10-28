@extends('layouts.admin')
@section('title', 'Quản lý Thanh toán')

@section('content')
<div class="topbar">
    <div>
        <h1>Quản lý Thanh toán</h1>
        <small style="color: var(--muted); font-size: 12px;">Tập trung giao dịch thanh toán từ Đặt sân (Booking), Bán hàng (Sale) và Gói thành viên (Subscription)</small>
    </div>
    <div class="right">
        <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm thanh toán
        </a>
    </div>
</div>

{{-- Tổng quan nhanh theo từng phần --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 18px;">
    <div class="card" style="padding: 14px 18px;">
        <div style="font-size: 12px; color: var(--muted);">Tổng đã thu</div>
        <div style="font-size: 20px; font-weight: 700; color: var(--primary); margin-top: 4px;">
            {{ number_format($summary['total_paid'] ?? 0, 0, ',', '.') }} đ
        </div>
        <small style="color: var(--muted);">{{ $summary['total_count'] ?? 0 }} giao dịch ({{ $summary['pending_count'] ?? 0 }} chờ xử lý)</small>
    </div>
    <div class="card" style="padding: 14px 18px; border-left: 3px solid #178A4C;">
        <div style="font-size: 12px; color: var(--muted);">Thu từ Đặt sân</div>
        <div style="font-size: 18px; font-weight: 700; color: var(--text); margin-top: 4px;">
            {{ number_format($summary['booking_paid'] ?? 0, 0, ',', '.') }} đ
        </div>
    </div>
    <div class="card" style="padding: 14px 18px; border-left: 3px solid #F59E0B;">
        <div style="font-size: 12px; color: var(--muted);">Thu từ Bán hàng</div>
        <div style="font-size: 18px; font-weight: 700; color: var(--text); margin-top: 4px;">
            {{ number_format($summary['sale_paid'] ?? 0, 0, ',', '.') }} đ
        </div>
    </div>
    <div class="card" style="padding: 14px 18px; border-left: 3px solid #3B82F6;">
        <div style="font-size: 12px; color: var(--muted);">Thu từ Gói thành viên</div>
        <div style="font-size: 18px; font-weight: 700; color: var(--text); margin-top: 4px;">
            {{ number_format($summary['subscription_paid'] ?? 0, 0, ',', '.') }} đ
        </div>
    </div>
</div>

{{-- Bộ lọc --}}
<div class="card" style="padding: 12px 16px; margin-bottom: 16px;">
    <form method="GET" action="{{ route('admin.payments.index') }}" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
        <select name="payable_type" class="form-control" style="width: auto; min-width: 160px;">
            <option value="">-- Tất cả nguồn --</option>
            @foreach (\App\Models\Payment::TYPES as $typeKey => $typeLabel)
                <option value="{{ $typeKey }}" @selected(request('payable_type') === $typeKey)>{{ $typeLabel }}</option>
            @endforeach
        </select>

        <select name="status" class="form-control" style="width: auto; min-width: 160px;">
            <option value="">-- Tất cả trạng thái --</option>
            @foreach (\App\Models\Payment::STATUSES as $stKey => $stLabel)
                <option value="{{ $stKey }}" @selected(request('status') === $stKey)>{{ $stLabel }}</option>
            @endforeach
        </select>

        <select name="payment_method" class="form-control" style="width: auto; min-width: 160px;">
            <option value="">-- Phương thức TT --</option>
            @foreach (\App\Models\Payment::METHODS as $mKey => $mLabel)
                <option value="{{ $mKey }}" @selected(request('payment_method') === $mKey)>{{ $mLabel }}</option>
            @endforeach
        </select>

        <input type="text" name="search" class="form-control" style="width: auto; min-width: 220px;"
               placeholder="Mã giao dịch / tên khách..." value="{{ request('search') }}">

        <button type="submit" class="btn btn-primary">Lọc</button>
        @if (request()->hasAny(['payable_type', 'status', 'payment_method', 'search']))
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">Đặt lại</a>
        @endif
    </form>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Mã GD</th>
                <th>Nguồn đơn</th>
                <th>Chi tiết đơn</th>
                <th>Khách hàng</th>
                <th style="text-align: right;">Số tiền</th>
                <th>Phương thức</th>
                <th>Trạng thái</th>
                <th>Thời gian TT</th>
                <th style="text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($payments as $p)
            <tr>
                <td style="font-weight: 600; color: var(--text);">
                    <a href="{{ route('admin.payments.show', $p) }}" style="color: var(--primary); text-decoration: none;">
                        {{ $p->transaction_code ?? ('#PAY-' . $p->id) }}
                    </a>
                </td>
                <td>
                    <span class="badge" style="background: {{ $p->type_color }}1a; color: {{ $p->type_color }};">
                        {{ $p->type_label }}
                    </span>
                    <div style="font-size: 12px; font-weight: 600; margin-top: 2px;">{{ $p->reference }}</div>
                </td>
                <td style="max-width: 240px;">{{ $p->description }}</td>
                <td>{{ $p->customer_name }}</td>
                <td style="text-align: right; font-weight: 700; color: var(--text);">{{ number_format($p->amount, 0, ',', '.') }} đ</td>
                <td>{{ $p->method_label }}</td>
                <td>
                    <span class="badge {{ $p->status_badge }}">
                        {{ $p->status_label }}
                    </span>
                </td>
                <td style="font-size: 12px; color: var(--muted);">
                    {{ $p->paid_at ? $p->paid_at->format('d/m/Y H:i') : '—' }}
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.payments.show', $p) }}" class="action-link">Xem</a>
                    <a href="{{ route('admin.payments.edit', $p) }}" class="action-link">Sửa</a>
                    <form method="POST" action="{{ route('admin.payments.destroy', $p) }}" style="display:inline" data-confirm="Xóa thanh toán này?">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có thanh toán nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $payments->links() }}</div>
@endsection
