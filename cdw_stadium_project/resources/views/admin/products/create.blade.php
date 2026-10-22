@extends('admin.layouts.app')
@section('title', 'Thêm Sản Phẩm Mới')

@section('content')
<div class="topbar">
    <h1>Thêm Sản Phẩm Mới</h1>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label">Tên sản phẩm <span style="color:var(--danger)">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')<div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Đội / CLB</label>
            <input type="text" name="team" class="form-control" value="{{ old('team') }}">
            @error('team')<div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Mùa giải</label>
            <input type="text" name="season" class="form-control" value="{{ old('season') }}">
            @error('season')<div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>@enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Giá gốc (đ) <span style="color:var(--danger)">*</span></label>
                <input type="number" name="cost_price" class="form-control" value="{{ old('cost_price', 0) }}" required min="0" step="1000">
                @error('cost_price')<div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Số lượng tồn kho <span style="color:var(--danger)">*</span></label>
                <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', 0) }}" required min="0">
                @error('stock_quantity')<div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
            @error('description')<div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Ảnh sản phẩm</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            @error('image')<div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="display: flex; gap: 30px;">
            <label style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="is_in_stock" value="1" {{ old('is_in_stock', true) ? 'checked' : '' }}>
                <span>Còn hàng</span>
            </label>
            <label style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <span>Hoạt động (Hiển thị)</span>
            </label>
        </div>

        <div style="margin-top: 24px;">
            <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
            <a href="{{ route('admin.products.index') }}" class="btn" style="margin-left: 10px;">Hủy</a>
        </div>
    </form>
</div>
@endsection
