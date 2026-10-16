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
    <select name="user_id" class="form-control">
        <option value="">-- Chọn người dùng --</option>
        @foreach ($users as $u)
            <option value="{{ $u->id }}" @selected(old('user_id', $booking->user_id ?? '') == $u->id)>{{ $u->name }} ({{ $u->email }})</option>
        @endforeach
    </select>
    @error('user_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Sân *</label>
    <select name="court_id" class="form-control">
        <option value="">-- Chọn sân --</option>
        @foreach ($courts as $c)
            <option value="{{ $c->id }}" @selected(old('court_id', $booking->court_id ?? '') == $c->id)>{{ $c->name }}</option>
        @endforeach
    </select>
    @error('court_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Gói đăng ký (Tùy chọn)</label>
    <select name="subscription_id" class="form-control">
        <option value="">-- Chọn gói --</option>
        @foreach ($subscriptions as $s)
            <option value="{{ $s->id }}" @selected(old('subscription_id', $booking->subscription_id ?? '') == $s->id)>ID: {{ $s->id }} - {{ $s->plan->name ?? '' }}</option>
        @endforeach
    </select>
    @error('subscription_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Tên khách hàng *</label>
    <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name', $booking->customer_name ?? '') }}">
    @error('customer_name') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Số điện thoại *</label>
    <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone', $booking->customer_phone ?? '') }}">
    @error('customer_phone') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Email *</label>
    <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email', $booking->customer_email ?? '') }}">
    @error('customer_email') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Ngày đặt *</label>
    <input type="date" name="booking_date" class="form-control" value="{{ old('booking_date', $booking->booking_date ?? '') }}">
    @error('booking_date') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Giờ bắt đầu *</label>
    <input type="time" name="start_time" class="form-control" value="{{ old('start_time', isset($booking->start_time) ? substr($booking->start_time, 0, 5) : '') }}">
    @error('start_time') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Giờ kết thúc *</label>
    <input type="time" name="end_time" class="form-control" value="{{ old('end_time', isset($booking->end_time) ? substr($booking->end_time, 0, 5) : '') }}">
    @error('end_time') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Tạm tính *</label>
    <input type="number" name="subtotal" class="form-control" value="{{ old('subtotal', $booking->subtotal ?? 0) }}">
    @error('subtotal') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Giảm giá *</label>
    <input type="number" name="discount_amount" class="form-control" value="{{ old('discount_amount', $booking->discount_amount ?? 0) }}">
    @error('discount_amount') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Tổng tiền *</label>
    <input type="number" name="total_price" class="form-control" value="{{ old('total_price', $booking->total_price ?? 0) }}">
    @error('total_price') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Ghi chú</label>
    <textarea name="note" rows="3" class="form-control">{{ old('note', $booking->note ?? '') }}</textarea>
    @error('note') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Trạng thái *</label>
    <select name="status" class="form-control">
        @foreach (['pending'=>'Chờ xử lý', 'confirmed'=>'Đã xác nhận', 'checked_in'=>'Đã check-in', 'completed'=>'Hoàn thành', 'cancelled'=>'Đã hủy', 'rejected'=>'Bị từ chối'] as $val => $text)
            <option value="{{ $val }}" @selected(old('status', $booking->status ?? 'pending') === $val)>{{ $text }}</option>
        @endforeach
    </select>
    @error('status') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Lưu Đặt sân</button>
    <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">Hủy</a>
</div>
