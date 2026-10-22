@extends('admin.layouts.app')
@section('title', 'Thêm quảng cáo')

@section('content')
<div class="topbar">
    <h1>Thêm quảng cáo</h1>
    <div class="right">
        <a href="{{ route('admin.ads.index') }}" class="btn btn-outline">Quay lại</a>
    </div>
</div>

<div class="form-card">
    <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">Tên quảng cáo <span style="color:red">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <div class="form-text text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Đường dẫn (Link)</label>
            <input type="url" name="link_url" class="form-control" value="{{ old('link_url') }}">
            @error('link_url') <div class="form-text text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Mô tả / Ghi chú</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            @error('description') <div class="form-text text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Hình ảnh</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @error('image') <div class="form-text text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label class="form-label" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                Kích hoạt (Hiển thị)
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Lưu quảng cáo</button>
    </form>
</div>
@endsection
