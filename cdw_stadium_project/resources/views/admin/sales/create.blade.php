@extends('layouts.admin')
@section('title', 'Thêm Đơn bán hàng')

@section('content')
<div class="topbar">
    <h1>Thêm Đơn hàng mới</h1>
    <div class="right">
        <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card" style="max-width: 720px;">
    <form action="{{ route('admin.sales.store') }}" method="POST">
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
            <label class="form-label">Sản phẩm <span style="color: var(--danger);">*</span></label>
            <select name="product_id" class="form-control" required>
                <option value="">-- Chọn sản phẩm --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                        {{ $product->name }} (Tồn: {{ $product->stock_quantity }})
                    </option>
                @endforeach
            </select>
            @error('product_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Tài khoản thành viên (nếu có)</label>
            <select name="user_id" class="form-control">
                <option value="">-- Khách vãng lai --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id') == $user->id)>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            @error('user_id') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Tên khách hàng (với khách lẻ)</label>
                <input type="text" name="customer_name" class="form-control" value="{{ old('customer_name') }}" placeholder="Nhập tên khách hàng">
                @error('customer_name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">SĐT khách hàng</label>
                <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone') }}" placeholder="Nhập số điện thoại">
                @error('customer_phone') <div class="form-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-grid-3">
            <div class="form-group">
                <label class="form-label">Số lượng <span style="color: var(--danger);">*</span></label>
                <input type="number" name="quantity" class="form-control" value="{{ old('quantity', 1) }}" min="1" required>
                @error('quantity') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tổng tiền (VNĐ) <span style="color: var(--danger);">*</span></label>
                <input type="number" step="1" name="total_price" class="form-control" value="{{ old('total_price', 0) }}" min="0" required>
                @error('total_price') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Ngày bán <span style="color: var(--danger);">*</span></label>
                <input type="date" name="sale_date" class="form-control" value="{{ old('sale_date', now()->toDateString()) }}">
                @error('sale_date') <div class="form-error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Trạng thái <span style="color: var(--danger);">*</span></label>
            <select name="status" class="form-control" required>
                <option value="pending" @selected(old('status') === 'pending')>Chờ xử lý</option>
                <option value="completed" @selected(old('status', 'completed') === 'completed')>Hoàn thành</option>
                <option value="cancelled" @selected(old('status') === 'cancelled')>Đã hủy</option>
            </select>
            @error('status') <div class="form-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Lưu đơn hàng</button>
            <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
