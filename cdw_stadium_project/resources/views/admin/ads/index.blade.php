@extends('admin.layouts.app')
@section('title', 'Quản lý quảng cáo')

@section('content')
<div class="topbar">
    <h1>Danh sách quảng cáo</h1>
    <div class="right">
        <a href="{{ route('admin.ads.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm quảng cáo mới
        </a>
    </div>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 100px;">Hình ảnh</th>
                <th>Tên quảng cáo</th>
                <th>Đường dẫn (Link)</th>
                <th>Trạng thái</th>
                <th style="width: 130px; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($ads as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>
                    @if ($item->image)
                        <img src="{{ Storage::url($item->image) }}" alt="image" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                    @else
                        <span style="color: var(--muted); font-size: 12px;">Không có</span>
                    @endif
                </td>
                <td style="font-weight: 600; color: var(--text);">{{ $item->name }}</td>
                <td>
                    @if($item->link_url)
                        <a href="{{ $item->link_url }}" target="_blank" style="color: var(--primary);">{{ Str::limit($item->link_url, 30) }}</a>
                    @else
                        —
                    @endif
                </td>
                <td>
                    @if ($item->is_active)
                        <span class="badge success">Hoạt động</span>
                    @else
                        <span class="badge warning">Ẩn</span>
                    @endif
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.ads.edit', $item) }}" class="action-link">Sửa</a>
                    <form action="{{ route('admin.ads.destroy', $item) }}" method="POST" style="display:inline;"
                          data-confirm="Bạn có chắc muốn xóa quảng cáo này?">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có quảng cáo nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $ads->links() }}</div>
@endsection
