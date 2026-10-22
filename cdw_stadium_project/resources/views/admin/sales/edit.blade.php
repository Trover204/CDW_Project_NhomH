@extends('admin.layouts.app')

@section('title', 'Sửa Đơn hàng Bán')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Sửa Đơn hàng #{{ $sale->id }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.sales.update', $sale) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label required">Sản phẩm</label>
                <select name="product_id" class="form-select @error('product_id') is-invalid @enderror" required>
                    <option value="">-- Chọn sản phẩm --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id', $sale->product_id) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }}
                        </option>
                    @endforeach
                </select>
                @error('product_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Tài khoản thành viên (nếu có)</label>
                <select name="user_id" class="form-select @error('user_id') is-invalid @enderror">
                    <option value="">-- Khách vãng lai --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id', $sale->user_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Tên khách hàng</label>
                    <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" value="{{ old('customer_name', $sale->customer_name) }}">
                    @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">SĐT khách hàng</label>
                    <input type="text" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror" value="{{ old('customer_phone', $sale->customer_phone) }}">
                    @error('customer_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label required">Số lượng</label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $sale->quantity) }}" min="1" required>
                    @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Tổng tiền</label>
                    <input type="number" step="0.01" name="total_price" class="form-control @error('total_price') is-invalid @enderror" value="{{ old('total_price', $sale->total_price) }}" min="0" required>
                    @error('total_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label required">Ngày bán</label>
                    <input type="date" name="sale_date" class="form-control @error('sale_date') is-invalid @enderror" value="{{ old('sale_date', $sale->sale_date ? $sale->sale_date->toDateString() : '') }}">
                    @error('sale_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label required">Trạng thái</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="pending" {{ old('status', $sale->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ old('status', $sale->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status', $sale->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
            <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection
