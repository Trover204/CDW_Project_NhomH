@extends('layouts.admin')
@section('title', 'Quản lý Plans')

@section('content')
<div class="topbar">
    <h1>Quản lý Plans</h1>
    <div class="right">
        <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm Plan
        </a>
    </div>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Thời lượng (ngày)</th>
                <th>Giá</th>
                <th>Khuyến mãi (%)</th>
                <th>Trạng thái</th>
                <th style="width: 130px; text-align: right;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($plans as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td style="font-weight: 600; color: var(--text);">{{ $p->name }}</td>
                <td>{{ $p->duration_days }}</td>
                <td>{{ number_format($p->price, 0, ',', '.') }} VNĐ</td>
                <td>{{ $p->discount_percent }}%</td>
                <td>
                    <span class="badge {{ $p->status ? 'success' : 'danger' }}">
                        {{ $p->status ? 'Kích hoạt' : 'Vô hiệu hóa' }}
                    </span>
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="{{ route('admin.plans.edit', $p) }}" class="action-link">Sửa</a>
                    <form method="POST" action="{{ route('admin.plans.destroy', $p) }}"
                          style="display:inline" data-confirm="Xóa plan này?">
                        @csrf @method('DELETE')
                        <button type="submit" class="action-btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có plan nào.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">{{ $plans->links() }}</div>
@endsection
