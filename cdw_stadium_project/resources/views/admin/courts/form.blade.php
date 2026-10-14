<div class="form-group">
    <label class="form-label">Cơ sở *</label>
    <select name="facility_id" class="form-control">
        <option value="">-- Chọn cơ sở --</option>
        @foreach ($facilities as $fac)
            <option value="{{ $fac->id }}" @selected(old('facility_id', $court->facility_id ?? '') == $fac->id)>
                {{ $fac->name }} ({{ $fac->address }})
            </option>
        @endforeach
    </select>
    @error('facility_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Loại môn thể thao *</label>
    <select name="sport_type_id" class="form-control">
        <option value="">-- Chọn loại môn --</option>
        @foreach ($sportTypes as $st)
            <option value="{{ $st->id }}" @selected(old('sport_type_id', $court->sport_type_id ?? '') == $st->id)>
                {{ $st->name }}
            </option>
        @endforeach
    </select>
    @error('sport_type_id') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Tên sân *</label>
    <input type="text" name="name" value="{{ old('name', $court->name ?? '') }}" class="form-control" placeholder="Ví dụ: Sân bóng mini 1, Sân Pickleball VIP 2">
    @error('name') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Sức chứa (người) *</label>
    <input type="number" name="capacity" min="1" value="{{ old('capacity', $court->capacity ?? 4) }}" class="form-control" style="max-width: 180px;">
    @error('capacity') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Mô tả tiện ích</label>
    <textarea name="description" rows="3" class="form-control" placeholder="Mặt sân cỏ nhân tạo, hệ thống chiếu sáng LED, mái che...">{{ old('description', $court->description ?? '') }}</textarea>
    @error('description') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Trạng thái hoạt động *</label>
    <select name="status" class="form-control">
        <option value="available" @selected(old('status', $court->status ?? 'available') === 'available')>Sẵn sàng / Hoạt động</option>
        <option value="maintenance" @selected(old('status', $court->status ?? '') === 'maintenance')>Bảo trì</option>
        <option value="inactive" @selected(old('status', $court->status ?? '') === 'inactive')>Tạm dừng / Ẩn</option>
    </select>
    @error('status') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Lưu thông tin sân</button>
    <a href="{{ route('admin.courts.index') }}" class="btn btn-secondary">Hủy</a>
</div>
