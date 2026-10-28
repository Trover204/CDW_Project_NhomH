@extends('layouts.admin')
@section('title', 'Quản lý bình luận')

@section('content')
<div class="topbar">
    <h1>Quản lý bình luận</h1>
    <div class="right">
        <a href="{{ route('admin.comments.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm bình luận
        </a>
    </div>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 140px;">Người dùng</th>
                <th style="width: 150px;">Sân</th>
                <th>Nội dung</th>
                <th style="width: 100px;">Đánh giá</th>
                <th style="width: 120px;">Trạng thái</th>
                <th style="width: 130px; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($comments as $c)
            <tr>
                <td style="font-weight: 600; color: var(--text);">{{ $c->user->name ?? '—' }}</td>
                <td>{{ $c->court->name ?? '—' }}</td>
                <td>{{ \Illuminate\Support\Str::limit($c->content, 60) }}</td>
                <td>
                    <span style="color: #F59E0B; font-weight: 600;">
                        ★ {{ $c->rating ?? '—' }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ ['approved'=>'success','pending'=>'warning','hidden'=>'danger'][$c->status] ?? 'info' }}">
                        {{ ['approved'=>'Đã duyệt','pending'=>'Chờ duyệt','hidden'=>'Ẩn'][$c->status] ?? $c->status }}
                    </span>
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.comments.edit', $c) }}" class="action-link">Sửa</a>
                    <form method="POST" action="{{ route('admin.comments.destroy', $c) }}"
                          style="display:inline" data-confirm="Xóa bình luận này?">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có bình luận nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $comments->links() }}</div>
@endsection