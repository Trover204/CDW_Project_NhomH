@php $label = 'display:block;font-size:12px;font-weight:600;color:#16241C;margin-bottom:6px;'; @endphp
@php $input = 'width:100%;box-sizing:border-box;padding:9px 10px;border:0.5px solid #C7D3CA;border-radius:8px;font-size:13px;'; @endphp

<div style="margin-bottom:14px;">
    <label style="{{ $label }}">Tên cơ sở *</label>
    <input type="text" name="name" value="{{ old('name', $facility->name ?? '') }}" style="{{ $input }}" placeholder="Ví dụ: Sân Nhóm H - Thủ Đức">
    @error('name') <div style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
</div>

<div style="margin-bottom:14px;">
    <label style="{{ $label }}">Địa chỉ *</label>
    <input type="text" name="address" value="{{ old('address', $facility->address ?? '') }}" style="{{ $input }}" placeholder="Ví dụ: 53 Võ Văn Ngân, Thủ Đức, TP.HCM">
    @error('address') <div style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
</div>

<div style="margin-bottom:14px;">
    <label style="{{ $label }}">Số điện thoại</label>
    <input type="text" name="phone" value="{{ old('phone', $facility->phone ?? '') }}" style="{{ $input }}" placeholder="Ví dụ: 0281111001">
    @error('phone') <div style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
</div>

<div style="display:flex;gap:12px;margin-bottom:14px;">
    <div style="flex:1;">
        <label style="{{ $label }}">Giờ mở cửa *</label>
        <input type="time" name="open_time" value="{{ old('open_time', isset($facility) ? substr($facility->open_time, 0, 5) : '06:00') }}" style="{{ $input }}">
        @error('open_time') <div style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
    </div>
    <div style="flex:1;">
        <label style="{{ $label }}">Giờ đóng cửa *</label>
        <input type="time" name="close_time" value="{{ old('close_time', isset($facility) ? substr($facility->close_time, 0, 5) : '23:00') }}" style="{{ $input }}">
        @error('close_time') <div style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
    </div>
</div>

<div style="margin-bottom:18px;">
    <label style="font-size:13px;color:#16241C;">
        <input type="checkbox" name="status" value="1" {{ old('status', $facility->status ?? true) ? 'checked' : '' }}>
        Đang hoạt động
    </label>
</div>

<button type="submit" style="background:#178A4C;color:#fff;border:0;padding:9px 18px;border-radius:8px;font-size:13px;cursor:pointer;">Lưu</button>
<a href="{{ route('admin.facilities.index') }}" style="margin-left:10px;color:#6B7A70;font-size:13px;text-decoration:none;">Hủy</a>
