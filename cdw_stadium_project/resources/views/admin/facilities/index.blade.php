@extends('admin.layouts.app')
@section('title', 'Quản lý cơ sở')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
    <h2 style="margin:0;font-size:16px;color:#16241C;">Danh sách cơ sở</h2>
    <a href="{{ route('admin.facilities.create') }}"
       style="background:#178A4C;color:#fff;padding:8px 14px;border-radius:8px;font-size:13px;text-decoration:none;">
        + Thêm mới
    </a>
</div>

<div style="background:#fff;border:0.5px solid #E1E8E3;border-radius:10px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:13px;color:#3A453E;">
        <thead>
            <tr style="background:#F7FAF8;text-align:left;color:#6B7A70;font-size:12px;">
                <th style="padding:10px 12px;">#</th>
                <th style="padding:10px 12px;">Tên cơ sở</th>
                <th style="padding:10px 12px;">Địa chỉ</th>
                <th style="padding:10px 12px;">Hotline</th>
                <th style="padding:10px 12px;">Giờ mở cửa</th>
                <th style="padding:10px 12px;">Số sân</th>
                <th style="padding:10px 12px;">Trạng thái</th>
                <th style="padding:10px 12px;text-align:right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($facilities as $facility)
            <tr style="border-top:0.5px solid #E1E8E3;">
                <td style="padding:10px 12px;">{{ $facility->id }}</td>
                <td style="padding:10px 12px;font-weight:600;">{{ $facility->name }}</td>
                <td style="padding:10px 12px;">{{ $facility->address }}</td>
                <td style="padding:10px 12px;">{{ $facility->phone ?? '—' }}</td>
                <td style="padding:10px 12px;">{{ substr($facility->open_time, 0, 5) }} - {{ substr($facility->close_time, 0, 5) }}</td>
                <td style="padding:10px 12px;">{{ $facility->courts_count ?? 0 }} sân</td>
                <td style="padding:10px 12px;">
                    @if ($facility->status)
                        <span style="background:#E6F5EB;color:#0F5C33;padding:2px 8px;border-radius:20px;font-size:11px;">Hoạt động</span>
                    @else
                        <span style="background:#FCEBEB;color:#A32D2D;padding:2px 8px;border-radius:20px;font-size:11px;">Tạm đóng</span>
                    @endif
                </td>
                <td style="padding:10px 12px;text-align:right;white-space:nowrap;">
                    <a href="{{ route('admin.facilities.edit', $facility) }}" style="color:#178A4C;text-decoration:none;margin-right:10px;">Sửa</a>
                    <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Bạn có chắc muốn xóa cơ sở này?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none;border:0;color:#A32D2D;cursor:pointer;font-size:13px;">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8" style="padding:20px;text-align:center;color:#6B7A70;">Chưa có dữ liệu cơ sở nào.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:14px;">{{ $facilities->links() }}</div>
@endsection
