@extends('admin.layouts.app')
@section('title', 'Sửa quảng cáo')

@section('content')
<div class="topbar">
    <h1>Sửa quảng cáo</h1>
    <div class="right">
        <a href="{{ route('admin.ads.index') }}" class="btn btn-outline">Quay lại</a>
    </div>
</div>

<div class="form-card">
    <form action="{{ route('admin.ads.update', $ad) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Tên quảng cáo <span style="color:red">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $ad->name) }}" required>
            @error('name') <div class="form-text text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Đường dẫn (Link)</label>
            <input type="url" name="link_url" class="form-control" value="{{ old('link_url', $ad->link_url) }}">
            @error('link_url') <div class="form-text text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Mô tả / Ghi chú</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $ad->description) }}</textarea>
            @error('description') <div class="form-text text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Hình ảnh hiện tại</label>
            <div>
                @if ($ad->image)
                    <img src="{{ Storage::url($ad->image) }}" alt="image" style="width: 150px; border-radius: 4px; border: 1px solid #ddd; margin-bottom: 12px;">
                @else
                    <span style="color: var(--muted); font-size: 14px;">Không có</span>
                @endif
            </div>

            <label class="form-label">Tải ảnh mới (Bỏ trống nếu không đổi)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @error('image') <div class="form-text text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $ad->is_active) ? 'checked' : '' }}>
                Kích hoạt (Hiển thị)
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật quảng cáo</button>
    </form>
</div>
@endsection
