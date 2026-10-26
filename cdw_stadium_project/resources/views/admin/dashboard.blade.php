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
    </style>

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

    <div class="charts">
        {{-- Biểu đồ cột --}}
        <div class="card">
            <h3>Doanh thu 7 ngày qua (triệu VNĐ)</h3>
            @php
                $revenueData = $weeklyRevenue ?? [8, 13, 10, 16, 12, 19, 14];
                $maxRevenue = !empty($revenueData) ? max($revenueData) : 1;
                if ($maxRevenue <= 0) $maxRevenue = 1;
            @endphp
            <div class="bars">
                @foreach ($revenueData as $val)
                    <div class="{{ $val === $maxRevenue ? 'max' : '' }}"
                         style="height: {{ round($val / $maxRevenue * 100) }}%"
                         title="{{ number_format($val, 1, ',', '.') }} triệu"></div>
                @endforeach
            </div>
        </div>

        {{-- Biểu đồ tròn bằng CSS --}}
        <div class="card">
            <h3>Cơ cấu môn thể thao theo số lượng sân</h3>
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
                <div class="donut" style="background: conic-gradient({{ !empty($parts) ? implode(', ', $parts) : '#0D5C40 0% 100%' }})"></div>
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
@endsection