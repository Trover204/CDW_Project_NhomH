@extends('layouts.admin')
@section('title', 'Chi tiết Thanh toán')

@section('content')
<div class="topbar">
    <div>
        <h1>Chi tiết Thanh toán {{ $payment->transaction_code ?? ('#PAY-' . $payment->id) }}</h1>
        <small style="color: var(--muted);">Thông tin chứng từ thanh toán và đơn nguồn liên kết</small>
    </div>
    <div class="right">
        <a href="{{ route('admin.payments.edit', $payment) }}" class="btn btn-primary">Sửa</a>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
</div>

<div class="table-card" style="padding: 24px;">
    <table class="admin-table">
        <tbody>
            <tr>
                <th style="width: 220px;">Mã giao dịch</th>
                <td><strong>{{ $payment->transaction_code ?? ('#PAY-' . $payment->id) }}</strong></td>
            </tr>
            <tr>
                <th>Loại nguồn</th>
                <td>
                    <span class="badge" style="background: {{ $payment->type_color }}1a; color: {{ $payment->type_color }};">
                        {{ $payment->type_label }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Mã đơn nguồn</th>
                <td><strong>{{ $payment->reference }}</strong> — {{ $payment->description }}</td>
            </tr>
            <tr>
                <th>Khách hàng</th>
                <td>{{ $payment->customer_name }} @if($payment->user) ({{ $payment->user->email }}) @endif</td>
            </tr>
            <tr>
                <th>Số tiền</th>
                <td style="font-size: 18px; font-weight: 700; color: var(--primary);">{{ number_format($payment->amount, 0, ',', '.') }} VNĐ</td>
            </tr>
            <tr>
                <th>Phương thức thanh toán</th>
                <td>{{ $payment->method_label }}</td>
            </tr>
            <tr>
                <th>Trạng thái</th>
                <td><span class="badge {{ $payment->status_badge }}">{{ $payment->status_label }}</span></td>
            </tr>
            <tr>
                <th>Thời gian thanh toán</th>
                <td>{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i:s') : 'Chưa thanh toán' }}</td>
            </tr>
            <tr>
                <th>Ghi chú</th>
                <td>{{ $payment->note ?: '—' }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
