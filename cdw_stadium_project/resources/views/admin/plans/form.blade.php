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
    <label class="form-label">Tên *</label>
    <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" class="form-control" required>
    @error('name') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Thời lượng (ngày) *</label>
    <input type="number" name="duration_days" value="{{ old('duration_days', $plan->duration_days ?? '') }}" class="form-control" required min="1">
    @error('duration_days') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Giá *</label>
    <input type="number" name="price" value="{{ old('price', $plan->price ?? '') }}" class="form-control" required min="0" step="any">
    @error('price') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Khuyến mãi (%)</label>
    <input type="number" name="discount_percent" value="{{ old('discount_percent', $plan->discount_percent ?? '0') }}" class="form-control" min="0" max="100">
    @error('discount_percent') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Trạng thái</label>
    <select name="status" class="form-control">
        <option value="1" @selected(old('status', $plan->status ?? true) == true)>Kích hoạt</option>
        <option value="0" @selected(old('status', $plan->status ?? true) == false)>Vô hiệu hóa</option>
    </select>
    @error('status') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">Hủy</a>
</div>
