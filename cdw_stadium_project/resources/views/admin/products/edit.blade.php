@extends('admin.layouts.app')
@section('title', 'Cập nhật Sản Phẩm')

@section('content')
<div class="topbar">
    <h1>Cập nhật Sản Phẩm: {{ $product->name }}</h1>
</div>

<div class="card" style="max-width: 800px;">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Tên sản phẩm <span style="color:var(--danger)">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            @error('name')<div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Đội / CLB</label>
            <input type="text" name="team" class="form-control" value="{{ old('team', $product->team) }}">
            @error('team')<div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Mùa giải</label>
            <input type="text" name="season" class="form-control" value="{{ old('season', $product->season) }}">
            @error('season')<div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>@enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="form-group">
                <label class="form-label">Giá gốc (đ) <span style="color:var(--danger)">*</span></label>
                <input type="number" name="cost_price" class="form-control" value="{{ old('cost_price', $product->cost_price) }}" required min="0" step="1000">
                @error('cost_price')<div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Số lượng tồn kho <span style="color:var(--danger)">*</span></label>
                <input type="number" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', $product->stock_quantity) }}" required min="0">
                @error('stock_quantity')<div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
            @error('description')<div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">Ảnh sản phẩm</label>
            @if($product->image)
                <div style="margin-bottom: 10px;">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Current Image" style="max-width: 200px; border-radius: 8px;">
                </div>
            @endif
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-muted">Để trống nếu không muốn thay đổi ảnh.</small>
            @error('image')<div class="text-danger" style="margin-top: 5px;">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" style="display: flex; gap: 30px;">
            <label style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="is_in_stock" value="1" {{ old('is_in_stock', $product->is_in_stock) ? 'checked' : '' }}>
                <span>Còn hàng</span>
            </label>
            <label style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                <span>Hoạt động (Hiển thị)</span>
            </label>
        </div>

        <div style="margin-top: 24px;">
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            <a href="{{ route('admin.products.index') }}" class="btn" style="margin-left: 10px;">Hủy</a>
        </div>
    </form>
</div>
@endsection
