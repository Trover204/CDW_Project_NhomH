@csrf
@if ($errors->any())
    <div class="alert alert-danger">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="form-group">
    <label class="form-label">Booking ID *</label>
    <select name="booking_id" class="form-control">
        <option value="">-- Chọn đặt sân --</option>
        @foreach ($bookings as $b)
            <option value="{{ $b->id }}" @selected(old('booking_id', $payment->booking_id ?? '') == $b->id)>#{{ $b->id }} - {{ $b->customer_name }}</option>
        @endforeach
    </select>
    @error('booking_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Người dùng *</label>
    <select name="user_id" class="form-control">
        <option value="">-- Chọn người dùng --</option>
        @foreach ($users as $u)
            <option value="{{ $u->id }}" @selected(old('user_id', $payment->user_id ?? '') == $u->id)>{{ $u->name }}</option>
        @endforeach
    </select>
    @error('user_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Số tiền *</label>
    <input type="number" name="amount" class="form-control" value="{{ old('amount', $payment->amount ?? 0) }}">
    @error('amount') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Phương thức thanh toán *</label>
    <input type="text" name="payment_method" class="form-control" value="{{ old('payment_method', $payment->payment_method ?? 'cod') }}">
    @error('payment_method') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Mã giao dịch (Tùy chọn)</label>
    <input type="text" name="transaction_code" class="form-control" value="{{ old('transaction_code', $payment->transaction_code ?? '') }}">
    @error('transaction_code') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Thời gian thanh toán</label>
    <input type="datetime-local" name="paid_at" class="form-control" value="{{ old('paid_at', isset($payment->paid_at) ? $payment->paid_at->format('Y-m-d\TH:i') : '') }}">
    @error('paid_at') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Trạng thái *</label>
    <select name="status" class="form-control">
        @foreach (['pending'=>'Chờ xử lý', 'paid'=>'Đã thanh toán', 'failed'=>'Thất bại', 'refunded'=>'Đã hoàn tiền'] as $val => $text)
            <option value="{{ $val }}" @selected(old('status', $payment->status ?? 'pending') === $val)>{{ $text }}</option>
        @endforeach
    </select>
    @error('status') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Lưu Thanh toán</button>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">Hủy</a>
</div>
