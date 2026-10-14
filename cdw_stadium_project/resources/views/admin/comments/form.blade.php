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
            <option value="{{ $u->id }}" @selected(old('user_id', $comment->user_id ?? '') == $u->id)>
                {{ $u->name }} ({{ $u->email ?? '' }})
            </option>
        @endforeach
    </select>
    @error('user_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Sân thể thao *</label>
    <select name="court_id" class="form-control">
        <option value="">-- Chọn sân --</option>
        @foreach ($courts as $c)
            <option value="{{ $c->id }}" @selected(old('court_id', $comment->court_id ?? '') == $c->id)>
                {{ $c->name }}
            </option>
        @endforeach
    </select>
    @error('court_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Nội dung bình luận *</label>
    <textarea name="content" rows="4" class="form-control" placeholder="Nhập nội dung đánh giá của khách hàng...">{{ old('content', $comment->content ?? '') }}</textarea>
    @error('content') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Đánh giá số sao (1-5)</label>
    <input type="number" name="rating" min="1" max="5" value="{{ old('rating', $comment->rating ?? 5) }}" class="form-control" style="max-width: 150px;">
    @error('rating') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Trạng thái kiểm duyệt *</label>
    <select name="status" class="form-control">
        @foreach (['pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'hidden' => 'Ẩn'] as $val => $text)
            <option value="{{ $val }}" @selected(old('status', $comment->status ?? 'pending') === $val)>{{ $text }}</option>
        @endforeach
    </select>
    @error('status') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Lưu bình luận</button>
    <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">Hủy</a>
</div>