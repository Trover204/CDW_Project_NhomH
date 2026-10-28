@extends('layouts.admin')
@section('title', 'Quản lý cơ sở')

@section('content')
<div class="topbar">
    <h1>Danh sách cơ sở</h1>
    <div class="right">
        <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm cơ sở mới
        </a>
    </div>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th>Tên cơ sở</th>
                <th>Địa chỉ</th>
                <th style="width: 120px;">Hotline</th>
                <th style="width: 140px;">Giờ mở cửa</th>
                <th style="width: 90px;">Số sân</th>
                <th style="width: 120px;">Trạng thái</th>
                <th style="width: 130px; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($facilities as $facility)
            <tr>
                <td>{{ $facility->id }}</td>
                <td style="font-weight: 600; color: var(--text);">{{ $facility->name }}</td>
                <td>{{ $facility->address }}</td>
                <td>{{ $facility->phone ?? '—' }}</td>
                <td>{{ substr($facility->open_time, 0, 5) }} - {{ substr($facility->close_time, 0, 5) }}</td>
                <td>
                    <span style="font-weight: 600; color: var(--primary);">
                        {{ $facility->courts_count ?? 0 }} sân
                    </span>
                </td>
                <td>
                    @if ($facility->status)
                        <span class="badge success">Hoạt động</span>
                    @else
                        <span class="badge danger">Tạm đóng</span>
                    @endif
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.facilities.edit', $facility) }}" class="action-link">Sửa</a>
                    <form action="{{ route('admin.facilities.destroy', $facility) }}" method="POST" style="display:inline;"
                          data-confirm="Bạn có chắc muốn xóa cơ sở này?">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có dữ liệu cơ sở nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $facilities->links() }}</div>
@endsection
