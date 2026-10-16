@extends('layouts.admin')

@section('title', 'Báo cáo doanh thu')

@section('content')
<style>
    .report-filter-bar {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
    }

    .report-filter-form {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }

    .filter-input {
        height: 36px;
        padding: 0 12px;
        border-radius: 8px;
        border: 1px solid var(--border-strong);
        background: #FFFFFF;
        font-size: 13px;
        color: var(--text);
        outline: none;
        transition: border-color 0.2s;
    }

    .filter-input:focus {
        border-color: var(--primary);
    }

    .btn-action {
        height: 36px;
        padding: 0 14px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .btn-filter {
        background: var(--primary);
        color: #FFFFFF;
    }

    .btn-filter:hover {
        background: var(--primary-hover);
    }

    .btn-excel {
        background: #178A4C;
        color: #FFFFFF;
    }

    .btn-excel:hover {
        background: #0f6235;
        color: #FFFFFF;
    }

    .btn-pdf {
        background: #FFFFFF;
        border: 1px solid var(--border-strong);
        color: var(--text-secondary);
    }

    .btn-pdf:hover {
        background: var(--surface-alt);
        color: var(--text);
    }

    /* KPI Cards */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 20px;
    }

    .kpi-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 16px 20px;
        box-shadow: 0 1px 3px rgba(13, 92, 64, 0.03);
    }

    .kpi-label {
        font-size: 12px;
        color: var(--muted);
        margin-bottom: 8px;
        font-weight: 500;
    }

    .kpi-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--text);
        line-height: 1.2;
    }

    .kpi-trend {
        font-size: 11px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
    }

    .kpi-trend.positive {
        color: #16A34A;
    }

    .kpi-trend.negative {
        color: #DC2626;
    }

    .kpi-trend.neutral {
        color: var(--muted);
    }

    /* Charts Container */
    .chart-panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 20px;
    }

    .chart-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .chart-panel-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
    }

    .chart-bars-wrap {
        height: 140px;
        display: flex;
        align-items: flex-end;
        gap: 6px;
        padding-top: 20px;
        border-bottom: 1px solid var(--border);
        overflow-x: auto;
    }

    .chart-bar-item {
        flex: 1;
        min-width: 18px;
        display: flex;
        flex-direction: column;
        align-items: center;
        height: 100%;
        justify-content: flex-end;
        position: relative;
    }

    .chart-bar-pillar {
        width: 100%;
        max-width: 24px;
        background: #BEE3CB;
        border-radius: 4px 4px 0 0;
        transition: height 0.3s ease, background-color 0.2s;
        cursor: pointer;
    }

    .chart-bar-pillar.max {
        background: #178A4C;
    }

    .chart-bar-pillar:hover {
        background: #0D5C40;
    }

    .chart-bar-label {
        font-size: 10px;
        color: var(--muted);
        margin-top: 6px;
        white-space: nowrap;
    }

    /* Tooltip */
    .chart-bar-item:hover::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 105%;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(17, 24, 39, 0.9);
        color: #FFFFFF;
        font-size: 11px;
        padding: 5px 8px;
        border-radius: 6px;
        white-space: nowrap;
        pointer-events: none;
        z-index: 10;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    /* Grid Layout for Sport Breakdown & Yearly */
    .two-col-grid {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    @media (max-width: 900px) {
        .kpi-grid {
            grid-template-columns: 1fr;
        }
        .two-col-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Custom Table Styling */
    .report-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
    }

    .report-table th {
        background: var(--surface-alt);
        color: var(--muted);
        font-weight: 600;
        font-size: 11.5px;
        text-align: left;
        padding: 10px 14px;
        border-bottom: 1px solid var(--border);
    }

    .report-table td {
        padding: 11px 14px;
        border-bottom: 1px solid var(--border);
        color: var(--text-secondary);
    }

    .report-table tr:hover td {
        background: var(--surface-hover);
    }

    .progress-bar-bg {
        width: 80px;
        height: 6px;
        background: #E5E7EB;
        border-radius: 9999px;
        overflow: hidden;
        display: inline-block;
        vertical-align: middle;
        margin-right: 8px;
    }

    .progress-bar-fill {
        height: 100%;
        background: var(--primary);
        border-radius: 9999px;
    }
</style>

<div class="topbar">
    <div>
        <h1 class="page-title">Báo cáo doanh thu</h1>
        <small style="color: var(--muted); font-size: 12px;">Thống kê tổng hợp doanh thu và đơn đặt sân theo tháng</small>
    </div>
    <div class="right">
        <a href="{{ route('admin.reports.revenue.excel', request()->all()) }}" class="btn-action btn-excel">
            <i class="ti ti-file-spreadsheet"></i> Xuất Excel
        </a>
        <a href="{{ route('admin.reports.revenue.pdf', request()->all()) }}" target="_blank" class="btn-action btn-pdf">
            <i class="ti ti-file-type-pdf"></i> Xuất PDF
        </a>
    </div>
</div>

{{-- Thanh lọc dữ liệu --}}
<div class="report-filter-bar">
    <form action="{{ route('admin.reports.revenue') }}" method="GET" class="report-filter-form">
        <div style="display: flex; align-items: center; gap: 6px;">
            <label for="month-picker" style="font-size: 12.5px; font-weight: 600; color: var(--text-secondary);">Tháng:</label>
            <input type="month" id="month-picker" name="month" class="filter-input" value="{{ $selectedMonth }}">
        </div>

        <div style="display: flex; align-items: center; gap: 6px;">
            <label for="sport-picker" style="font-size: 12.5px; font-weight: 600; color: var(--text-secondary);">Môn:</label>
            <select id="sport-picker" name="sport_type_id" class="filter-input">
                <option value="">Tất cả môn thể thao</option>
                @foreach ($sportTypes as $st)
                    <option value="{{ $st->id }}" {{ request('sport_type_id') == $st->id ? 'selected' : '' }}>
                        {{ $st->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; align-items: center; gap: 6px;">
            <label for="facility-picker" style="font-size: 12.5px; font-weight: 600; color: var(--text-secondary);">Cơ sở:</label>
            <select id="facility-picker" name="facility_id" class="filter-input">
                <option value="">Tất cả cơ sở</option>
                @foreach ($facilities as $fc)
                    <option value="{{ $fc->id }}" {{ request('facility_id') == $fc->id ? 'selected' : '' }}>
                        {{ $fc->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-action btn-filter">
            <i class="ti ti-filter"></i> Lọc báo cáo
        </button>

        @if(request()->hasAny(['month', 'sport_type_id', 'facility_id']))
            <a href="{{ route('admin.reports.revenue') }}" style="font-size: 12px; color: var(--muted); text-decoration: underline; margin-left: 4px;">
                Đặt lại
            </a>
        @endif
    </form>

    <div style="font-size: 12.5px; font-weight: 600; color: var(--primary);">
        <i class="ti ti-calendar-event"></i> Kỳ báo cáo: {{ $periodLabel }}
    </div>
</div>

{{-- 3 Thẻ chỉ số (KPI cards) theo mục 4.15 --}}
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-label">Tổng doanh thu</div>
        <div class="kpi-value">{{ number_format($totalRevenue, 0, ',', '.') }}đ</div>
        <div class="kpi-trend {{ $growthRate === null ? 'neutral' : ($growthRate >= 0 ? 'positive' : 'negative') }}">
            @if ($growthRate !== null)
                @if ($growthRate >= 0)
                    <span>▲ +{{ $growthRate }}%</span> so với kỳ trước
                @else
                    <span>▼ {{ $growthRate }}%</span> so với kỳ trước
                @endif
            @else
                <span>● Kỳ trước chưa có dữ liệu</span>
            @endif
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Số đơn hoàn thành</div>
        <div class="kpi-value">{{ number_format($completedBookingsCount, 0, ',', '.') }}</div>
        <div class="kpi-trend neutral">
            <span>Tổng {{ $totalBookingsCount }} lượt đặt trong kỳ</span>
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Giá trị đơn TB</div>
        <div class="kpi-value">{{ number_format($averageOrderValue, 0, ',', '.') }}đ</div>
        <div class="kpi-trend neutral">
            <span>Doanh thu bình quân / đơn thành công</span>
        </div>
    </div>
</div>

{{-- Biểu đồ Doanh thu theo ngày trong tháng --}}
<div class="chart-panel">
    <div class="chart-panel-header">
        <h2 class="chart-panel-title">Doanh thu theo ngày trong {{ $periodLabel }}</h2>
        <small style="color: var(--muted); font-size: 11px;">(Cột màu xanh đậm thể hiện ngày đạt doanh thu cao nhất)</small>
    </div>

    @if ($totalRevenue > 0)
        <div class="chart-bars-wrap">
            @foreach ($daysInMonth as $d)
                <div class="chart-bar-item" data-tooltip="{{ $d['label'] }}: {{ number_format($d['revenue'], 0, ',', '.') }}đ ({{ $d['count'] }} đơn)">
                    <div class="chart-bar-pillar {{ $d['is_max'] ? 'max' : '' }}"
                         style="height: {{ $d['height_percent'] }}%;"></div>
                    <div class="chart-bar-label">{{ $d['day'] }}</div>
                </div>
            @endforeach
        </div>
    @else
        <div style="text-align: center; padding: 40px 10px; color: var(--muted); font-size: 13px;">
            <i class="ti ti-chart-bar-off" style="font-size: 28px; display: block; margin-bottom: 8px;"></i>
            Không có dữ liệu doanh thu trong khoảng thời gian này.
        </div>
    @endif
</div>

{{-- Khối 2 cột: Doanh thu theo loại sân & Thống kê 12 tháng --}}
<div class="two-col-grid">
    {{-- Doanh thu theo loại sân --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 16px 18px; border-bottom: 1px solid var(--border);">
            <h3 style="margin: 0; font-size: 14px; font-weight: 700; color: var(--text);">Doanh thu theo loại sân</h3>
        </div>
        @if ($sportTypesBreakdown->count() > 0 && $sportTypesBreakdown->sum('revenue') > 0)
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Loại sân</th>
                        <th style="text-align: center;">Số đơn</th>
                        <th style="text-align: right;">Doanh thu</th>
                        <th style="text-align: right;">Tỷ trọng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sportTypesBreakdown as $st)
                        <tr>
                            <td style="font-weight: 600; color: var(--text);">
                                <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: {{ $st['color'] }}; margin-right: 6px;"></span>
                                {{ $st['name'] }}
                            </td>
                            <td style="text-align: center;">{{ $st['count'] }}</td>
                            <td style="text-align: right; font-weight: 600;">{{ number_format($st['revenue'], 0, ',', '.') }}đ</td>
                            <td style="text-align: right;">
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="width: {{ $st['percent'] }}%; background: {{ $st['color'] }};"></div>
                                </div>
                                <span style="font-size: 11px; font-weight: 600;">{{ $st['percent'] }}%</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="text-align: center; padding: 36px 10px; color: var(--muted); font-size: 13px;">
                Không có dữ liệu loại sân trong khoảng thời gian này.
            </div>
        @endif
    </div>

    {{-- Thống kê doanh thu theo 12 tháng trong năm --}}
    <div class="card" style="padding: 0; overflow: hidden;">
        <div style="padding: 16px 18px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 14px; font-weight: 700; color: var(--text);">Thống kê 12 tháng năm {{ $selectedYear }}</h3>
            <span style="font-size: 11px; color: var(--muted);">Doanh thu theo tháng</span>
        </div>
        <table class="report-table">
            <thead>
                <tr>
                    <th>Tháng</th>
                    <th style="text-align: center;">Số đơn</th>
                    <th style="text-align: right;">Doanh thu</th>
                    <th style="text-align: right;">Đơn TB</th>
                    <th style="text-align: right;">Tăng trưởng</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($monthlyBreakdown as $m)
                    <tr style="{{ $m['month_key'] === $selectedMonth ? 'background: #F0FDF4; font-weight: 600;' : '' }}">
                        <td>
                            <a href="{{ route('admin.reports.revenue', ['month' => $m['month_key']]) }}" style="color: inherit; text-decoration: none;">
                                {{ $m['month_label'] }}
                                @if($m['month_key'] === $selectedMonth)
                                    <span style="font-size: 9.5px; background: #DCFCE7; color: #16A34A; padding: 2px 6px; border-radius: 10px; margin-left: 4px;">Đang xem</span>
                                @endif
                            </a>
                        </td>
                        <td style="text-align: center;">{{ $m['count'] }}</td>
                        <td style="text-align: right; color: var(--text);">{{ number_format($m['revenue'], 0, ',', '.') }}đ</td>
                        <td style="text-align: right; font-size: 12px; color: var(--muted);">{{ number_format($m['aov'], 0, ',', '.') }}đ</td>
                        <td style="text-align: right; font-size: 11.5px;">
                            @if ($m['growth'] !== null)
                                <span style="color: {{ $m['growth'] >= 0 ? '#16A34A' : '#DC2626' }};">
                                    {{ $m['growth'] >= 0 ? '+' : '' }}{{ $m['growth'] }}%
                                </span>
                            @else
                                <span style="color: var(--muted);">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Bảng chi tiết đơn đặt trong kỳ --}}
<div class="card" style="padding: 0; overflow: hidden; margin-bottom: 20px;">
    <div style="padding: 16px 18px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; font-size: 14px; font-weight: 700; color: var(--text);">Danh sách đơn đặt phát sinh doanh thu</h3>
        <span style="font-size: 12px; color: var(--muted);">Hiển thị {{ $bookings->count() }} / {{ $totalBookingsCount }} đơn</span>
    </div>
    @if ($bookings->count() > 0)
        <table class="report-table">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Sân</th>
                    <th>Ngày đặt</th>
                    <th>Khung giờ</th>
                    <th style="text-align: right;">Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $b)
                    <tr>
                        <td style="font-weight: 600; color: var(--primary);">
                            #BK-{{ str_pad($b->id, 5, '0', STR_PAD_LEFT) }}
                        </td>
                        <td>
                            <div style="font-weight: 600; color: var(--text);">{{ $b->customer_name }}</div>
                            <small style="color: var(--muted);">{{ $b->customer_phone }}</small>
                        </td>
                        <td>
                            <div>{{ $b->court ? $b->court->name : 'N/A' }}</div>
                            <small style="color: var(--muted);">{{ $b->court && $b->court->sportType ? $b->court->sportType->name : '' }}</small>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($b->booking_date)->format('d/m/Y') }}</td>
                        <td>{{ substr($b->start_time, 0, 5) }} - {{ substr($b->end_time, 0, 5) }}</td>
                        <td style="text-align: right; font-weight: 700; color: var(--text);">
                            {{ number_format($b->total_price, 0, ',', '.') }}đ
                        </td>
                        <td>
                            @if ($b->payment)
                                <span class="badge {{ $b->payment->status === 'paid' ? 'success' : 'warning' }}">
                                    {{ strtoupper($b->payment->payment_method) }}: {{ $b->payment->status === 'paid' ? 'Đã thu' : 'Chưa thu' }}
                                </span>
                            @else
                                <span style="color: var(--muted); font-size: 12px;">Chưa có</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $b->status === 'completed' ? 'success' : ($b->status === 'confirmed' ? 'primary' : 'warning') }}">
                                {{ $b->status === 'completed' ? 'Hoàn thành' : ($b->status === 'confirmed' ? 'Đã xác nhận' : $b->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding: 12px 18px;">
            {{ $bookings->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 36px 10px; color: var(--muted); font-size: 13px;">
            Không có đơn đặt nào trong kỳ này.
        </div>
    @endif
</div>
@endsection
