@extends('layouts.admin')
@section('title', 'Quản lý sân')

@section('content')
<div class="topbar">
    <h1>Danh sách sân thể thao</h1>
    <div class="right">
        <a href="{{ route('admin.courts.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm sân mới
        </a>
    </div>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th>Tên sân</th>
                <th>Cơ sở</th>
                <th>Loại môn</th>
                <th style="width: 100px;">Sức chứa</th>
                <th>Mô tả</th>
                <th style="width: 120px;">Trạng thái</th>
                <th style="width: 130px; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($courts as $court)
            <tr>
                <td>{{ $court->id }}</td>
                <td style="font-weight: 600; color: var(--text);">{{ $court->name }}</td>
                <td>{{ $court->facility->name ?? '—' }}</td>
                <td>{{ $court->sportType->name ?? '—' }}</td>
                <td>{{ $court->capacity }} người</td>
                <td>{{ Str::limit($court->description, 40) ?: '—' }}</td>
                <td>
                    @if ($court->status === 'available')
                        <span class="badge success">Sẵn sàng</span>
                    @elseif ($court->status === 'maintenance')
                        <span class="badge warning">Bảo trì</span>
                    @else
                        <span class="badge danger">Tạm ẩn</span>
                    @endif
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.courts.edit', $court) }}" class="action-link">Sửa</a>
                    <form action="{{ route('admin.courts.destroy', $court) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Bạn có chắc muốn xóa sân này?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có dữ liệu sân thể thao nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $courts->links() }}</div>
@endsection
