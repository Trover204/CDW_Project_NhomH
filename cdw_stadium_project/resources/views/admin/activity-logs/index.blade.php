@extends('layouts.admin')

@section('title', 'Lịch sử hoạt động')

@section('content')
<style>
    .panel {
        padding: 38px 36px;
        background: var(--bg-white, #FFFFFF);
        border-radius: 24px;
        border: 1px solid var(--line, #E5E7EB);
        box-shadow: 0 12px 36px rgba(13, 56, 46, 0.04);
        margin-top: 2rem;
        margin-bottom: 2rem;
    }

    .section-head {
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 28px;
    }

    .section-head h2 {
        margin: 0;
        font-size: 27px;
        font-weight: 700;
        color: var(--turf-dark, #0D5C40);
    }

    .activity-row {
        display: grid;
        grid-template-columns: 1fr 1fr 2fr 1fr;
        gap: 10px;
        padding: 11px 14px;
        border-top: 1px solid var(--border);
        font-size: 12.5px;
        color: var(--text);
        align-items: center;
    }
</style>

<div class="panel">
    <div class="section-head">
        <h2>Lịch sử hoạt động</h2>
    </div>

    <div class="table">
        <div class="activity-row row head">
            <div>Thời gian</div>
            <div>Người dùng</div>
            <div>Hành động</div>
            <div>IP Address</div>
        </div>
        @if(isset($activityLogs) && $activityLogs->count() > 0)
            @foreach ($activityLogs as $log)
                <div class="activity-row row">
                    <div>{{ $log->created_at->format('d/m/Y H:i') }}</div>
                    <div style="font-weight: 600;">{{ $log->user ? $log->user->name : 'Hệ thống' }}</div>
                    <div>{{ $log->description }}</div>
                    <div>{{ $log->ip_address }}</div>
                </div>
            @endforeach
        @else
            <div class="row" style="display: block; text-align: center; padding: 20px;">
                Chưa có hoạt động nào.
            </div>
        @endif
    </div>

    <div style="margin-top: 20px;">
        {{ $activityLogs->links() }}
    </div>
</div>
@endsection
