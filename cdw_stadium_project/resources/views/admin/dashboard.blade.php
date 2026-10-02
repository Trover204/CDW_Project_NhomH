@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="topbar">
        <h1>Tổng quan hôm nay</h1>
        <div class="right">
            <input class="search" type="text" placeholder="Tìm đơn, sân...">
            <div class="avatar">NA</div>
        </div>
    </div>

    {{-- 4 thẻ thống kê --}}
    <div class="stats">
        <div class="card stat">
            <div class="label">Doanh thu hôm nay</div>
            <div class="value">{{ number_format($stats['revenue'], 0, ',', '.') }}đ</div>
        </div>
        <div class="card stat">
            <div class="label">Đơn đặt mới</div>
            <div class="value">{{ $stats['new_bookings'] }}</div>
        </div>
        <div class="card stat">
            <div class="label">Người dùng mới</div>
            <div class="value">{{ $stats['new_users'] }}</div>
        </div>
        <div class="card stat">
            <div class="label">Sân đang hoạt động</div>
            <div class="value green">{{ $stats['active_courts'] }}/{{ $stats['total_courts'] }}</div>
        </div>
    </div>

    <div class="charts">
        {{-- Biểu đồ cột --}}
        <div class="card">
            <h3>Doanh thu 7 ngày qua</h3>
            @php $max = max($weeklyRevenue); @endphp
            <div class="bars">
                @foreach ($weeklyRevenue as $value)
                    <div class="{{ $value === $max ? 'max' : '' }}"
                         style="height: {{ round($value / $max * 100) }}%"
                         title="{{ number_format($value, 0, ',', '.') }}đ"></div>
                @endforeach
            </div>
        </div>

        {{-- Biểu đồ tròn bằng CSS --}}
        <div class="card">
            <h3>Loại sân được đặt</h3>
            @php
                $total = array_sum(array_column($courtTypes, 'count'));
                $start = 0;
                $parts = [];
                foreach ($courtTypes as $t) {
                    $end = $start + $t['count'] / $total * 100;
                    $parts[] = "{$t['color']} {$start}% {$end}%";
                    $start = $end;
                }
            @endphp
            <div class="donut-wrap">
                <div class="donut" style="background: conic-gradient({{ implode(', ', $parts) }})"></div>
                <ul class="legend">
                    @foreach ($courtTypes as $t)
                        <li><i style="background: {{ $t['color'] }}"></i>{{ $t['name'] }} ({{ $t['count'] }})</li>
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
        @foreach ($recentBookings as $b)
            <div class="row">
                <div>{{ $b['customer'] }}</div>
                <div>{{ $b['court'] }}</div>
                <div>{{ $b['time'] }}</div>
                <div><span class="badge {{ $b['badge'] }}">{{ $b['status'] }}</span></div>
            </div>
        @endforeach
    </div>
@endsection