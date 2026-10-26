<div class="form-group">
    <label class="form-label">Tiêu đề *</label>
    <input type="text" name="title" value="{{ old('title', $news->title ?? '') }}" class="form-control" placeholder="Nhập tiêu đề tin tức">
    @error('title') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Slug</label>
    <input type="text" name="slug" value="{{ old('slug', $news->slug ?? '') }}" class="form-control" placeholder="Để trống sẽ tự tạo từ tiêu đề">
    @error('slug') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Danh mục</label>
    <input type="text" name="category" value="{{ old('category', $news->category ?? '') }}" class="form-control" placeholder="Nhập danh mục tin tức">
    @error('category') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Hình ảnh</label>
    <input type="file" name="image" class="form-control">
    @if(isset($news) && $news->image)
        <div style="margin-top: 10px;">
            <img src="{{ $news->image_url }}" alt="image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px;">
        </div>
    @endif
    @error('image') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Tóm tắt</label>
    <textarea name="summary" rows="3" class="form-control" placeholder="Tóm tắt ngắn gọn tin tức">{{ old('summary', $news->summary ?? '') }}</textarea>
    @error('summary') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Nội dung *</label>
    <textarea name="content" rows="8" class="form-control" placeholder="Nội dung chi tiết của tin tức">{{ old('content', $news->content ?? '') }}</textarea>
    @error('content') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Trạng thái *</label>
    <select name="status" class="form-control">
        <option value="draft" @selected(old('status', $news->status ?? 'draft') === 'draft')>Bản nháp</option>
        <option value="published" @selected(old('status', $news->status ?? '') === 'published')>Đã xuất bản</option>
    </select>
    @error('status') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Lưu thông tin</button>
    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Hủy</a>
</div>
