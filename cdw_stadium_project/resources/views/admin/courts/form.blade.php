@php $label = 'display:block;font-size:12px;font-weight:600;color:#16241C;margin-bottom:6px;'; @endphp
@php $input = 'width:100%;box-sizing:border-box;padding:9px 10px;border:0.5px solid #C7D3CA;border-radius:8px;font-size:13px;'; @endphp

<div style="margin-bottom:14px;">
    <label style="{{ $label }}">Cơ sở *</label>
    <select name="facility_id" style="{{ $input }}">
        <option value="">-- Chọn cơ sở --</option>
        @foreach ($facilities as $fac)
            <option value="{{ $fac->id }}" @selected(old('facility_id', $court->facility_id ?? '') == $fac->id)>
                {{ $fac->name }} ({{ $fac->address }})
            </option>
        @endforeach
    </select>
    @error('facility_id') <div style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
</div>

<div style="margin-bottom:14px;">
    <label style="{{ $label }}">Loại môn thể thao *</label>
    <select name="sport_type_id" style="{{ $input }}">
        <option value="">-- Chọn loại môn --</option>
        @foreach ($sportTypes as $st)
            <option value="{{ $st->id }}" @selected(old('sport_type_id', $court->sport_type_id ?? '') == $st->id)>
                {{ $st->name }}
            </option>
        @endforeach
    </select>
    @error('sport_type_id') <div style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
</div>

<div style="margin-bottom:14px;">
    <label style="{{ $label }}">Tên sân *</label>
    <input type="text" name="name" value="{{ old('name', $court->name ?? '') }}" style="{{ $input }}" placeholder="Ví dụ: Sân bóng 1, Sân pickleball 2">
    @error('name') <div style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
</div>

<div style="margin-bottom:14px;">
    <label style="{{ $label }}">Sức chứa (người) *</label>
    <input type="number" name="capacity" min="1" value="{{ old('capacity', $court->capacity ?? 4) }}" style="{{ $input }}">
    @error('capacity') <div style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
</div>

<div style="margin-bottom:14px;">
    <label style="{{ $label }}">Mô tả</label>
    <textarea name="description" rows="3" style="{{ $input }}" placeholder="Tiện ích sân, mô tả chi tiết...">{{ old('description', $court->description ?? '') }}</textarea>
    @error('description') <div style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
</div>

<div style="margin-bottom:18px;">
    <label style="{{ $label }}">Trạng thái *</label>
    <select name="status" style="{{ $input }}">
        <option value="available" @selected(old('status', $court->status ?? 'available') === 'available')>Sẵn sàng / Hoạt động</option>
        <option value="maintenance" @selected(old('status', $court->status ?? '') === 'maintenance')>Bảo trì</option>
        <option value="inactive" @selected(old('status', $court->status ?? '') === 'inactive')>Tạm dừng / Ẩn</option>
    </select>
    @error('status') <div style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
</div>

<button type="submit" style="background:#178A4C;color:#fff;border:0;padding:9px 18px;border-radius:8px;font-size:13px;cursor:pointer;">Lưu</button>
<a href="{{ route('admin.courts.index') }}" style="margin-left:10px;color:#6B7A70;font-size:13px;text-decoration:none;">Hủy</a>
