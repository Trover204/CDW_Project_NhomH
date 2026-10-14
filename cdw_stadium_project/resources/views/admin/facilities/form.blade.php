<div class="form-group">
    <label class="form-label">Tên cơ sở *</label>
    <input type="text" name="name" value="{{ old('name', $facility->name ?? '') }}" class="form-control" placeholder="Ví dụ: Sân Nhóm H - Thủ Đức">
    @error('name') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Địa chỉ *</label>
    <input type="text" name="address" value="{{ old('address', $facility->address ?? '') }}" class="form-control" placeholder="Ví dụ: 53 Võ Văn Ngân, TP. Thủ Đức, TP.HCM">
    @error('address') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div class="form-group">
    <label class="form-label">Số điện thoại liên hệ</label>
    <input type="text" name="phone" value="{{ old('phone', $facility->phone ?? '') }}" class="form-control" placeholder="Ví dụ: 0281111001">
    @error('phone') <div class="form-error">{{ $message }}</div> @enderror
</div>

<div style="display: flex; gap: 14px; margin-bottom: 16px;">
    <div style="flex: 1;">
        <label class="form-label">Giờ mở cửa *</label>
        <input type="time" name="open_time" value="{{ old('open_time', isset($facility) ? substr($facility->open_time, 0, 5) : '06:00') }}" class="form-control">
        @error('open_time') <div class="form-error">{{ $message }}</div> @enderror
    </div>
    <div style="flex: 1;">
        <label class="form-label">Giờ đóng cửa *</label>
        <input type="time" name="close_time" value="{{ old('close_time', isset($facility) ? substr($facility->close_time, 0, 5) : '23:00') }}" class="form-control">
        @error('close_time') <div class="form-error">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-group">
    <label class="form-check">
        <input type="checkbox" name="status" value="1" {{ old('status', $facility->status ?? true) ? 'checked' : '' }}>
        <span>Đang hoạt động</span>
    </label>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">Lưu cơ sở</button>
    <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">Hủy</a>
</div>
