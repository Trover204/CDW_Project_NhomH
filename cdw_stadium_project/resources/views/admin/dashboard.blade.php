@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="topbar">
        <h1>Tổng quan hôm nay</h1>
        <div class="right">
            <a href="{{ route('home') }}" class="btn btn-secondary">
                <i class="ti ti-home"></i> Quay về trang chủ
            </a>
            <input class="search" type="text" placeholder="Tìm đơn, sân...">
            <div class="avatar">NA</div>
        </div>
    </div>

    {{-- 4 thẻ thống kê --}}
    <div class="stats">
        <div class="card stat">
            <div class="label">Doanh thu hôm nay</div>
            <div class="value">
                @if (isset($stats['revenue']))
                    {{ number_format($stats['revenue'], 0, ',', '.') }}đ
                @else
                    1.000.000đ
                @endif
            </div>
        </div>
        <div class="card stat">
            <div class="label">Đơn đặt mới</div>
            <div class="value">{{ $stats['new_bookings'] ?? 100 }}</div>
        </div>
        <div class="card stat">
            <div class="label">Người dùng mới</div>
            <div class="value">{{ $stats['new_users'] ?? 10 }}</div>
        </div>
        <div class="card stat">
            <div class="label">Sân đang hoạt động</div>
            <div class="value green">
                @if (isset($stats['active_courts']))
                    {{ $stats['active_courts'] }}{{ isset($stats['total_courts']) ? '/' . $stats['total_courts'] : '' }}
                @else
                    5/5
                @endif
            </div>
        </div>
    </div>

    <div class="charts">
        {{-- Biểu đồ cột --}}
        <div class="card">
            <h3>Doanh thu 7 ngày qua</h3>
            @php
                $revenueData = $weeklyRevenue ?? [8, 13, 10, 16, 12, 19, 14];
                $maxRevenue = !empty($revenueData) ? max($revenueData) : 1;
            @endphp
            <div class="bars">
                @foreach ($revenueData as $val)
                    <div class="{{ $val === $maxRevenue ? 'max' : '' }}"
                         style="height: {{ round($val / $maxRevenue * 100) }}%"
                         title="{{ number_format($val, 0, ',', '.') }} triệu"></div>
                @endforeach
            </div>
        </div>

        {{-- Biểu đồ tròn bằng CSS --}}
        <div class="card">
            <h3>Loại sân được đặt</h3>
            @php
                $types = $courtTypes ?? [
                    ['name' => 'Cầu lông',   'count' => 18, 'color' => '#0D5C40'],
                    ['name' => 'Bóng đá',    'count' => 14, 'color' => '#22C55E'],
                    ['name' => 'Pickleball', 'count' => 10, 'color' => '#A7F3D0'],
                ];
                $total = array_sum(array_column($types, 'count'));
                $start = 0;
                $parts = [];
                foreach ($types as $t) {
                    $end = $start + ($total > 0 ? ($t['count'] / $total * 100) : 33.33);
                    $parts[] = "{$t['color']} {$start}% {$end}%";
                    $start = $end;
                }
            @endphp
            <div class="donut-wrap">
                <div class="donut" style="background: conic-gradient({{ implode(', ', $parts) }})"></div>
                <ul class="legend">
                    @foreach ($types as $t)
                        <li>
                            <i style="background: {{ $t['color'] }}"></i>
                            <span>{{ $t['name'] }}</span>
                            <strong style="margin-left: auto; color: var(--text);">({{ $t['count'] }})</strong>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- Bảng đơn gần đây --}}
    <div class="section-title">Đơn đặt gần đây</div>
    <div class="table">
        <div class="row head">
            <div>Khách hàng</div><div>Sân</div><div>Khung giờ</div><div>Trạng thái</div>
        </div>
        @php
            $bookings = $recentBookings ?? [
                ['customer' => 'Nguyễn Văn A', 'court' => 'Cầu lông BT',   'time' => '18:00-19:00', 'status' => 'Đã xác nhận', 'badge' => 'success'],
                ['customer' => 'Lê Thị B',     'court' => 'Bóng đá Q9',    'time' => '19:00-20:00', 'status' => 'Chờ duyệt',   'badge' => 'warning'],
                ['customer' => 'Phạm C',       'court' => 'Pickleball Q7', 'time' => '20:00-21:00', 'status' => 'Đã hủy',      'badge' => 'danger'],
            ];
        @endphp
        @foreach ($bookings as $b)
            <div class="row">
                <div style="font-weight: 600;">{{ $b['customer'] }}</div>
                <div>{{ $b['court'] }}</div>
                <div>{{ $b['time'] }}</div>
                <div>
                    <span class="badge {{ $b['badge'] }}">{{ $b['status'] }}</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Lịch sử hoạt động --}}
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

        .section-head a {
            font-size: 14px;
            font-weight: 600;
            color: var(--turf-teal, #10B981);
            transition: color 0.2s;
            border-bottom: 1.5px solid transparent;
            text-decoration: none;
        }

        .section-head a:hover {
            color: var(--turf-dark, #0D5C40);
            border-color: var(--turf-dark, #0D5C40);
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
            <a href="{{ route('admin.activity-logs.index') }}">Xem tất cả</a>
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
    </div>
@endsection