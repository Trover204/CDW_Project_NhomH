@csrf
<input type="hidden" name="version" value="{{ $review->version ?? 0 }}">

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
            <option value="{{ $u->id }}" @selected(old('user_id', $review->user_id ?? '') == $u->id)>
                {{ $u->name }} ({{ $u->email ?? '' }})
            </option>
        @endforeach
    </select>
    @error('user_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Sân thể thao *</label>
    <select name="court_id" class="form-control" required>
        <option value="">-- Chọn sân thể thao --</option>
        @foreach ($courts as $c)
            <option value="{{ $c->id }}" @selected(old('court_id', $review->court_id ?? '') == $c->id)>
                {{ $c->name }}
            </option>
        @endforeach
    </select>
    @error('court_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Đơn đặt sân liên kết (Tùy chọn)</label>
    <select name="booking_id" class="form-control">
        <option value="">-- Không liên kết đơn cụ thể --</option>
        @foreach ($bookings ?? [] as $b)
            <option value="{{ $b->id }}" @selected(old('booking_id', $review->booking_id ?? '') == $b->id)>
                #BK-{{ str_pad($b->id, 5, '0', STR_PAD_LEFT) }} — {{ $b->customer_name ?: ($b->user->name ?? 'Khách') }} ({{ $b->court->name ?? 'Sân' }})
            </option>
        @endforeach
    </select>
    @error('booking_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Nội dung đánh giá</label>
    <textarea name="content" rows="4" class="form-control" placeholder="Nhập nội dung nhận xét về sân thể thao...">{{ old('content', $review->content ?? '') }}</textarea>
    @error('content') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Đánh giá số sao (1-5) *</label>
    <select name="rating" class="form-control" style="max-width: 240px;">
        @foreach ([5 => '5 sao - Rất tuyệt vời', 4 => '4 sao - Tốt', 3 => '3 sao - Bình thường', 2 => '2 sao - Chưa tốt', 1 => '1 sao - Không hài lòng'] as $star => $label)
            <option value="{{ $star }}" @selected((int) old('rating', $review->rating ?? 5) === $star)>★ {{ $label }}</option>
        @endforeach
    </select>
    @error('rating') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Trạng thái hiển thị *</label>
    <select name="status" class="form-control" style="max-width: 260px;">
        <option value="1" @selected((int) old('status', $review->status ?? 1) === 1)>Hiển thị ngoài web</option>
        <option value="0" @selected((int) old('status', $review->status ?? 1) === 0)>Tạm ẩn</option>
    </select>
    @error('status') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Lưu đánh giá sân</button>
    <a href="{{ route('admin.comments.index', ['tab' => 'court-reviews']) }}" class="btn btn-secondary">Hủy</a>
</div>
