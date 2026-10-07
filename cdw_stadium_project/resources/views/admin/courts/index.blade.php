@extends('admin.layouts.app')
@section('title', 'Quản lý sân')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
    <h2 style="margin:0;font-size:16px;color:#16241C;">Danh sách sân thể thao</h2>
    <a href="{{ route('admin.courts.create') }}"
       style="background:#178A4C;color:#fff;padding:8px 14px;border-radius:8px;font-size:13px;text-decoration:none;">
        + Thêm mới
    </a>
</div>

<div style="background:#fff;border:0.5px solid #E1E8E3;border-radius:10px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:13px;color:#3A453E;">
        <thead>
            <tr style="background:#F7FAF8;text-align:left;color:#6B7A70;font-size:12px;">
                <th style="padding:10px 12px;">#</th>
                <th style="padding:10px 12px;">Tên sân</th>
                <th style="padding:10px 12px;">Cơ sở</th>
                <th style="padding:10px 12px;">Loại môn</th>
                <th style="padding:10px 12px;">Sức chứa</th>
                <th style="padding:10px 12px;">Mô tả</th>
                <th style="padding:10px 12px;">Trạng thái</th>
                <th style="padding:10px 12px;text-align:right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($courts as $court)
            <tr style="border-top:0.5px solid #E1E8E3;">
                <td style="padding:10px 12px;">{{ $court->id }}</td>
                <td style="padding:10px 12px;font-weight:600;">{{ $court->name }}</td>
                <td style="padding:10px 12px;">{{ $court->facility->name ?? '—' }}</td>
                <td style="padding:10px 12px;">{{ $court->sportType->name ?? '—' }}</td>
                <td style="padding:10px 12px;">{{ $court->capacity }} người</td>
                <td style="padding:10px 12px;">{{ Str::limit($court->description, 40) ?? '—' }}</td>
                <td style="padding:10px 12px;">
                    @if ($court->status === 'available')
                        <span style="background:#E6F5EB;color:#0F5C33;padding:2px 8px;border-radius:20px;font-size:11px;">Sẵn sàng</span>
                    @elseif ($court->status === 'maintenance')
                        <span style="background:#FEF3C7;color:#B45309;padding:2px 8px;border-radius:20px;font-size:11px;">Bảo trì</span>
                    @else
                        <span style="background:#FCEBEB;color:#A32D2D;padding:2px 8px;border-radius:20px;font-size:11px;">Tạm ẩn</span>
                    @endif
                </td>
                <td style="padding:10px 12px;text-align:right;white-space:nowrap;">
                    <a href="{{ route('admin.courts.edit', $court) }}" style="color:#178A4C;text-decoration:none;margin-right:10px;">Sửa</a>
                    <form action="{{ route('admin.courts.destroy', $court) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Bạn có chắc muốn xóa sân này?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none;border:0;color:#A32D2D;cursor:pointer;font-size:13px;">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" style="padding:20px;text-align:center;color:#6B7A70;">Chưa có dữ liệu sân thể thao nào.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:14px;">{{ $courts->links() }}</div>
@endsection
