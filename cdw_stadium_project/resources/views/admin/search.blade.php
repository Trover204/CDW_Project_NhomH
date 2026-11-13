@extends('layouts.admin')

@section('title', $keyword !== '' ? 'Tìm kiếm: ' . $keyword : 'Tìm kiếm hệ thống')

@section('content')
    <div class="topbar">
        <h1><i class="ti ti-search" style="color: var(--primary); margin-right: 6px;"></i>Tìm kiếm hệ thống</h1>
        <div class="right">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left"></i> Quay lại Dashboard
            </a>
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

    {{-- HERO SEARCH BANNER --}}
    @php
        $currentMode = $searchMode ?? request('mode', 'elasticsearch');
    @endphp
    <div class="admin-search-hero">
        <div class="admin-search-hero-top">
            <div>
                <h2 class="admin-search-hero-title">
                    @if ($keyword !== '')
                        Kết quả tìm kiếm cho <span class="keyword-pill">"{{ $keyword }}"</span>
                    @else
                        Tra cứu toàn bộ dữ liệu quản trị
                    @endif
                </h2>
                <p class="admin-search-hero-sub">
                    @if ($keyword !== '')
                        @if ($currentMode === 'normal')
                            Tìm thấy tổng cộng <strong>{{ $counts['all'] }}</strong> bản ghi phù hợp qua <strong>Tìm kiếm thường (SQL Database)</strong>
                        @else
                            Tìm thấy tổng cộng <strong>{{ $counts['all'] }}</strong> bản ghi phù hợp trên <strong>Elasticsearch / Laravel Scout</strong>
                        @endif
                    @else
                        Nhập từ khóa và chọn chế độ tìm kiếm (Elasticsearch hoặc Tìm kiếm thường) để tra cứu nhanh Sân thể thao, Cơ sở, Đơn đặt sân, Người dùng, Sản phẩm, Tin tức...
                    @endif
                </p>
            </div>
            <div class="admin-search-mode-switch" role="group" aria-label="Chuyển đổi chế độ tìm kiếm">
                <a
                    href="{{ route('admin.search', array_filter(['q' => $keyword, 'type' => $activeType !== 'all' ? $activeType : null, 'mode' => 'elasticsearch'])) }}"
                    class="mode-switch-pill {{ $currentMode === 'elasticsearch' ? 'active' : '' }}"
                >
                    <i class="ti ti-bolt"></i>
                    <span>Elasticsearch</span>
                </a>
                <a
                    href="{{ route('admin.search', array_filter(['q' => $keyword, 'type' => $activeType !== 'all' ? $activeType : null, 'mode' => 'normal'])) }}"
                    class="mode-switch-pill {{ $currentMode === 'normal' ? 'active' : '' }}"
                >
                    <i class="ti ti-database-search"></i>
                    <span>Tìm kiếm thường</span>
                </a>
            </div>
        </div>

        <form action="{{ route('admin.search') }}" method="GET" class="admin-search-hero-bar">
            @if ($activeType !== 'all')
                <input type="hidden" name="type" value="{{ $activeType }}">
            @endif
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.3-4.3"/>
            </svg>
            <input
                type="text"
                name="q"
                value="{{ $keyword }}"
                placeholder="Nhập tên sân, cơ sở, khách hàng, mã giao dịch, sản phẩm hoặc tin tức..."
                autofocus
            >
            <div class="hero-mode-divider"></div>
            <select name="mode" class="hero-mode-select" aria-label="{{ __('Chế độ tìm kiếm') }}">
                <option value="elasticsearch" @selected($currentMode === 'elasticsearch')>⚡ Elasticsearch</option>
                <option value="normal" @selected($currentMode === 'normal')>🗄️ Tìm kiếm thường (SQL)</option>
            </select>
            <button type="submit" class="hero-submit-btn">
                <i class="ti ti-search"></i> Tìm kiếm
            </button>
        </form>
    </div>

    @if ($keyword === '')
        <div class="admin-search-empty">
            <i class="ti ti-search"></i>
            <h3>Bắt đầu tìm kiếm trong trang Quản trị</h3>
            <p>Nhập từ khóa vào thanh tìm kiếm phía trên để tra cứu dữ liệu toàn hệ thống.</p>
        </div>
    @else
        {{-- FILTER TABS --}}
        @php
            $tabs = [
                'all' => ['label' => 'Tất cả kết quả', 'icon' => 'ti-apps'],
                'courts' => ['label' => 'Sân thể thao', 'icon' => 'ti-layout-grid'],
                'facilities' => ['label' => 'Cơ sở', 'icon' => 'ti-building'],
                'bookings' => ['label' => 'Đơn đặt sân', 'icon' => 'ti-calendar-event'],
                'products' => ['label' => 'Sản phẩm', 'icon' => 'ti-shopping-cart'],
                'sales' => ['label' => 'Đơn bán hàng', 'icon' => 'ti-receipt'],
                'payments' => ['label' => 'Thanh toán', 'icon' => 'ti-credit-card'],
                'users' => ['label' => 'Người dùng', 'icon' => 'ti-users'],
                'news' => ['label' => 'Tin tức', 'icon' => 'ti-news'],
                'sport_types' => ['label' => 'Môn thể thao', 'icon' => 'ti-ball-football'],
                'plans' => ['label' => 'Gói hội viên', 'icon' => 'ti-crown'],
                'subscriptions' => ['label' => 'Đăng ký gói', 'icon' => 'ti-id-badge-2'],
                'reviews' => ['label' => 'Đánh giá', 'icon' => 'ti-star'],
                'ads' => ['label' => 'Quảng cáo', 'icon' => 'ti-photo'],
            ];
        @endphp

        @if ($counts['all'] > 0)
            <div class="admin-search-tabs">
                @foreach ($tabs as $typeKey => $tab)
                    @if ($typeKey === 'all' || ($counts[$typeKey] ?? 0) > 0 || $activeType === $typeKey)
                        <a
                            href="{{ route('admin.search', ['q' => $keyword, 'type' => $typeKey, 'mode' => $currentMode]) }}"
                            class="admin-search-tab {{ $activeType === $typeKey ? 'active' : '' }}"
                        >
                            <i class="ti {{ $tab['icon'] }}"></i>
                            <span>{{ $tab['label'] }}</span>
                            <span class="tab-count">{{ $counts[$typeKey] ?? 0 }}</span>
                        </a>
                    @endif
                @endforeach
            </div>
        @endif

        @if (($counts[$activeType] ?? 0) === 0)
            <div class="admin-search-empty">
                <i class="ti ti-mood-empty"></i>
                <h3>Không tìm thấy kết quả nào cho "{{ $keyword }}"</h3>
                <p>Hãy thử kiểm tra lại chính tả hoặc tìm kiếm với từ khóa ngắn gọn hơn.</p>
            </div>
        @else
            {{-- 1. SÂN THỂ THAO (COURTS) - Chỉ hiển thị khi có dữ liệu tìm thấy --}}
            @if (($activeType === 'all' || $activeType === 'courts') && $courts->isNotEmpty())
                <div class="admin-search-section">
                    <div class="admin-search-section-head">
                        <h3 class="admin-search-section-title">
                            <span class="sec-icon"><i class="ti ti-layout-grid"></i></span>
                            <span>Sân thể thao</span>
                            <span class="sec-badge">{{ $courts->total() }} kết quả</span>
                        </h3>
                        <a href="{{ route('admin.courts.index') }}" class="btn btn-secondary btn-sm">
                            Quản lý tất cả sân <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-card">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 55px;">ID</th>
                                    <th>Sân thể thao</th>
                                    <th>Cơ sở / Khu vực</th>
                                    <th>Loại môn</th>
                                    <th>Giá / Giờ</th>
                                    <th>Sức chứa</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 150px; text-align: right;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courts as $court)
                                    <tr>
                                        <td>#{{ $court->id }}</td>
                                        <td>
                                            <div class="admin-search-item-cell">
                                                @if ($court->images && $court->images->isNotEmpty())
                                                    @php
                                                        $primaryImg = $court->images->firstWhere('is_primary', true) ?? $court->images->first();
                                                    @endphp
                                                    <img src="{{ asset($primaryImg->image_path) }}" alt="{{ $court->name }}" class="admin-search-thumb">
                                                @else
                                                    <span class="admin-search-thumb"><i class="ti ti-ball-football"></i></span>
                                                @endif
                                                <div>
                                                    <div style="font-weight: 700; color: var(--text);">{{ $court->name }}</div>
                                                    <div style="font-size: 11.5px; color: var(--muted);">{{ Str::limit($court->description, 45) ?: 'Chưa có mô tả' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-weight: 600;">{{ $court->facility->name ?? '—' }}</div>
                                            <div style="font-size: 11.5px; color: var(--muted);">{{ $court->facility->district ?? $court->facility->address ?? '' }}</div>
                                        </td>
                                        <td><span class="badge info">{{ $court->sportType->name ?? '—' }}</span></td>
                                        <td style="font-weight: 600; color: var(--primary);">
                                            {{ number_format($court->price ?? 150000, 0, ',', '.') }}đ/h
                                        </td>
                                        <td>{{ $court->capacity ?? 0 }} người</td>
                                        <td>
                                            @if ($court->status === 'available')
                                                <span class="badge success">Sẵn sàng</span>
                                            @elseif ($court->status === 'maintenance')
                                                <span class="badge warning">Bảo trì</span>
                                            @else
                                                <span class="badge danger">Tạm ẩn</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right; white-space: nowrap;">
                                            <a href="{{ route('courts.show', $court->id) }}" class="action-link" target="_blank">Xem</a>
                                            <a href="{{ route('admin.courts.edit', $court) }}" class="action-link">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($courts->hasPages())
                        <div class="pagination-wrap">{{ $courts->links() }}</div>
                    @endif
                </div>
            @endif

            {{-- 2. CƠ SỞ & CHI NHÁNH (FACILITIES) - Chỉ hiển thị khi có dữ liệu tìm thấy --}}
            @if (($activeType === 'all' || $activeType === 'facilities') && $facilities->isNotEmpty())
                <div class="admin-search-section">
                    <div class="admin-search-section-head">
                        <h3 class="admin-search-section-title">
                            <span class="sec-icon"><i class="ti ti-building"></i></span>
                            <span>Cơ sở & Chi nhánh</span>
                            <span class="sec-badge">{{ $facilities->total() }} kết quả</span>
                        </h3>
                        <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary btn-sm">
                            Quản lý cơ sở <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-card">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 55px;">ID</th>
                                    <th>Tên cơ sở</th>
                                    <th>Quận / Khu vực</th>
                                    <th>Địa chỉ</th>
                                    <th>Điện thoại</th>
                                    <th>Số sân</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 120px; text-align: right;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($facilities as $facility)
                                    <tr>
                                        <td>#{{ $facility->id }}</td>
                                        <td style="font-weight: 700; color: var(--text);">{{ $facility->name }}</td>
                                        <td>{{ $facility->district ?: '—' }}</td>
                                        <td>{{ Str::limit($facility->address, 50) ?: '—' }}</td>
                                        <td>{{ $facility->phone ?: '—' }}</td>
                                        <td><strong>{{ $facility->courts_count ?? 0 }}</strong> sân</td>
                                        <td>
                                            @if ($facility->status)
                                                <span class="badge success">Hoạt động</span>
                                            @else
                                                <span class="badge danger">Ngừng</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right; white-space: nowrap;">
                                            <a href="{{ route('admin.facilities.edit', $facility) }}" class="action-link">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($facilities->hasPages())
                        <div class="pagination-wrap">{{ $facilities->links() }}</div>
                    @endif
                </div>
            @endif

            {{-- 3. ĐƠN ĐẶT SÂN (BOOKINGS) - Chỉ hiển thị khi có dữ liệu tìm thấy --}}
            @if (($activeType === 'all' || $activeType === 'bookings') && $bookings->isNotEmpty())
                <div class="admin-search-section">
                    <div class="admin-search-section-head">
                        <h3 class="admin-search-section-title">
                            <span class="sec-icon"><i class="ti ti-calendar-event"></i></span>
                            <span>Đơn đặt sân</span>
                            <span class="sec-badge">{{ $bookings->total() }} kết quả</span>
                        </h3>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">
                            Quản lý đơn đặt sân <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-card">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 55px;">ID</th>
                                    <th>Khách hàng</th>
                                    <th>Sân</th>
                                    <th>Ngày & Giờ</th>
                                    <th>Tổng tiền</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 120px; text-align: right;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookings as $booking)
                                    <tr>
                                        <td>#{{ $booking->id }}</td>
                                        <td>
                                            <div style="font-weight: 700; color: var(--text);">{{ $booking->customer_name ?: ($booking->user->name ?? '—') }}</div>
                                            <div style="font-size: 11.5px; color: var(--muted);">{{ $booking->customer_phone ?: ($booking->customer_email ?? '') }}</div>
                                        </td>
                                        <td>{{ $booking->court->name ?? '—' }}</td>
                                        <td>
                                            <div>{{ $booking->booking_date?->format('d/m/Y') ?? '—' }}</div>
                                            <div style="font-size: 11.5px; color: var(--muted);">{{ substr((string) $booking->start_time, 0, 5) }} - {{ substr((string) $booking->end_time, 0, 5) }}</div>
                                        </td>
                                        <td style="font-weight: 600; color: var(--primary);">
                                            {{ number_format((float) $booking->total_price, 0, ',', '.') }}đ
                                        </td>
                                        <td>
                                            @if (in_array($booking->status, ['confirmed', 'completed'], true))
                                                <span class="badge success">{{ $booking->status === 'completed' ? 'Hoàn thành' : 'Đã xác nhận' }}</span>
                                            @elseif ($booking->status === 'pending')
                                                <span class="badge warning">Chờ duyệt</span>
                                            @else
                                                <span class="badge danger">Đã hủy</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right; white-space: nowrap;">
                                            <a href="{{ route('admin.bookings.edit', $booking) }}" class="action-link">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($bookings->hasPages())
                        <div class="pagination-wrap">{{ $bookings->links() }}</div>
                    @endif
                </div>
            @endif

            {{-- 4. SẢN PHẨM & PHỤ KIỆN (PRODUCTS) - Chỉ hiển thị khi có dữ liệu tìm thấy --}}
            @if (($activeType === 'all' || $activeType === 'products') && $products->isNotEmpty())
                <div class="admin-search-section">
                    <div class="admin-search-section-head">
                        <h3 class="admin-search-section-title">
                            <span class="sec-icon"><i class="ti ti-shopping-cart"></i></span>
                            <span>Sản phẩm & Phụ kiện</span>
                            <span class="sec-badge">{{ $products->total() }} kết quả</span>
                        </h3>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
                            Quản lý sản phẩm <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-card">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 55px;">ID</th>
                                    <th>Sản phẩm</th>
                                    <th>Đội / CLB</th>
                                    <th>Mùa giải</th>
                                    <th>Giá bán</th>
                                    <th>Tồn kho</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 120px; text-align: right;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>#{{ $product->id }}</td>
                                        <td>
                                            <div class="admin-search-item-cell">
                                                @if ($product->image)
                                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="admin-search-thumb">
                                                @else
                                                    <span class="admin-search-thumb"><i class="ti ti-shirt-sport"></i></span>
                                                @endif
                                                <div>
                                                    <div style="font-weight: 700; color: var(--text);">{{ $product->name }}</div>
                                                    <div style="font-size: 11.5px; color: var(--muted);">{{ Str::limit($product->description, 45) ?: '—' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $product->team ?: '—' }}</td>
                                        <td>{{ $product->season ?: '—' }}</td>
                                        <td style="font-weight: 600; color: var(--primary);">
                                            {{ number_format((float) $product->cost_price, 0, ',', '.') }}đ
                                        </td>
                                        <td>{{ $product->stock_quantity ?? 0 }}</td>
                                        <td>
                                            @if ($product->is_active && $product->is_in_stock)
                                                <span class="badge success">Còn hàng</span>
                                            @elseif ($product->is_active)
                                                <span class="badge warning">Hết hàng</span>
                                            @else
                                                <span class="badge danger">Đang ẩn</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right; white-space: nowrap;">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="action-link">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($products->hasPages())
                        <div class="pagination-wrap">{{ $products->links() }}</div>
                    @endif
                </div>
            @endif

            {{-- 5. ĐƠN BÁN HÀNG (SALES) - Chỉ hiển thị khi có dữ liệu tìm thấy --}}
            @if (($activeType === 'all' || $activeType === 'sales') && $sales->isNotEmpty())
                <div class="admin-search-section">
                    <div class="admin-search-section-head">
                        <h3 class="admin-search-section-title">
                            <span class="sec-icon"><i class="ti ti-receipt"></i></span>
                            <span>Đơn bán hàng</span>
                            <span class="sec-badge">{{ $sales->total() }} kết quả</span>
                        </h3>
                        <a href="{{ route('admin.sales.index') }}" class="btn btn-secondary btn-sm">
                            Quản lý đơn bán hàng <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-card">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 55px;">ID</th>
                                    <th>Khách hàng</th>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Tổng tiền</th>
                                    <th>Ngày bán</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 120px; text-align: right;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $sale)
                                    <tr>
                                        <td>#{{ $sale->id }}</td>
                                        <td>
                                            <div style="font-weight: 700; color: var(--text);">{{ $sale->customer_name ?: ($sale->user->name ?? '—') }}</div>
                                            <div style="font-size: 11.5px; color: var(--muted);">{{ $sale->customer_phone ?: '—' }}</div>
                                        </td>
                                        <td>{{ $sale->product->name ?? '—' }}</td>
                                        <td>{{ $sale->quantity }}</td>
                                        <td style="font-weight: 600; color: var(--primary);">
                                            {{ number_format((float) $sale->total_price, 0, ',', '.') }}đ
                                        </td>
                                        <td>{{ $sale->sale_date?->format('d/m/Y') ?? '—' }}</td>
                                        <td>
                                            @if ($sale->status === 'completed')
                                                <span class="badge success">Hoàn thành</span>
                                            @elseif ($sale->status === 'pending')
                                                <span class="badge warning">Chờ xử lý</span>
                                            @else
                                                <span class="badge danger">Đã hủy</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right; white-space: nowrap;">
                                            <a href="{{ route('admin.sales.edit', $sale) }}" class="action-link">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($sales->hasPages())
                        <div class="pagination-wrap">{{ $sales->links() }}</div>
                    @endif
                </div>
            @endif

            {{-- 6. GIAO DỊCH THANH TOÁN (PAYMENTS) - Chỉ hiển thị khi có dữ liệu tìm thấy --}}
            @if (($activeType === 'all' || $activeType === 'payments') && $payments->isNotEmpty())
                <div class="admin-search-section">
                    <div class="admin-search-section-head">
                        <h3 class="admin-search-section-title">
                            <span class="sec-icon"><i class="ti ti-credit-card"></i></span>
                            <span>Giao dịch thanh toán</span>
                            <span class="sec-badge">{{ $payments->total() }} kết quả</span>
                        </h3>
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm">
                            Quản lý thanh toán <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-card">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 55px;">ID</th>
                                    <th>Mã giao dịch</th>
                                    <th>Khách hàng</th>
                                    <th>Loại</th>
                                    <th>Phương thức</th>
                                    <th>Số tiền</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 120px; text-align: right;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>#{{ $payment->id }}</td>
                                        <td style="font-weight: 700; color: var(--text);"><code>{{ $payment->transaction_code ?: '—' }}</code></td>
                                        <td>{{ $payment->user->name ?? 'Khách lẻ' }}</td>
                                        <td><span class="badge info">{{ $payment->type_label }}</span></td>
                                        <td>{{ $payment->method_label }}</td>
                                        <td style="font-weight: 600; color: var(--primary);">
                                            {{ number_format((float) $payment->amount, 0, ',', '.') }}đ
                                        </td>
                                        <td>
                                            <span class="badge {{ $payment->status_badge }}">{{ $payment->status_label }}</span>
                                        </td>
                                        <td style="text-align: right; white-space: nowrap;">
                                            <a href="{{ route('admin.payments.edit', $payment) }}" class="action-link">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($payments->hasPages())
                        <div class="pagination-wrap">{{ $payments->links() }}</div>
                    @endif
                </div>
            @endif

            {{-- 7. NGƯỜI DÙNG (USERS) - Chỉ hiển thị khi có dữ liệu tìm thấy --}}
            @if (($activeType === 'all' || $activeType === 'users') && $users->isNotEmpty())
                <div class="admin-search-section">
                    <div class="admin-search-section-head">
                        <h3 class="admin-search-section-title">
                            <span class="sec-icon"><i class="ti ti-users"></i></span>
                            <span>Người dùng</span>
                            <span class="sec-badge">{{ $users->total() }} kết quả</span>
                        </h3>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                            Quản lý người dùng <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-card">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 55px;">ID</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Điện thoại</th>
                                    <th>Vai trò</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 120px; text-align: right;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $userItem)
                                    <tr>
                                        <td>#{{ $userItem->id }}</td>
                                        <td style="font-weight: 700; color: var(--text);">{{ $userItem->name }}</td>
                                        <td>{{ $userItem->email }}</td>
                                        <td>{{ $userItem->phone ?: '—' }}</td>
                                        <td><span class="badge info">{{ strtoupper((string) $userItem->role) }}</span></td>
                                        <td>
                                            @if ($userItem->status)
                                                <span class="badge success">Hoạt động</span>
                                            @else
                                                <span class="badge danger">Đã khóa</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right; white-space: nowrap;">
                                            <a href="{{ route('admin.users.edit', $userItem) }}" class="action-link">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($users->hasPages())
                        <div class="pagination-wrap">{{ $users->links() }}</div>
                    @endif
                </div>
            @endif

            {{-- 8. TIN TỨC & BÀI VIẾT (NEWS) - Chỉ hiển thị khi có dữ liệu tìm thấy --}}
            @if (($activeType === 'all' || $activeType === 'news') && $news->isNotEmpty())
                <div class="admin-search-section">
                    <div class="admin-search-section-head">
                        <h3 class="admin-search-section-title">
                            <span class="sec-icon"><i class="ti ti-news"></i></span>
                            <span>Tin tức & Bài viết</span>
                            <span class="sec-badge">{{ $news->total() }} kết quả</span>
                        </h3>
                        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary btn-sm">
                            Quản lý tin tức <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-card">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 55px;">ID</th>
                                    <th>Bài viết</th>
                                    <th>Danh mục</th>
                                    <th>Tác giả</th>
                                    <th>Lượt xem</th>
                                    <th>Ngày tạo</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 150px; text-align: right;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($news as $item)
                                    <tr>
                                        <td>#{{ $item->id }}</td>
                                        <td>
                                            <div class="admin-search-item-cell">
                                                @if ($item->image_url)
                                                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="admin-search-thumb">
                                                @else
                                                    <span class="admin-search-thumb"><i class="ti ti-news"></i></span>
                                                @endif
                                                <div>
                                                    <div style="font-weight: 700; color: var(--text);">{{ $item->title }}</div>
                                                    <div style="font-size: 11.5px; color: var(--muted);">{{ Str::limit($item->summary, 55) ?: '—' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge info">{{ $item->category ?: 'Tin tức' }}</span></td>
                                        <td>{{ $item->user->name ?? 'Admin' }}</td>
                                        <td>{{ number_format((int) $item->views) }}</td>
                                        <td>{{ $item->created_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td>
                                            @if ($item->status === 'published' || $item->status === 1)
                                                <span class="badge success">Đã xuất bản</span>
                                            @else
                                                <span class="badge warning">Bản nháp</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right; white-space: nowrap;">
                                            @if ($item->slug)
                                                <a href="{{ route('news.show', $item->slug) }}" class="action-link" target="_blank">Xem</a>
                                            @endif
                                            <a href="{{ route('admin.news.edit', $item) }}" class="action-link">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($news->hasPages())
                        <div class="pagination-wrap">{{ $news->links() }}</div>
                    @endif
                </div>
            @endif

            {{-- 9. LOẠI MÔN THỂ THAO (SPORT TYPES) - Chỉ hiển thị khi có dữ liệu tìm thấy --}}
            @if (($activeType === 'all' || $activeType === 'sport_types') && $sportTypes->isNotEmpty())
                <div class="admin-search-section">
                    <div class="admin-search-section-head">
                        <h3 class="admin-search-section-title">
                            <span class="sec-icon"><i class="ti ti-ball-football"></i></span>
                            <span>Loại môn thể thao</span>
                            <span class="sec-badge">{{ $sportTypes->total() }} kết quả</span>
                        </h3>
                        <a href="{{ route('admin.sport-types.index') }}" class="btn btn-secondary btn-sm">
                            Quản lý môn thể thao <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-card">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 55px;">ID</th>
                                    <th>Tên môn thể thao</th>
                                    <th>Mô tả</th>
                                    <th>Số sân</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 120px; text-align: right;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sportTypes as $sportType)
                                    <tr>
                                        <td>#{{ $sportType->id }}</td>
                                        <td style="font-weight: 700; color: var(--text);">{{ $sportType->name }}</td>
                                        <td>{{ Str::limit($sportType->description, 70) ?: '—' }}</td>
                                        <td><strong>{{ $sportType->courts_count ?? 0 }}</strong> sân</td>
                                        <td>
                                            @if ($sportType->status)
                                                <span class="badge success">Hoạt động</span>
                                            @else
                                                <span class="badge danger">Tạm ẩn</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right; white-space: nowrap;">
                                            <a href="{{ route('admin.sport-types.edit', $sportType) }}" class="action-link">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($sportTypes->hasPages())
                        <div class="pagination-wrap">{{ $sportTypes->links() }}</div>
                    @endif
                </div>
            @endif

            {{-- 10. GÓI THÀNH VIÊN (PLANS) - Chỉ hiển thị khi có dữ liệu tìm thấy --}}
            @if (($activeType === 'all' || $activeType === 'plans') && $plans->isNotEmpty())
                <div class="admin-search-section">
                    <div class="admin-search-section-head">
                        <h3 class="admin-search-section-title">
                            <span class="sec-icon"><i class="ti ti-crown"></i></span>
                            <span>Gói thành viên</span>
                            <span class="sec-badge">{{ $plans->total() }} kết quả</span>
                        </h3>
                        <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary btn-sm">
                            Quản lý gói <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-card">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 55px;">ID</th>
                                    <th>Tên gói</th>
                                    <th>Thời hạn</th>
                                    <th>Giá gói</th>
                                    <th>Ưu đãi giảm</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 120px; text-align: right;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plans as $plan)
                                    <tr>
                                        <td>#{{ $plan->id }}</td>
                                        <td style="font-weight: 700; color: var(--text);">{{ $plan->name }}</td>
                                        <td>{{ $plan->duration_days }} ngày</td>
                                        <td style="font-weight: 600; color: var(--primary);">
                                            {{ number_format((float) $plan->price, 0, ',', '.') }}đ
                                        </td>
                                        <td><span class="badge info">-{{ $plan->discount_percent }}%</span></td>
                                        <td>
                                            @if ($plan->status)
                                                <span class="badge success">Hoạt động</span>
                                            @else
                                                <span class="badge danger">Tạm ẩn</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right; white-space: nowrap;">
                                            <a href="{{ route('admin.plans.edit', $plan) }}" class="action-link">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($plans->hasPages())
                        <div class="pagination-wrap">{{ $plans->links() }}</div>
                    @endif
                </div>
            @endif

            {{-- 11. ĐĂNG KÝ GÓI (SUBSCRIPTIONS) - Chỉ hiển thị khi có dữ liệu tìm thấy --}}
            @if (($activeType === 'all' || $activeType === 'subscriptions') && $subscriptions->isNotEmpty())
                <div class="admin-search-section">
                    <div class="admin-search-section-head">
                        <h3 class="admin-search-section-title">
                            <span class="sec-icon"><i class="ti ti-id-badge-2"></i></span>
                            <span>Đăng ký gói thành viên</span>
                            <span class="sec-badge">{{ $subscriptions->total() }} kết quả</span>
                        </h3>
                        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary btn-sm">
                            Quản lý đăng ký gói <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-card">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 55px;">ID</th>
                                    <th>Khách hàng</th>
                                    <th>Gói đăng ký</th>
                                    <th>Ngày bắt đầu</th>
                                    <th>Ngày hết hạn</th>
                                    <th>Giá</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 120px; text-align: right;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscriptions as $sub)
                                    <tr>
                                        <td>#{{ $sub->id }}</td>
                                        <td style="font-weight: 700; color: var(--text);">{{ $sub->user->name ?? '—' }}</td>
                                        <td><span class="badge info">{{ $sub->plan->name ?? '—' }}</span></td>
                                        <td>{{ $sub->starts_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td>{{ $sub->expires_at?->format('d/m/Y') ?? '—' }}</td>
                                        <td style="font-weight: 600; color: var(--primary);">
                                            {{ number_format((float) $sub->price, 0, ',', '.') }}đ
                                        </td>
                                        <td>
                                            @if ($sub->status)
                                                <span class="badge success">Đang hiệu lực</span>
                                            @else
                                                <span class="badge danger">Hết hạn</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right; white-space: nowrap;">
                                            <a href="{{ route('admin.subscriptions.edit', $sub) }}" class="action-link">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($subscriptions->hasPages())
                        <div class="pagination-wrap">{{ $subscriptions->links() }}</div>
                    @endif
                </div>
            @endif

            {{-- 12. ĐÁNH GIÁ & BÌNH LUẬN (REVIEWS) - Chỉ hiển thị khi có dữ liệu tìm thấy --}}
            @if (($activeType === 'all' || $activeType === 'reviews') && $reviews->isNotEmpty())
                <div class="admin-search-section">
                    <div class="admin-search-section-head">
                        <h3 class="admin-search-section-title">
                            <span class="sec-icon"><i class="ti ti-star"></i></span>
                            <span>Đánh giá & Bình luận</span>
                            <span class="sec-badge">{{ $reviews->total() }} kết quả</span>
                        </h3>
                        <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary btn-sm">
                            Quản lý đánh giá <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-card">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 55px;">ID</th>
                                    <th>Người dùng</th>
                                    <th>Sân</th>
                                    <th>Số sao</th>
                                    <th>Nội dung</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $review)
                                    <tr>
                                        <td>#{{ $review->id }}</td>
                                        <td style="font-weight: 700; color: var(--text);">{{ $review->user->name ?? '—' }}</td>
                                        <td>{{ $review->court->name ?? '—' }}</td>
                                        <td><strong>{{ $review->rating }}/5</strong> ⭐</td>
                                        <td>{{ Str::limit($review->content, 70) ?: '—' }}</td>
                                        <td>
                                            @if ($review->status)
                                                <span class="badge success">Hiển thị</span>
                                            @else
                                                <span class="badge warning">Đã ẩn</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($reviews->hasPages())
                        <div class="pagination-wrap">{{ $reviews->links() }}</div>
                    @endif
                </div>
            @endif

            {{-- 13. QUẢNG CÁO (ADS) - Chỉ hiển thị khi có dữ liệu tìm thấy --}}
            @if (($activeType === 'all' || $activeType === 'ads') && $ads->isNotEmpty())
                <div class="admin-search-section">
                    <div class="admin-search-section-head">
                        <h3 class="admin-search-section-title">
                            <span class="sec-icon"><i class="ti ti-photo"></i></span>
                            <span>Quảng cáo</span>
                            <span class="sec-badge">{{ $ads->total() }} kết quả</span>
                        </h3>
                        <a href="{{ route('admin.ads.index') }}" class="btn btn-secondary btn-sm">
                            Quản lý quảng cáo <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>

                    <div class="table-card">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 55px;">ID</th>
                                    <th>Tên chiến dịch / Banner</th>
                                    <th>Đường dẫn</th>
                                    <th>Mô tả</th>
                                    <th style="width: 120px;">Trạng thái</th>
                                    <th style="width: 120px; text-align: right;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ads as $ad)
                                    <tr>
                                        <td>#{{ $ad->id }}</td>
                                        <td style="font-weight: 700; color: var(--text);">{{ $ad->name }}</td>
                                        <td><code>{{ $ad->link_url ?: '—' }}</code></td>
                                        <td>{{ Str::limit($ad->description, 60) ?: '—' }}</td>
                                        <td>
                                            @if ($ad->is_active)
                                                <span class="badge success">Đang bật</span>
                                            @else
                                                <span class="badge danger">Đang tắt</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right; white-space: nowrap;">
                                            <a href="{{ route('admin.ads.edit', $ad) }}" class="action-link">Sửa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($ads->hasPages())
                        <div class="pagination-wrap">{{ $ads->links() }}</div>
                    @endif
                </div>
            @endif
        @endif
    @endif
@endsection
