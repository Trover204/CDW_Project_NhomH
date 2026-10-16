@extends('layouts.admin')
@section('title', 'Quản lý tin tức')

@section('content')
<div class="topbar">
    <h1>Danh sách tin tức</h1>
    <div class="right">
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm tin tức mới
        </a>
    </div>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 100px;">Hình ảnh</th>
                <th>Tiêu đề</th>
                <th>Danh mục</th>
                <th>Người đăng</th>
                <th>Trạng thái</th>
                <th style="width: 130px; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($news as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>
                    @if ($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="image" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                    @else
                        <span style="color: var(--muted); font-size: 12px;">Không có</span>
                    @endif
                </td>
                <td style="font-weight: 600; color: var(--text);">{{ $item->title }}</td>
                <td>{{ $item->category ?? '—' }}</td>
                <td>{{ $item->user->name ?? '—' }}</td>
                <td>
                    @if ($item->status === 'published')
                        <span class="badge success">Đã xuất bản</span>
                    @else
                        <span class="badge warning">Bản nháp</span>
                    @endif
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.news.edit', $item) }}" class="action-link">Sửa</a>
                    <form action="{{ route('admin.news.destroy', $item) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Bạn có chắc muốn xóa tin tức này?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có tin tức nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $news->links() }}</div>
@endsection
