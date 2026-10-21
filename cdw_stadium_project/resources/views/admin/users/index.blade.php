@extends('layouts.admin')
@section('title', 'Quản lý người dùng')

@section('content')
<div class="topbar">
    <h1>Quản lý người dùng</h1>
    <div class="right">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm người dùng
        </a>
    </div>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 50px;">ID</th>
                <th>Tên người dùng</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Vai trò</th>
                <th style="width: 120px;">Trạng thái</th>
                <th style="width: 130px; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td style="font-weight: 600; color: var(--text);">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone ?? '—' }}</td>
                <td>
                    <span class="badge {{ ['admin'=>'danger','staff'=>'warning','ctv'=>'success','pending_ctv'=>'warning','customer'=>'info'][$user->role] ?? 'info' }}">
                        {{ ['admin'=>'Quản trị viên','staff'=>'Nhân viên','ctv'=>'Cộng tác viên','pending_ctv'=>'Chờ duyệt CTV','customer'=>'Khách hàng'][$user->role] ?? $user->role }}
                    </span>
                </td>
                <td>
                    <span class="badge {{ $user->status ? 'success' : 'danger' }}">
                        {{ $user->status ? 'Hoạt động' : 'Đã khóa' }}
                    </span>
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.users.edit', $user) }}" class="action-link">Sửa</a>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có người dùng nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $users->links() }}</div>
@endsection
