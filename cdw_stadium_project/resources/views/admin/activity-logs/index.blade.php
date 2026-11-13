@extends('layouts.admin')

@section('title', 'Lịch sử hoạt động')

@section('content')
<div class="topbar">
    <div>
        <h1>Lịch sử hoạt động (Activity Logs)</h1>
        <small style="color: var(--muted); font-size: 12.5px;">
            Theo dõi toàn bộ thao tác Thêm mới (Created), Cập nhật (Updated) và Xóa (Deleted) trên các phân hệ dữ liệu quản trị
        </small>
    </div>
    <div class="right">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Về Dashboard
        </a>
    </div>
</div>

<div class="card" style="padding: 14px 18px; margin-bottom: 16px;">
    <form method="GET" action="{{ route('admin.activity-logs.index') }}" style="display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
        <select name="module" class="form-control" style="width: auto; min-width: 190px;">
            <option value="">-- Tất cả phân hệ CRUD --</option>
            @foreach ($modules ?? \App\Models\ActivityLog::MODEL_LABELS as $modelKey => $modelLabel)
                <option value="{{ $modelKey }}" @selected(($moduleFilter ?? request('module')) === $modelKey)>
                    {{ $modelLabel }} ({{ $modelKey }})
                </option>
            @endforeach
        </select>

        <select name="action" class="form-control" style="width: auto; min-width: 160px;">
            <option value="">-- Tất cả hành động --</option>
            @foreach ($actions ?? \App\Models\ActivityLog::ACTION_LABELS as $actKey => $actLabel)
                <option value="{{ $actKey }}" @selected(($actionFilter ?? request('action')) === $actKey)>
                    {{ $actLabel }} ({{ $actKey }})
                </option>
            @endforeach
        </select>

        <input type="text" name="q" value="{{ $search ?? request('q') }}" class="form-control"
               style="width: auto; min-width: 240px;" placeholder="Tìm mô tả, người thực hiện, IP...">

        <button type="submit" class="btn btn-primary">Lọc nhật ký</button>
        @if (request()->filled('module') || request()->filled('action') || request()->filled('q'))
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">Đặt lại</a>
        @endif
    </form>
</div>

<div class="table-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 70px;">ID</th>
                <th style="width: 150px;">Thời gian</th>
                <th style="width: 180px;">Người thực hiện</th>
                <th style="width: 130px;">Loại thao tác</th>
                <th>Chi tiết hoạt động</th>
                <th style="width: 130px;">IP Address</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($activityLogs as $log)
            @php
                $badgeClass = match ($log->action) {
                    'Created' => 'success',
                    'Updated' => 'info',
                    'Deleted' => 'danger',
                    default => 'warning',
                };
                $actionText = \App\Models\ActivityLog::ACTION_LABELS[$log->action] ?? $log->action;
            @endphp
            <tr>
                <td>#{{ $log->id }}</td>
                <td style="font-size: 12.5px; color: var(--muted);">
                    {{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '—' }}
                </td>
                <td>
                    <div style="font-weight: 600; color: var(--text);">
                        {{ $log->user ? $log->user->name : 'Hệ thống' }}
                    </div>
                    @if (!empty($log->user?->email))
                        <small style="color: var(--muted);">{{ $log->user->email }}</small>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $badgeClass }}">{{ $actionText }}</span>
                </td>
                <td style="font-weight: 500; color: var(--text);">{{ $log->description }}</td>
                <td><code>{{ $log->ip_address ?? '—' }}</code></td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="padding: 24px; text-align: center; color: var(--muted);">
                    Chưa có lịch sử hoạt động nào.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-wrap">
    {{ $activityLogs->links() }}
</div>
@endsection

