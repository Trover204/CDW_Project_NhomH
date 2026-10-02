@php $label = 'display:block;font-size:12px;font-weight:600;color:#16241C;margin-bottom:6px;'; @endphp
@php $input = 'width:100%;box-sizing:border-box;padding:9px 10px;border:0.5px solid #C7D3CA;border-radius:8px;font-size:13px;'; @endphp

<div style="margin-bottom:14px;">
    <label style="{{ $label }}">Tên loại môn *</label>
    <input type="text" name="name" value="{{ old('name', $sportType->name ?? '') }}" style="{{ $input }}">
    @error('name') <div style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
</div>

<div style="margin-bottom:14px;">
    <label style="{{ $label }}">Mô tả</label>
    <textarea name="description" rows="3" style="{{ $input }}">{{ old('description', $sportType->description ?? '') }}</textarea>
    @error('description') <div style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
</div>

<div style="margin-bottom:14px;">
    <label style="{{ $label }}">Ảnh</label>
    @if (!empty($sportType->image))
        <img src="{{ asset('storage/'.$sportType->image) }}" alt="" style="width:80px;height:80px;object-fit:cover;border-radius:8px;display:block;margin-bottom:8px;">
    @endif
    <input type="file" name="image" accept="image/*">
    @error('image') <div style="color:#A32D2D;font-size:12px;margin-top:4px;">{{ $message }}</div> @enderror
</div>

<div style="margin-bottom:18px;">
    <label style="font-size:13px;color:#16241C;">
        <input type="checkbox" name="status" value="1" {{ old('status', $sportType->status ?? true) ? 'checked' : '' }}>
        Đang hoạt động
    </label>
</div>

<button type="submit" style="background:#178A4C;color:#fff;border:0;padding:9px 18px;border-radius:8px;font-size:13px;cursor:pointer;">Lưu</button>
<a href="{{ route('admin.sport-types.index') }}" style="margin-left:10px;color:#6B7A70;font-size:13px;text-decoration:none;">Hủy</a>