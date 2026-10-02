@csrf

@if ($errors->any())
    <div class="card" style="color:#c0392b">
        <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<p>
    <label>Người dùng</label><br>
    <select name="user_id">
        @foreach ($users as $u)
            <option value="{{ $u->id }}" @selected(old('user_id', $comment->user_id ?? '') == $u->id)>
                {{ $u->name }}
            </option>
        @endforeach
    </select>
</p>

<p>
    <label>Sân</label><br>
    <select name="court_id">
        @foreach ($courts as $c)
            <option value="{{ $c->id }}" @selected(old('court_id', $comment->court_id ?? '') == $c->id)>
                {{ $c->name }}
            </option>
        @endforeach
    </select>
</p>

<p>
    <label>Nội dung</label><br>
    <textarea name="content" rows="4" style="width:100%">{{ old('content', $comment->content ?? '') }}</textarea>
</p>

<p>
    <label>Số sao (1-5)</label><br>
    <input type="number" name="rating" min="1" max="5" value="{{ old('rating', $comment->rating ?? '') }}">
</p>

<p>
    <label>Trạng thái</label><br>
    <select name="status">
        @foreach (['pending' => 'Chờ duyệt', 'approved' => 'Đã duyệt', 'hidden' => 'Ẩn'] as $val => $text)
            <option value="{{ $val }}" @selected(old('status', $comment->status ?? 'pending') === $val)>{{ $text }}</option>
        @endforeach
    </select>
</p>

<button type="submit">Lưu</button>
<a href="{{ route('admin.comments.index') }}">Hủy</a>