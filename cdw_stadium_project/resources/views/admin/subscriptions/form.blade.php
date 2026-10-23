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
    <label class="form-label">Người dùng *</label>
    <select name="user_id" class="form-control" required>
        <option value="">-- Chọn người dùng --</option>
        @foreach ($users as $u)
            <option value="{{ $u->id }}" @selected(old('user_id', $subscription->user_id ?? '') == $u->id)>
                {{ $u->name }} ({{ $u->email ?? '' }})
            </option>
        @endforeach
    </select>
    @error('user_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Plan *</label>
    <select name="plan_id" class="form-control" required>
        <option value="">-- Chọn plan --</option>
        @foreach ($plans as $p)
            <option value="{{ $p->id }}" @selected(old('plan_id', $subscription->plan_id ?? '') == $p->id)>
                {{ $p->name }} @if(isset($p->price)) ({{ number_format($p->price, 0, ',', '.') }}đ) @endif
            </option>
        @endforeach
    </select>
    @error('plan_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Ngày bắt đầu *</label>
    <input type="datetime-local" name="starts_at" value="{{ old('starts_at', isset($subscription) ? $subscription->starts_at->format('Y-m-d\TH:i') : '') }}" class="form-control" required>
    @error('starts_at') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Ngày hết hạn *</label>
    <input type="datetime-local" name="expires_at" value="{{ old('expires_at', isset($subscription) ? $subscription->expires_at->format('Y-m-d\TH:i') : '') }}" class="form-control" required>
    @error('expires_at') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Giá gói đăng ký (VNĐ)</label>
    <input type="number" name="price" value="{{ old('price', isset($subscription) ? (int) $subscription->price : 0) }}" class="form-control" min="0" step="1">
    @error('price') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Phương thức thanh toán (đồng bộ sang Payment)</label>
    <select name="payment_method" class="form-control">
        @foreach (\App\Models\Payment::METHODS as $mKey => $mText)
            <option value="{{ $mKey }}" @selected(old('payment_method', $subscription->payment->payment_method ?? 'vnpay') === $mKey)>{{ $mText }}</option>
        @endforeach
    </select>
    @error('payment_method') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Mã giao dịch (Để trống sẽ tự sinh ở bảng Payment)</label>
    <input type="text" name="transaction_code" value="{{ old('transaction_code', $subscription->payment->transaction_code ?? '') }}" class="form-control">
    @error('transaction_code') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Trạng thái gói</label>
    <select name="status" class="form-control">
        <option value="1" @selected(old('status', $subscription->status ?? true) == true)>Đang hoạt động</option>
        <option value="0" @selected(old('status', $subscription->status ?? true) == false)>Hết hạn/Hủy</option>
    </select>
    @error('status') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">Hủy</a>
</div>
