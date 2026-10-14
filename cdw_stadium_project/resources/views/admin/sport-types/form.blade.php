<div class="form-group">
    <label class="form-label">Tên loại môn *</label>
    <input type="text" name="name" value="{{ old('name', $sportType->name ?? '') }}" class="form-control" placeholder="Ví dụ: Bóng đá, Cầu lông, Pickleball">
    @error('name') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Mô tả</label>
    <textarea name="description" rows="3" class="form-control" placeholder="Thông tin tóm tắt về môn thể thao...">{{ old('description', $sportType->description ?? '') }}</textarea>
    @error('description') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Ảnh đại diện</label>
    @if (!empty($sportType->image))
        <img src="{{ asset('storage/'.$sportType->image) }}" alt="Preview" style="width:80px;height:80px;object-fit:cover;border-radius:8px;display:block;margin-bottom:10px;border:1px solid var(--border);">
    @endif
    <input type="file" name="image" accept="image/*" class="form-control" style="padding: 7px 10px;">
    @error('image') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-check">
        <input type="checkbox" name="status" value="1" {{ old('status', $sportType->status ?? true) ? 'checked' : '' }}>
        <span>Đang hoạt động</span>
    </label>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Lưu thông tin</button>
    <a href="{{ route('admin.sport-types.index') }}" class="btn btn-secondary">Hủy</a>
</div>