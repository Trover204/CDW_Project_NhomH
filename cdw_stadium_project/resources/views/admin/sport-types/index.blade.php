@extends('layouts.admin')
@section('title', 'Loại môn thể thao')

@section('content')
<div class="topbar">
    <h1>Loại môn thể thao</h1>
    <div class="right">
        <a href="{{ route('admin.sport-types.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm mới
        </a>
    </div>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 70px;">Ảnh</th>
                <th>Tên loại môn</th>
                <th>Mô tả</th>
                <th style="width: 130px;">Trạng thái</th>
                <th style="width: 140px; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($sportTypes as $type)
            <tr>
                <td>{{ $type->id }}</td>
                <td>
                    @if ($type->image)
                        <img src="{{ asset('storage/'.$type->image) }}" alt="{{ $type->name }}" style="width:44px;height:44px;object-fit:cover;border-radius:8px;border:1px solid var(--border);">
                    @else
                        <span style="color:var(--muted);">—</span>
                    @endif
                </td>
                <td style="font-weight: 600; color: var(--text);">{{ $type->name }}</td>
                <td>{{ Str::limit($type->description, 50) ?: '—' }}</td>
                <td>
                    @if ($type->status)
                        <span class="badge success">Hoạt động</span>
                    @else
                        <span class="badge danger">Tạm ẩn</span>
                    @endif
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.sport-types.edit', $type) }}" class="action-link">Sửa</a>
                    <form action="{{ route('admin.sport-types.destroy', $type) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Xóa loại môn này?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có dữ liệu loại môn thể thao nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $sportTypes->links() }}</div>
@endsection