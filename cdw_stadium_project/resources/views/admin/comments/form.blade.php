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
            <option value="{{ $u->id }}" @selected(old('user_id', $comment->user_id ?? '') == $u->id)>
                {{ $u->name }} ({{ $u->email ?? '' }})
            </option>
        @endforeach
    </select>
    @error('user_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Bài viết tin tức *</label>
    <select name="news_id" class="form-control" required>
        <option value="">-- Chọn bài viết tin tức --</option>
        @foreach ($newsList ?? [] as $n)
            <option value="{{ $n->id }}" @selected(old('news_id', $comment->news_id ?? request('news_id', '')) == $n->id)>
                {{ $n->title }}
            </option>
        @endforeach
    </select>
    @error('news_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Nội dung bình luận / đánh giá *</label>
    <textarea name="content" rows="4" class="form-control" required placeholder="Nhập nội dung bình luận hoặc đánh giá cho bài viết tin tức...">{{ old('content', $comment->content ?? '') }}</textarea>
    @error('content') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Đánh giá số sao (1-5)</label>
    <select name="rating" class="form-control" style="max-width: 220px;">
        @foreach ([5 => '5 sao - Rất tuyệt vời', 4 => '4 sao - Hữu ích', 3 => '3 sao - Bình thường', 2 => '2 sao - Chưa tốt', 1 => '1 sao - Không hài lòng'] as $star => $label)
            <option value="{{ $star }}" @selected((int) old('rating', $comment->rating ?? 5) === $star)>★ {{ $label }}</option>
        @endforeach
    </select>
    @error('rating') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Trạng thái kiểm duyệt *</label>
    @php
        $currentStatus = old('status', isset($comment) ? ($comment->status ? '1' : '0') : '1');
        if ($currentStatus === 'approved' || $currentStatus === true || $currentStatus === 1) {
            $currentStatus = '1';
        } elseif ($currentStatus === 'pending' || $currentStatus === 'hidden' || $currentStatus === false || $currentStatus === 0) {
            $currentStatus = '0';
        }
    @endphp
    <select name="status" class="form-control" style="max-width: 260px;">
        <option value="1" @selected((string) $currentStatus === '1')>Đã duyệt (Hiển thị ngoài web)</option>
        <option value="0" @selected((string) $currentStatus === '0')>Ẩn bình luận</option>
    </select>
    @error('status') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Lưu bình luận</button>
    <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">Hủy</a>
</div>