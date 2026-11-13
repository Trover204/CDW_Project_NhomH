@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <style>
        .db-section-header {
            margin-top: 24px;
            margin-bottom: 14px;
        }
        .db-section-header h2 {
            font-size: 17px;
            font-weight: 700;
            color: var(--turf-dark, #0D5C40);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .db-section-header p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 12.5px;
        }
        .table-stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }
        @media (max-width: 1280px) {
            .table-stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 640px) {
            .table-stats-grid {
                grid-template-columns: 1fr;
            }
        }
        .table-stat-card {
            display: flex;
            flex-direction: column;
            border-radius: 14px;
            border: 1px solid var(--border);
            padding: 16px 18px;
            background: var(--surface);
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .table-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(13, 92, 64, 0.08);
            border-color: var(--primary);
        }
        .tsc-head {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-bottom: 12px;
            border-bottom: 1px dashed var(--border);
        }
        .tsc-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
            flex-shrink: 0;
        }
        .tsc-icon.bg-green { background: #DCFCE7; color: #15803D; }
        .tsc-icon.bg-teal { background: #CCFBF1; color: #0F766E; }
        .tsc-icon.bg-amber { background: #FEF3C7; color: #B45309; }
        .tsc-icon.bg-purple { background: #F3E8FF; color: #7E22CE; }
        .tsc-icon.bg-emerald { background: #D1FAE5; color: #047857; }
        .tsc-icon.bg-orange { background: #FFEDD5; color: #C2410C; }
        .tsc-icon.bg-yellow { background: #FEF9C3; color: #A16207; }
        .tsc-icon.bg-sky { background: #E0F2FE; color: #0369A1; }

        .tsc-meta {
            flex: 1;
            min-width: 0;
        }
        .tsc-title {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text);
            line-height: 1.3;
        }
        .tsc-table {
            display: block;
            font-size: 11px;
            color: var(--muted);
            margin-top: 2px;
        }
        .tsc-table code {
            background: var(--surface-alt);
            padding: 1px 4px;
            border-radius: 4px;
            color: var(--primary);
            font-size: 10.5px;
            font-family: monospace;
        }
        .tsc-total {
            font-size: 20px;
            font-weight: 800;
            color: var(--text);
            text-align: right;
            line-height: 1;
        }
        .tsc-total small {
            font-size: 10.5px;
            font-weight: 500;
            color: var(--muted);
            display: block;
            margin-top: 2px;
        }
        .tsc-body {
            padding: 10px 0;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 7px;
            font-size: 12px;
        }
        .tsc-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            color: var(--text-secondary);
        }
        .tsc-foot {
            padding-top: 10px;
            border-top: 1px solid var(--border);
            font-size: 11.5px;
        }
        .tsc-foot a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.15s ease;
        }
        .tsc-foot a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }
        .text-primary {
            color: var(--primary);
        }
        .panel {
            padding: 28px 30px;
            background: var(--surface);
            border-radius: 16px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 20px rgba(13, 56, 46, 0.03);
            margin-top: 24px;
            margin-bottom: 24px;
        }
        .section-head {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }
        .section-head h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: var(--turf-dark, #0D5C40);
        }
        .section-head a {
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            transition: color 0.2s;
            text-decoration: none;
        }
        .section-head a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
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

        /* =========================================================
           30-Day Interactive Line/Area Graph Styles
           ========================================================= */
        .charts.charts-30d {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
            margin-bottom: 24px;
            width: 100%;
        }
        .graph-card {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: visible;
            padding: 22px 26px;
            width: 100%;
        }
        .graph-card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }
        .graph-title-group h3 {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .graph-title-group h3 i {
            color: var(--primary);
            font-size: 18px;
        }
        .graph-subtitle {
            margin: 4px 0 0;
            font-size: 12px;
            color: var(--muted);
        }
        .graph-kpis {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .graph-kpi-pill {
            background: var(--surface-alt);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 6px 12px;
            display: flex;
            flex-direction: column;
            min-width: 105px;
        }
        .graph-kpi-pill span {
            font-size: 10.5px;
            color: var(--muted);
            font-weight: 500;
        }
        .graph-kpi-pill strong {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--text);
            margin-top: 1px;
        }
        .graph-kpi-pill.highlight strong {
            color: var(--primary);
        }
        .graph-stage {
            position: relative;
            width: 100%;
            user-select: none;
        }
        .revenue-30d-svg {
            width: 100%;
            height: 290px;
            display: block;
            overflow: visible;
        }
        .graph-grid-line {
            stroke: var(--border);
            stroke-width: 1;
            stroke-dasharray: 4 4;
        }
        .graph-grid-line.baseline {
            stroke: var(--border-strong);
            stroke-dasharray: none;
        }
        .graph-axis-text {
            fill: var(--muted);
            font-size: 10.5px;
            font-weight: 600;
            font-family: inherit;
        }
        .graph-x-text {
            fill: var(--muted);
            font-size: 10.5px;
            font-weight: 600;
            font-family: inherit;
            transition: fill 0.15s ease, font-weight 0.15s ease;
        }
        .graph-x-text.is-active {
            fill: var(--primary);
            font-weight: 800;
        }
        .graph-area-path {
            transition: opacity 0.25s ease;
        }
        .graph-line-path {
            fill: none;
            stroke: url(#revenueLineGrad);
            stroke-width: 3;
            stroke-linecap: round;
            stroke-linejoin: round;
            filter: drop-shadow(0 4px 8px rgba(236, 72, 153, 0.25));
            transition: stroke-width 0.2s ease;
        }
        .graph-stage.is-hovering .graph-line-path {
            stroke-width: 3.6;
        }
        .graph-line-hit {
            fill: none;
            stroke: transparent;
            stroke-width: 18;
            cursor: crosshair;
        }
        .graph-hover-guide {
            stroke: var(--primary);
            stroke-width: 1.5;
            stroke-dasharray: 4 4;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.15s ease;
        }
        .graph-stage.is-hovering .graph-hover-guide {
            opacity: 0.75;
        }
        .graph-point-group {
            cursor: pointer;
        }
        .graph-point-halo {
            fill: var(--primary);
            opacity: 0;
            transform-box: fill-box;
            transform-origin: center;
            transition: opacity 0.18s ease, r 0.18s ease;
        }
        .graph-point-dot {
            fill: #FFFFFF;
            stroke: var(--primary);
            stroke-width: 2.5;
            transition: r 0.18s ease, fill 0.18s ease, stroke-width 0.18s ease;
        }
        .graph-point-dot.is-peak {
            fill: var(--primary);
            stroke: #FFFFFF;
            stroke-width: 2;
        }
        .graph-point-group.is-active .graph-point-halo,
        .graph-point-group:hover .graph-point-halo {
            opacity: 0.22;
        }
        .graph-point-group.is-active .graph-point-dot,
        .graph-point-group:hover .graph-point-dot {
            r: 6.5;
            fill: var(--primary);
            stroke: #FFFFFF;
            stroke-width: 2.5;
        }
        .graph-point-hit {
            fill: transparent;
            cursor: pointer;
        }
        .graph-tooltip {
            position: absolute;
            top: 8px;
            left: 0;
            z-index: 30;
            min-width: 215px;
            background: rgba(24, 24, 36, 0.96);
            color: #FFFFFF;
            border: 1px solid rgba(255, 46, 147, 0.45);
            border-radius: 12px;
            padding: 11px 13px;
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.25);
            pointer-events: none;
            opacity: 0;
            transform: translate3d(0, 6px, 0);
            transition: opacity 0.15s ease, transform 0.15s ease;
            backdrop-filter: blur(8px);
        }
        .graph-tooltip.is-visible {
            opacity: 1;
            transform: translate3d(0, 0, 0);
        }
        .gt-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding-bottom: 6px;
            margin-bottom: 6px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            font-size: 11.5px;
            color: #E2D8DF;
        }
        .gt-date {
            font-weight: 700;
            color: #FFFFFF;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .gt-badge {
            background: rgba(255, 46, 147, 0.22);
            color: #FF85C0;
            padding: 1px 7px;
            border-radius: 999px;
            font-size: 10.5px;
            font-weight: 700;
        }
        .gt-total {
            font-size: 16px;
            font-weight: 800;
            color: #FF2E93;
            margin-bottom: 7px;
            display: flex;
            align-items: baseline;
            justify-content: space-between;
        }
        .gt-total small {
            font-size: 11px;
            font-weight: 600;
            color: #9CA3AF;
        }
        .gt-breakdown {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 11px;
            color: #D1D5DB;
        }
        .gt-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .gt-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .graph-foot-note {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 8px;
            padding-top: 10px;
            border-top: 1px dashed var(--border);
            font-size: 11.5px;
            color: var(--muted);
        }
        .graph-legend-inline {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }
        .graph-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
            color: var(--text-secondary);
        }
        .legend-line-swatch {
            width: 18px;
            height: 3px;
            border-radius: 999px;
            background: var(--primary);
            display: inline-block;
        }
        .legend-peak-swatch {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--primary);
            border: 2px solid #FCE7F3;
            display: inline-block;
        }
    </style>

    <div class="topbar">
        <h1>Tổng quan hôm nay</h1>
        <div class="right">
            <a href="{{ route('home') }}" class="btn btn-secondary">
                <i class="ti ti-home"></i> Quay về trang chủ
            </a>
            <form action="{{ route('admin.search') }}" method="GET" class="admin-search-form">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path d="m21 21-4.3-4.3"/>
                </svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('Tìm kiếm sân, cơ sở, sản phẩm, tin tức...') }}">
                <div class="admin-search-mode-divider"></div>
                <select name="mode" class="admin-search-mode-select" aria-label="{{ __('Chế độ tìm kiếm') }}" title="{{ __('Chọn chế độ tìm kiếm') }}">
                    <option value="elasticsearch" @selected(request('mode', 'elasticsearch') === 'elasticsearch')>⚡ Elasticsearch</option>
                    <option value="normal" @selected(request('mode') === 'normal')>🗄️ Tìm kiếm thường</option>
                </select>
                <button type="submit" class="admin-search-btn">
                    {{ __('Tìm kiếm') }}
                </button>
            </form>
            @php
                $authUser = auth()->user();
                $avatarInitials = 'NA';
                if ($authUser && !empty($authUser->name)) {
                    $words = preg_split('/\s+/u', trim($authUser->name), -1, PREG_SPLIT_NO_EMPTY);
                    $avatarInitials = count($words) >= 2
                        ? mb_strtoupper(mb_substr($words[0], 0, 1, 'UTF-8') . mb_substr(end($words), 0, 1, 'UTF-8'), 'UTF-8')
                        : mb_strtoupper(mb_substr($authUser->name, 0, 2, 'UTF-8'), 'UTF-8');
                }
            @endphp
            <a href="{{ route('profile.edit') }}" class="avatar" title="{{ $authUser->name ?? 'Hồ sơ cá nhân' }}" aria-label="{{ $authUser->name ?? 'Hồ sơ cá nhân' }}">
                @if ($authUser && $authUser->avatar)
                    <img src="{{ asset('storage/' . $authUser->avatar) }}" alt="{{ $authUser->name }}">
                @else
                    {{ $avatarInitials }}
                @endif
            </a>
        </div>
    </div>

    {{-- 4 thẻ thống kê tổng quan --}}
    <div class="stats">
        <div class="card stat">
            <div class="label">Doanh thu thanh toán</div>
            <div class="value">
                @if (isset($stats['revenue']))
                    {{ number_format($stats['revenue'], 0, ',', '.') }}đ
                @else
                    0đ
                @endif
            </div>
        </div>
        <div class="card stat">
            <div class="label">Đơn đặt sân</div>
            <div class="value">{{ $stats['new_bookings'] ?? 0 }}</div>
        </div>
        <div class="card stat">
            <div class="label">Người dùng</div>
            <div class="value">{{ $stats['new_users'] ?? 0 }}</div>
        </div>
        <div class="card stat">
            <div class="label">Sân hoạt động</div>
            <div class="value green">
                @if (isset($stats['active_courts']))
                    {{ $stats['active_courts'] }}{{ isset($stats['total_courts']) ? '/' . $stats['total_courts'] : '' }}
                @else
                    0/0
                @endif
            </div>
        </div>
    </div>

    {{-- Khối thống kê chi tiết các bảng cơ sở dữ liệu chính --}}
    @if(isset($tableStats))
        <div class="db-section-header">
            <h2><i class="ti ti-database"></i> Thống kê Cơ Sở Dữ Liệu</h2>
            <p>Số liệu tổng quan thời gian thực của Sport, Facility, Booking, Payment, Product, Sale, Review, News</p>
        </div>

        <div class="table-stats-grid">
            {{-- 1. Sport --}}
            @if(isset($tableStats['sport']))
                <div class="card table-stat-card">
                    <div class="tsc-head">
                        <div class="tsc-icon bg-green"><i class="ti {{ $tableStats['sport']['icon'] }}"></i></div>
                        <div class="tsc-meta">
                            <span class="tsc-title">{{ $tableStats['sport']['title'] }}</span>
                            <span class="tsc-table">Bảng: <code>{{ $tableStats['sport']['table'] }}</code></span>
                        </div>
                        <div class="tsc-total">{{ $tableStats['sport']['total'] }} <small>môn</small></div>
                    </div>
                    <div class="tsc-body">
                        <div class="tsc-row">
                            <span>Đang hoạt động</span>
                            <span class="badge success">{{ $tableStats['sport']['active'] }} môn</span>
                        </div>
                        <div class="tsc-row">
                            <span>Ngừng hoạt động</span>
                            <span class="badge {{ $tableStats['sport']['inactive'] > 0 ? 'danger' : 'info' }}">{{ $tableStats['sport']['inactive'] }}</span>
                        </div>
                        <div class="tsc-row">
                            <span>Tổng sân liên kết</span>
                            <strong>{{ number_format($tableStats['sport']['total_courts']) }} sân</strong>
                        </div>
                    </div>
                    <div class="tsc-foot">
                        <a href="{{ $tableStats['sport']['route'] }}">Quản lý môn thể thao <i class="ti ti-arrow-right"></i></a>
                    </div>
                </div>
            @endif

            {{-- 2. Facility --}}
            @if(isset($tableStats['facility']))
                <div class="card table-stat-card">
                    <div class="tsc-head">
                        <div class="tsc-icon bg-teal"><i class="ti {{ $tableStats['facility']['icon'] }}"></i></div>
                        <div class="tsc-meta">
                            <span class="tsc-title">{{ $tableStats['facility']['title'] }}</span>
                            <span class="tsc-table">Bảng: <code>{{ $tableStats['facility']['table'] }}</code></span>
                        </div>
                        <div class="tsc-total">{{ $tableStats['facility']['total'] }} <small>cơ sở</small></div>
                    </div>
                    <div class="tsc-body">
                        <div class="tsc-row">
                            <span>Đang mở cửa</span>
                            <span class="badge success">{{ $tableStats['facility']['active'] }} cơ sở</span>
                        </div>
                        <div class="tsc-row">
                            <span>Phủ sóng</span>
                            <span class="badge info">{{ $tableStats['facility']['districts_count'] }} quận/khu vực</span>
                        </div>
                        <div class="tsc-row">
                            <span>Giờ phục vụ</span>
                            <strong>{{ $tableStats['facility']['operating_hours'] }}</strong>
                        </div>
                    </div>
                    <div class="tsc-foot">
                        <a href="{{ $tableStats['facility']['route'] }}">Quản lý cơ sở <i class="ti ti-arrow-right"></i></a>
                    </div>
                </div>
            @endif

            {{-- 3. Booking --}}
            @if(isset($tableStats['booking']))
                <div class="card table-stat-card">
                    <div class="tsc-head">
                        <div class="tsc-icon bg-emerald"><i class="ti {{ $tableStats['booking']['icon'] }}"></i></div>
                        <div class="tsc-meta">
                            <span class="tsc-title">{{ $tableStats['booking']['title'] }}</span>
                            <span class="tsc-table">Bảng: <code>{{ $tableStats['booking']['table'] }}</code></span>
                        </div>
                        <div class="tsc-total">{{ $tableStats['booking']['total'] }} <small>đơn</small></div>
                    </div>
                    <div class="tsc-body">
                        <div class="tsc-row">
                            <span>Duyệt / Xong</span>
                            <span><span class="badge info">{{ $tableStats['booking']['confirmed'] }} duyệt</span> <span class="badge success">{{ $tableStats['booking']['completed'] }} xong</span></span>
                        </div>
                        <div class="tsc-row">
                            <span>Chờ / Đã hủy</span>
                            <span><span class="badge warning">{{ $tableStats['booking']['pending'] }} chờ</span> <span class="badge danger">{{ $tableStats['booking']['cancelled'] }} hủy</span></span>
                        </div>
                        <div class="tsc-row">
                            <span>Doanh thu đặt sân</span>
                            <strong class="text-primary">{{ number_format($tableStats['booking']['total_revenue'], 0, ',', '.') }}đ</strong>
                        </div>
                    </div>
                    <div class="tsc-foot">
                        <a href="{{ $tableStats['booking']['route'] }}">Quản lý đơn đặt sân <i class="ti ti-arrow-right"></i></a>
                    </div>
                </div>
            @endif

            {{-- 4. Payment --}}
            @if(isset($tableStats['payment']))
                <div class="card table-stat-card">
                    <div class="tsc-head">
                        <div class="tsc-icon bg-purple"><i class="ti {{ $tableStats['payment']['icon'] }}"></i></div>
                        <div class="tsc-meta">
                            <span class="tsc-title">{{ $tableStats['payment']['title'] }}</span>
                            <span class="tsc-table">Bảng: <code>{{ $tableStats['payment']['table'] }}</code></span>
                        </div>
                        <div class="tsc-total">{{ $tableStats['payment']['total'] }} <small>GD</small></div>
                    </div>
                    <div class="tsc-body">
                        <div class="tsc-row">
                            <span>Đã thanh toán</span>
                            <span class="badge success">{{ $tableStats['payment']['paid_count'] }} GD</span>
                        </div>
                        <div class="tsc-row">
                            <span>Chờ / Thất bại</span>
                            <span><span class="badge warning">{{ $tableStats['payment']['pending_count'] }} chờ</span> <span class="badge danger">{{ $tableStats['payment']['failed_count'] }} lỗi</span></span>
                        </div>
                        <div class="tsc-row">
                            <span>Thực thu</span>
                            <strong class="text-primary">{{ number_format($tableStats['payment']['paid_revenue'], 0, ',', '.') }}đ</strong>
                        </div>
                    </div>
                    <div class="tsc-foot">
                        <a href="{{ $tableStats['payment']['route'] }}">Quản lý thanh toán <i class="ti ti-arrow-right"></i></a>
                    </div>
                </div>
            @endif

            {{-- 5. Product --}}
            @if(isset($tableStats['product']))
                <div class="card table-stat-card">
                    <div class="tsc-head">
                        <div class="tsc-icon bg-amber"><i class="ti {{ $tableStats['product']['icon'] }}"></i></div>
                        <div class="tsc-meta">
                            <span class="tsc-title">{{ $tableStats['product']['title'] }}</span>
                            <span class="tsc-table">Bảng: <code>{{ $tableStats['product']['table'] }}</code></span>
                        </div>
                        <div class="tsc-total">{{ $tableStats['product']['total'] }} <small>SP</small></div>
                    </div>
                    <div class="tsc-body">
                        <div class="tsc-row">
                            <span>Tình trạng kho</span>
                            <span><span class="badge success">{{ $tableStats['product']['in_stock'] }} còn hàng</span></span>
                        </div>
                        <div class="tsc-row">
                            <span>Tổng tồn kho</span>
                            <strong>{{ number_format($tableStats['product']['total_stock']) }} món</strong>
                        </div>
                        <div class="tsc-row">
                            <span>Giá bán TB</span>
                            <strong class="text-primary">{{ number_format($tableStats['product']['avg_price'], 0, ',', '.') }}đ</strong>
                        </div>
                    </div>
                    <div class="tsc-foot">
                        <a href="{{ $tableStats['product']['route'] }}">Quản lý sản phẩm <i class="ti ti-arrow-right"></i></a>
                    </div>
                </div>
            @endif

            {{-- 6. Sale --}}
            @if(isset($tableStats['sale']))
                <div class="card table-stat-card">
                    <div class="tsc-head">
                        <div class="tsc-icon bg-orange"><i class="ti {{ $tableStats['sale']['icon'] }}"></i></div>
                        <div class="tsc-meta">
                            <span class="tsc-title">{{ $tableStats['sale']['title'] }}</span>
                            <span class="tsc-table">Bảng: <code>{{ $tableStats['sale']['table'] }}</code></span>
                        </div>
                        <div class="tsc-total">{{ $tableStats['sale']['total'] }} <small>đơn</small></div>
                    </div>
                    <div class="tsc-body">
                        <div class="tsc-row">
                            <span>Hoàn thành / Chờ</span>
                            <span><span class="badge success">{{ $tableStats['sale']['completed'] }} xong</span> <span class="badge warning">{{ $tableStats['sale']['pending'] }} chờ</span></span>
                        </div>
                        <div class="tsc-row">
                            <span>Số lượng đã bán</span>
                            <strong>{{ number_format($tableStats['sale']['total_quantity']) }} SP</strong>
                        </div>
                        <div class="tsc-row">
                            <span>Doanh thu bán lẻ</span>
                            <strong class="text-primary">{{ number_format($tableStats['sale']['total_revenue'], 0, ',', '.') }}đ</strong>
                        </div>
                    </div>
                    <div class="tsc-foot">
                        <a href="{{ $tableStats['sale']['route'] }}">Quản lý đơn bán hàng <i class="ti ti-arrow-right"></i></a>
                    </div>
                </div>
            @endif

            {{-- 7. Review --}}
            @if(isset($tableStats['review']))
                <div class="card table-stat-card">
                    <div class="tsc-head">
                        <div class="tsc-icon bg-yellow"><i class="ti {{ $tableStats['review']['icon'] }}"></i></div>
                        <div class="tsc-meta">
                            <span class="tsc-title">{{ $tableStats['review']['title'] }}</span>
                            <span class="tsc-table">Bảng: <code>{{ $tableStats['review']['table'] }}</code></span>
                        </div>
                        <div class="tsc-total">{{ $tableStats['review']['total'] }} <small>đánh giá</small></div>
                    </div>
                    <div class="tsc-body">
                        <div class="tsc-row">
                            <span>Điểm đánh giá TB</span>
                            <strong style="color: #D97706; font-size: 13.5px;">★ {{ $tableStats['review']['avg_rating'] }} / 5.0</strong>
                        </div>
                        <div class="tsc-row">
                            <span>Đánh giá 4★ & 5★</span>
                            <span class="badge success">{{ $tableStats['review']['high_ratings'] }} lượt</span>
                        </div>
                        <div class="tsc-row">
                            <span>Trạng thái hiển thị</span>
                            <span class="badge info">{{ $tableStats['review']['approved'] }} đã duyệt</span>
                        </div>
                    </div>
                    <div class="tsc-foot">
                        <a href="{{ $tableStats['review']['route'] }}">Quản lý bình luận <i class="ti ti-arrow-right"></i></a>
                    </div>
                </div>
            @endif

            {{-- 8. News --}}
            @if(isset($tableStats['news']))
                <div class="card table-stat-card">
                    <div class="tsc-head">
                        <div class="tsc-icon bg-sky"><i class="ti {{ $tableStats['news']['icon'] }}"></i></div>
                        <div class="tsc-meta">
                            <span class="tsc-title">{{ $tableStats['news']['title'] }}</span>
                            <span class="tsc-table">Bảng: <code>{{ $tableStats['news']['table'] }}</code></span>
                        </div>
                        <div class="tsc-total">{{ $tableStats['news']['total'] }} <small>bài</small></div>
                    </div>
                    <div class="tsc-body">
                        <div class="tsc-row">
                            <span>Đã xuất bản</span>
                            <span class="badge success">{{ $tableStats['news']['published'] }} bài viết</span>
                        </div>
                        <div class="tsc-row">
                            <span>Tổng lượt đọc</span>
                            <strong>{{ number_format($tableStats['news']['total_views']) }} views</strong>
                        </div>
                        <div class="tsc-row">
                            <span>Số chuyên mục</span>
                            <span class="badge info">{{ $tableStats['news']['categories_count'] }} danh mục</span>
                        </div>
                    </div>
                    <div class="tsc-foot">
                        <a href="{{ $tableStats['news']['route'] }}">Quản lý tin tức <i class="ti ti-arrow-right"></i></a>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <div class="charts charts-30d">
        {{-- Biểu đồ Graph 30 ngày tương tác --}}
        @php
            $fallback30 = [
                8.5, 11.2, 9.8, 13.4, 15.0, 18.2, 16.5, 10.4, 12.1, 14.3,
                13.8, 17.5, 21.0, 19.4, 11.8, 13.2, 15.6, 14.9, 18.7, 22.4,
                20.1, 12.6, 14.5, 16.2, 15.8, 19.3, 24.5, 21.8, 15.4, 18.9,
            ];

            if (!empty($monthlyRevenueGraph) && is_array($monthlyRevenueGraph)) {
                $graphPointsRaw = array_values($monthlyRevenueGraph);
            } else {
                $graphPointsRaw = [];
                for ($i = 29; $i >= 0; $i--) {
                    $idx = 29 - $i;
                    $dObj = now()->subDays($i);
                    $valM = $fallback30[$idx] ?? 12.0;
                    $rev = $valM * 1000000;
                    $bRev = round($rev * 0.65);
                    $sRev = round($rev * 0.20);
                    $subRev = $rev - $bRev - $sRev;
                    $graphPointsRaw[] = [
                        'index' => $idx,
                        'date' => $dObj->toDateString(),
                        'label' => $dObj->format('d/m'),
                        'full_date' => $dObj->format('d/m/Y'),
                        'revenue' => $rev,
                        'revenue_million' => $valM,
                        'booking_revenue' => $bRev,
                        'sale_revenue' => $sRev,
                        'subscription_revenue' => $subRev,
                        'count' => max(1, (int) round($valM * 1.4)),
                    ];
                }
            }

            $totalPoints = count($graphPointsRaw);
            $millionsList = array_column($graphPointsRaw, 'revenue_million');
            $rawMaxM = !empty($millionsList) ? (float) max($millionsList) : 10.0;
            if ($rawMaxM <= 0) {
                $rawMaxM = 10.0;
            }

            // Làm tròn trần trục Y hợp lý (chia 4 mốc đều nhau)
            $stepM = match (true) {
                $rawMaxM <= 4 => 1.0,
                $rawMaxM <= 8 => 2.0,
                $rawMaxM <= 20 => 5.0,
                $rawMaxM <= 40 => 10.0,
                $rawMaxM <= 100 => 25.0,
                default => ceil(($rawMaxM / 4) / 10) * 10,
            };
            $yMaxM = max($stepM * 4, ceil($rawMaxM / $stepM) * $stepM);

            $svgW = 1040;
            $svgH = 280;
            $padL = 52;
            $padR = 22;
            $padT = 22;
            $padB = 36;
            $plotW = $svgW - $padL - $padR;
            $plotH = $svgH - $padT - $padB;
            $baseY = $padT + $plotH;

            $coords = [];
            foreach ($graphPointsRaw as $idx => $pt) {
                $x = $padL + ($totalPoints > 1 ? ($idx / ($totalPoints - 1)) * $plotW : $plotW / 2);
                $ratio = $yMaxM > 0 ? min(1, max(0, ((float) $pt['revenue_million']) / $yMaxM)) : 0;
                $y = $padT + $plotH - ($ratio * $plotH);
                $coords[] = array_merge($pt, [
                    'x' => round($x, 2),
                    'y' => round($y, 2),
                    'is_peak' => ((float) $pt['revenue_million'] >= $rawMaxM && $rawMaxM > 0),
                ]);
            }

            // Nội suy đường cong mượt (Catmull-Rom sang Cubic Bezier) và giữ trong giới hạn trục Y
            $linePath = '';
            $areaPath = '';
            if (!empty($coords)) {
                $linePath = "M {$coords[0]['x']},{$coords[0]['y']}";
                $n = count($coords);
                for ($i = 0; $i < $n - 1; $i++) {
                    $p0 = $coords[max(0, $i - 1)];
                    $p1 = $coords[$i];
                    $p2 = $coords[$i + 1];
                    $p3 = $coords[min($n - 1, $i + 2)];

                    $cp1x = round($p1['x'] + ($p2['x'] - $p0['x']) / 6, 2);
                    $cp1y = round(max($padT, min($baseY, $p1['y'] + ($p2['y'] - $p0['y']) / 6)), 2);
                    $cp2x = round($p2['x'] - ($p3['x'] - $p1['x']) / 6, 2);
                    $cp2y = round(max($padT, min($baseY, $p2['y'] - ($p3['y'] - $p1['y']) / 6)), 2);

                    $linePath .= " C {$cp1x},{$cp1y} {$cp2x},{$cp2y} {$p2['x']},{$p2['y']}";
                }
                $lastCoord = $coords[$n - 1];
                $firstCoord = $coords[0];
                $areaPath = $linePath . " L {$lastCoord['x']},{$baseY} L {$firstCoord['x']},{$baseY} Z";
            }

            $total30Revenue = array_sum(array_column($coords, 'revenue'));
            $avg30Revenue = $totalPoints > 0 ? round($total30Revenue / $totalPoints) : 0;
            $peakPoint = collect($coords)->sortByDesc('revenue')->first();
            $xTickIndices = [0, 4, 9, 14, 19, 24, 29];
        @endphp

        <div class="card graph-card" id="revenue30dCard">
            <div class="graph-card-head">
                <div class="graph-title-group">
                    <h3>
                        <i class="ti ti-chart-area-line"></i>
                        <span>Biểu đồ doanh thu 30 ngày gần nhất (triệu VNĐ)</span>
                    </h3>
                    <p class="graph-subtitle">
                        Di chuột qua đường biểu đồ hoặc từng điểm (point) để xem chi tiết doanh thu & số giao dịch theo ngày
                    </p>
                </div>

                <div class="graph-kpis">
                    <div class="graph-kpi-pill highlight">
                        <span>Tổng 30 ngày</span>
                        <strong>{{ number_format($total30Revenue / 1000000, 1, ',', '.') }} tr</strong>
                    </div>
                    <div class="graph-kpi-pill">
                        <span>Trung bình/ngày</span>
                        <strong>{{ number_format($avg30Revenue / 1000000, 1, ',', '.') }} tr</strong>
                    </div>
                    <div class="graph-kpi-pill">
                        <span>Đỉnh ngày ({{ $peakPoint['label'] ?? '—' }})</span>
                        <strong>{{ number_format((float) ($peakPoint['revenue_million'] ?? 0), 1, ',', '.') }} tr</strong>
                    </div>
                </div>
            </div>

            <div class="graph-stage" id="revenueGraphStage">
                {{-- Floating Interactive Tooltip --}}
                <div class="graph-tooltip" id="revenueGraphTooltip" role="status" aria-live="polite">
                    <div class="gt-head">
                        <span class="gt-date"><i class="ti ti-calendar-event"></i> <span data-gt-date>{{ $peakPoint['full_date'] ?? '' }}</span></span>
                        <span class="gt-badge" data-gt-count>{{ $peakPoint['count'] ?? 0 }} giao dịch</span>
                    </div>
                    <div class="gt-total">
                        <span data-gt-revenue>{{ number_format((float) ($peakPoint['revenue'] ?? 0), 0, ',', '.') }}đ</span>
                        <small data-gt-million>({{ number_format((float) ($peakPoint['revenue_million'] ?? 0), 2, ',', '.') }} triệu)</small>
                    </div>
                    <div class="gt-breakdown">
                        <div class="gt-row">
                            <span><i class="gt-dot" style="background: #EC4899;"></i>Đặt sân</span>
                            <strong data-gt-booking>{{ number_format((float) ($peakPoint['booking_revenue'] ?? 0), 0, ',', '.') }}đ</strong>
                        </div>
                        <div class="gt-row">
                            <span><i class="gt-dot" style="background: #F59E0B;"></i>Bán hàng</span>
                            <strong data-gt-sale>{{ number_format((float) ($peakPoint['sale_revenue'] ?? 0), 0, ',', '.') }}đ</strong>
                        </div>
                        <div class="gt-row">
                            <span><i class="gt-dot" style="background: #3B82F6;"></i>Gói hội viên</span>
                            <strong data-gt-sub>{{ number_format((float) ($peakPoint['subscription_revenue'] ?? 0), 0, ',', '.') }}đ</strong>
                        </div>
                    </div>
                </div>

                <svg class="revenue-30d-svg" id="revenue30dSvg" viewBox="0 0 {{ $svgW }} {{ $svgH }}" preserveAspectRatio="none" aria-label="Biểu đồ doanh thu 30 ngày qua">
                    <defs>
                        <linearGradient id="revenueAreaGrad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#EC4899" stop-opacity="0.32"/>
                            <stop offset="65%" stop-color="#EC4899" stop-opacity="0.08"/>
                            <stop offset="100%" stop-color="#EC4899" stop-opacity="0.0"/>
                        </linearGradient>
                        <linearGradient id="revenueLineGrad" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0%" stop-color="#DB2777"/>
                            <stop offset="50%" stop-color="#FF2E93"/>
                            <stop offset="100%" stop-color="#EC4899"/>
                        </linearGradient>
                    </defs>

                    {{-- Lưới ngang & Nhãn trục Y (5 mốc: 0%, 25%, 50%, 75%, 100%) --}}
                    @for ($stepIdx = 0; $stepIdx <= 4; $stepIdx++)
                        @php
                            $valY = ($yMaxM / 4) * $stepIdx;
                            $posY = round($baseY - ($stepIdx / 4) * $plotH, 2);
                        @endphp
                        <line
                            x1="{{ $padL }}"
                            y1="{{ $posY }}"
                            x2="{{ $svgW - $padR }}"
                            y2="{{ $posY }}"
                            class="graph-grid-line {{ $stepIdx === 0 ? 'baseline' : '' }}"
                        />
                        <text
                            x="{{ $padL - 8 }}"
                            y="{{ $posY + 3.5 }}"
                            text-anchor="end"
                            class="graph-axis-text"
                        >{{ number_format($valY, $valY == (int) $valY ? 0 : 1, ',', '.') }}tr</text>
                    @endfor

                    {{-- Vùng nền gradient dưới đường biểu đồ --}}
                    @if ($areaPath !== '')
                        <path d="{{ $areaPath }}" fill="url(#revenueAreaGrad)" class="graph-area-path"/>
                    @endif

                    {{-- Đường biểu đồ chính (Line) và vùng tương tác hover trên đường --}}
                    @if ($linePath !== '')
                        <path d="{{ $linePath }}" class="graph-line-path" id="revenueGraphLine"/>
                        <path d="{{ $linePath }}" class="graph-line-hit" id="revenueGraphLineHit"/>
                    @endif

                    {{-- Đường gióng dọc khi hover --}}
                    <line
                        id="revenueHoverGuide"
                        x1="{{ $coords[0]['x'] ?? $padL }}"
                        y1="{{ $padT }}"
                        x2="{{ $coords[0]['x'] ?? $padL }}"
                        y2="{{ $baseY }}"
                        class="graph-hover-guide"
                    />

                    {{-- Nhãn trục X phân bổ hợp lý (cách mỗi 5 ngày: 1, 5, 10, 15, 20, 25, 30) --}}
                    @foreach ($coords as $idx => $pt)
                        @if (in_array($idx, $xTickIndices, true) || $idx === $totalPoints - 1)
                            <text
                                x="{{ $pt['x'] }}"
                                y="{{ $svgH - 8 }}"
                                text-anchor="middle"
                                class="graph-x-text"
                                data-x-label-index="{{ $idx }}"
                            >{{ $pt['label'] }}</text>
                        @endif
                    @endforeach

                    {{-- 30 Points dữ liệu phân bố đều --}}
                    @foreach ($coords as $idx => $pt)
                        <g
                            class="graph-point-group"
                            data-point-index="{{ $idx }}"
                            data-x="{{ $pt['x'] }}"
                            data-y="{{ $pt['y'] }}"
                            data-date="{{ $pt['full_date'] }}"
                            data-label="{{ $pt['label'] }}"
                            data-revenue="{{ number_format((float) $pt['revenue'], 0, ',', '.') }}đ"
                            data-million="{{ number_format((float) $pt['revenue_million'], 2, ',', '.') }}"
                            data-booking="{{ number_format((float) $pt['booking_revenue'], 0, ',', '.') }}đ"
                            data-sale="{{ number_format((float) $pt['sale_revenue'], 0, ',', '.') }}đ"
                            data-sub="{{ number_format((float) $pt['subscription_revenue'], 0, ',', '.') }}đ"
                            data-count="{{ $pt['count'] }}"
                            tabindex="0"
                            role="button"
                            aria-label="Ngày {{ $pt['full_date'] }}: {{ number_format((float) $pt['revenue'], 0, ',', '.') }} đồng ({{ $pt['count'] }} giao dịch)"
                        >
                            <circle cx="{{ $pt['x'] }}" cy="{{ $pt['y'] }}" r="12" class="graph-point-halo"/>
                            <circle
                                cx="{{ $pt['x'] }}"
                                cy="{{ $pt['y'] }}"
                                r="{{ $pt['is_peak'] ? 5 : 3.8 }}"
                                class="graph-point-dot {{ $pt['is_peak'] ? 'is-peak' : '' }}"
                            />
                            <circle cx="{{ $pt['x'] }}" cy="{{ $pt['y'] }}" r="12" class="graph-point-hit">
                                <title>{{ $pt['full_date'] }}: {{ number_format((float) $pt['revenue'], 0, ',', '.') }}đ ({{ number_format((float) $pt['revenue_million'], 2, ',', '.') }} triệu - {{ $pt['count'] }} GD)</title>
                            </circle>
                        </g>
                    @endforeach
                </svg>
            </div>

            <div class="graph-foot-note">
                <div class="graph-legend-inline">
                    <span class="graph-legend-item">
                        <i class="legend-line-swatch"></i> Đường xu hướng doanh thu (30 ngày)
                    </span>
                    <span class="graph-legend-item">
                        <i class="legend-peak-swatch"></i> Điểm đạt đỉnh ({{ $peakPoint['full_date'] ?? '' }})
                    </span>
                </div>
                <a href="{{ route('admin.reports.revenue') }}" style="color: var(--primary); font-weight: 600; text-decoration: none;">
                    Xem báo cáo chi tiết <i class="ti ti-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Bảng đơn gần đây --}}
    <div class="section-title">Đơn đặt gần đây</div>
    <div class="table">
        <div class="row head">
            <div>Khách hàng</div><div>Sân</div><div>Khung giờ</div><div>Trạng thái</div>
        </div>
        @forelse ($recentBookings as $b)
            @php
                if (is_array($b)) {
                    $customer = $b['customer'] ?? '';
                    $court = $b['court'] ?? '';
                    $time = $b['time'] ?? '';
                    $badge = $b['badge'] ?? 'info';
                    $status = $b['status'] ?? '';
                } else {
                    $customer = $b->customer_name ?: ($b->user?->name ?? 'Khách lẻ');
                    $court = $b->court?->name ?? 'Sân #'.$b->court_id;
                    $time = substr((string) $b->start_time, 0, 5).'-'.substr((string) $b->end_time, 0, 5);
                    $badge = match ($b->status) {
                        'completed' => 'success',
                        'confirmed' => 'info',
                        'pending' => 'warning',
                        default => 'danger',
                    };
                    $status = match ($b->status) {
                        'completed' => 'Đã hoàn thành',
                        'confirmed' => 'Đã xác nhận',
                        'pending' => 'Chờ duyệt',
                        'cancelled' => 'Đã hủy',
                        'rejected' => 'Bị từ chối',
                        default => $b->status,
                    };
                }
            @endphp
            <div class="row">
                <div style="font-weight: 600;">{{ $customer }}</div>
                <div>{{ $court }}</div>
                <div>{{ $time }}</div>
                <div>
                    <span class="badge {{ $badge }}">{{ $status }}</span>
                </div>
            </div>
        @empty
            <div class="row" style="display: block; text-align: center; padding: 20px;">
                Chưa có đơn đặt sân nào.
            </div>
        @endforelse
    </div>
    @if(method_exists($recentBookings, 'links'))
        <div class="pagination-wrap">
            {{ $recentBookings->appends(request()->query())->links() }}
        </div>
    @endif

    {{-- Lịch sử hoạt động --}}
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
        @if(method_exists($activityLogs, 'links'))
            <div class="pagination-wrap">
                {{ $activityLogs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <script nonce="{{ $cspNonce ?? '' }}">
        document.addEventListener('DOMContentLoaded', () => {
            const stage = document.getElementById('revenueGraphStage');
            const svg = document.getElementById('revenue30dSvg');
            const tooltip = document.getElementById('revenueGraphTooltip');
            const guide = document.getElementById('revenueHoverGuide');

            if (!stage || !svg || !tooltip || !guide) {
                return;
            }

            const pointGroups = Array.from(svg.querySelectorAll('.graph-point-group'));
            const xLabels = Array.from(svg.querySelectorAll('[data-x-label-index]'));
            if (pointGroups.length === 0) {
                return;
            }

            const elDate = tooltip.querySelector('[data-gt-date]');
            const elCount = tooltip.querySelector('[data-gt-count]');
            const elRev = tooltip.querySelector('[data-gt-revenue]');
            const elMil = tooltip.querySelector('[data-gt-million]');
            const elBooking = tooltip.querySelector('[data-gt-booking]');
            const elSale = tooltip.querySelector('[data-gt-sale]');
            const elSub = tooltip.querySelector('[data-gt-sub]');

            const viewBoxWidth = 1040;
            const viewBoxHeight = 280;

            function activatePoint(group) {
                if (!group) {
                    return;
                }

                const idx = parseInt(group.dataset.pointIndex || '0', 10);
                const ptX = parseFloat(group.dataset.x || '0');
                const ptY = parseFloat(group.dataset.y || '0');

                pointGroups.forEach((g) => g.classList.toggle('is-active', g === group));
                xLabels.forEach((lbl) => {
                    const lblIdx = parseInt(lbl.dataset.xLabelIndex || '-1', 10);
                    lbl.classList.toggle('is-active', Math.abs(lblIdx - idx) <= 2);
                });

                guide.setAttribute('x1', String(ptX));
                guide.setAttribute('x2', String(ptX));

                if (elDate) elDate.textContent = group.dataset.date || '';
                if (elCount) elCount.textContent = `${group.dataset.count || 0} giao dịch`;
                if (elRev) elRev.textContent = group.dataset.revenue || '0đ';
                if (elMil) elMil.textContent = `(${group.dataset.million || '0'} triệu)`;
                if (elBooking) elBooking.textContent = group.dataset.booking || '0đ';
                if (elSale) elSale.textContent = group.dataset.sale || '0đ';
                if (elSub) elSub.textContent = group.dataset.sub || '0đ';

                stage.classList.add('is-hovering');
                tooltip.classList.add('is-visible');

                const stageRect = stage.getBoundingClientRect();
                const tooltipRect = tooltip.getBoundingClientRect();
                const ratioX = ptX / viewBoxWidth;
                const pixelX = ratioX * stageRect.width;
                const ratioY = ptY / viewBoxHeight;
                const pixelY = ratioY * stageRect.height;

                let leftPos = pixelX - tooltipRect.width / 2;
                const minLeft = 8;
                const maxLeft = Math.max(minLeft, stageRect.width - tooltipRect.width - 8);
                leftPos = Math.max(minLeft, Math.min(maxLeft, leftPos));

                let topPos = pixelY - tooltipRect.height - 16;
                if (topPos < 4) {
                    topPos = Math.min(stageRect.height - tooltipRect.height - 4, pixelY + 18);
                }

                tooltip.style.left = `${Math.round(leftPos)}px`;
                tooltip.style.top = `${Math.round(Math.max(4, topPos))}px`;
            }

            function deactivateGraph() {
                stage.classList.remove('is-hovering');
                tooltip.classList.remove('is-visible');
                pointGroups.forEach((g) => g.classList.remove('is-active'));
                xLabels.forEach((lbl) => lbl.classList.remove('is-active'));
            }

            pointGroups.forEach((group) => {
                group.addEventListener('mouseenter', () => activatePoint(group));
                group.addEventListener('focus', () => activatePoint(group));
                group.addEventListener('blur', deactivateGraph);
            });

            svg.addEventListener('mousemove', (e) => {
                const rect = svg.getBoundingClientRect();
                if (rect.width <= 0) {
                    return;
                }
                const relX = ((e.clientX - rect.left) / rect.width) * viewBoxWidth;

                let nearestGroup = pointGroups[0];
                let minDiff = Infinity;

                for (const g of pointGroups) {
                    const gx = parseFloat(g.dataset.x || '0');
                    const diff = Math.abs(gx - relX);
                    if (diff < minDiff) {
                        minDiff = diff;
                        nearestGroup = g;
                    }
                }

                activatePoint(nearestGroup);
            });

            stage.addEventListener('mouseleave', deactivateGraph);
        });
    </script>
@endsection