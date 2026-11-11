@extends('layouts.admin')
@section('title', 'Quản lý bình luận & đánh giá')

@section('content')
<div class="topbar">
    <div>
        <h1>Quản lý bình luận & đánh giá</h1>
        <p style="margin: 4px 0 0; font-size: 13px; color: var(--muted);">
            Kiểm duyệt bình luận bài viết tin tức và đánh giá sân thể thao từ người dùng
        </p>
    </div>
    <div class="right">
        <a href="{{ route('admin.comments.create', request('news_id') ? ['news_id' => request('news_id')] : []) }}" class="btn btn-primary">
            <i class="ti ti-plus"></i> Thêm bình luận tin tức
        </a>
    </div>
</div>

{{-- Thống kê nhanh --}}
@if(isset($stats))
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px;">
    <div class="table-card" style="padding: 16px 20px;">
        <div style="font-size: 12.5px; color: var(--muted); font-weight: 600;">Tổng bình luận Tin tức</div>
        <div style="font-size: 24px; font-weight: 800; color: var(--text); margin: 4px 0;">
            {{ $stats['total_news_comments'] }}
        </div>
        <div style="font-size: 12px; color: var(--muted);">
            <span style="color: #10B981; font-weight: 600;">{{ $stats['active_news_comments'] }} hiển thị</span>
            •
            <span style="color: #EF4444; font-weight: 600;">{{ $stats['hidden_news_comments'] }} đã ẩn</span>
        </div>
    </div>

    <div class="table-card" style="padding: 16px 20px;">
        <div style="font-size: 12.5px; color: var(--muted); font-weight: 600;">Điểm đánh giá TB (Tin tức)</div>
        <div style="font-size: 24px; font-weight: 800; color: #F59E0B; margin: 4px 0;">
            ★ {{ number_format($stats['avg_news_rating'], 1) }} <small style="font-size: 13px; color: var(--muted); font-weight: 500;">/ 5.0</small>
        </div>
        <div style="font-size: 12px; color: var(--muted);">Từ các lượt đánh giá bài viết</div>
    </div>

    <div class="table-card" style="padding: 16px 20px;">
        <div style="font-size: 12.5px; color: var(--muted); font-weight: 600;">Tổng đánh giá Sân thể thao</div>
        <div style="font-size: 24px; font-weight: 800; color: var(--text); margin: 4px 0;">
            {{ $stats['total_court_reviews'] }}
        </div>
        <div style="font-size: 12px; color: #F59E0B; font-weight: 600;">
            ★ {{ number_format($stats['avg_court_rating'], 1) }} / 5.0 trung bình toàn hệ thống
        </div>
    </div>
</div>
@endif

{{-- Tabs chuyển đổi giữa Bình luận Tin tức & Đánh giá Sân --}}
@php
    $currentTab = $activeTab ?? request('tab', 'news-comments');
@endphp
<div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 16px;">
    <div style="display: inline-flex; gap: 8px; background: var(--card-bg, #fff); padding: 5px; border-radius: 10px; border: 1px solid var(--border, #e5e7eb);">
        <a href="{{ route('admin.comments.index', ['tab' => 'news-comments']) }}"
           class="btn {{ $currentTab !== 'court-reviews' ? 'btn-primary' : 'btn-secondary' }}"
           style="padding: 7px 14px; font-size: 13px;">
            <i class="ti ti-news"></i> Bình luận & Review Tin tức ({{ $stats['total_news_comments'] ?? $comments->total() }})
        </a>
        <a href="{{ route('admin.comments.index', ['tab' => 'court-reviews']) }}"
           class="btn {{ $currentTab === 'court-reviews' ? 'btn-primary' : 'btn-secondary' }}"
           style="padding: 7px 14px; font-size: 13px;">
            <i class="ti ti-ball-football"></i> Đánh giá Sân thể thao ({{ $stats['total_court_reviews'] ?? 0 }})
        </a>
    </div>

    {{-- Bộ lọc tìm kiếm --}}
    <form method="GET" action="{{ route('admin.comments.index') }}" style="display: flex; align-items: center; flex-wrap: wrap; gap: 8px;">
        <input type="hidden" name="tab" value="{{ $currentTab }}">
        <input type="text" name="q" value="{{ $keyword ?? request('q') }}" class="form-control"
               placeholder="Tìm nội dung, người dùng..." style="width: 220px; padding: 7px 12px; font-size: 13px;">

        @if($currentTab !== 'court-reviews' && isset($newsList))
            <select name="news_id" class="form-control" style="max-width: 240px; padding: 7px 10px; font-size: 13px;">
                <option value="">-- Tất cả bài viết tin tức --</option>
                @foreach($newsList as $n)
                    <option value="{{ $n->id }}" @selected((string) ($selectedNewsId ?? request('news_id')) === (string) $n->id)>
                        {{ \Illuminate\Support\Str::limit($n->title, 42) }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="form-control" style="width: 140px; padding: 7px 10px; font-size: 13px;">
                <option value="">-- Trạng thái --</option>
                <option value="1" @selected(($selectedStatus ?? request('status')) === '1')>Hiển thị</option>
                <option value="0" @selected(($selectedStatus ?? request('status')) === '0')>Đã ẩn</option>
            </select>
        @endif

        <button type="submit" class="btn btn-primary" style="padding: 7px 14px; font-size: 13px;">Lọc</button>
        @if(request()->filled('q') || request()->filled('news_id') || request()->filled('status'))
            <a href="{{ route('admin.comments.index', ['tab' => $currentTab]) }}" class="btn btn-secondary" style="padding: 7px 12px; font-size: 13px;">Xóa lọc</a>
        @endif
    </form>
</div>

@if($currentTab === 'court-reviews' && isset($courtReviews))
    {{-- Bảng Đánh giá Sân thể thao (Bảng reviews) --}}
    <div class="table-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="width: 170px;">Người dùng</th>
                    <th style="width: 200px;">Sân thể thao</th>
                    <th>Nội dung đánh giá</th>
                    <th style="width: 100px;">Đánh giá</th>
                    <th style="width: 110px;">Trạng thái</th>
                    <th style="width: 120px;">Ngày tạo</th>
                    <th style="width: 100px; text-align: right;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($courtReviews as $r)
                <tr>
                    <td>#{{ $r->id }}</td>
                    <td>
                        <div style="font-weight: 600; color: var(--text);">{{ $r->user->name ?? '—' }}</div>
                        @if(!empty($r->user?->email))
                            <div style="font-size: 11.5px; color: var(--muted);">{{ $r->user->email }}</div>
                        @endif
                    </td>
                    <td>
                        @if($r->court)
                            <a href="{{ route('courts.show', $r->court->id) }}" target="_blank" style="font-weight: 600; color: var(--primary, #EC4899); text-decoration: none;">
                                {{ $r->court->name }} <i class="ti ti-external-link" style="font-size: 12px;"></i>
                            </a>
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit($r->content, 80) ?: '—' }}</td>
                    <td>
                        <span style="color: #F59E0B; font-weight: 700;">
                            ★ {{ $r->rating ?? 5 }}/5
                        </span>
                    </td>
                    <td>
                        @if ($r->status)
                            <span class="badge success">Hiển thị</span>
                        @else
                            <span class="badge danger">Đã ẩn</span>
                        @endif
                    </td>
                    <td style="font-size: 12.5px; color: var(--muted);">
                        {{ $r->created_at ? $r->created_at->format('d/m/Y') : '—' }}
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <form method="POST" action="{{ route('admin.reviews.destroy', $r) }}"
                              style="display:inline" data-confirm="Xóa đánh giá sân này?">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có đánh giá sân nào.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">{{ $courtReviews->links() }}</div>
@else
    {{-- Bảng Bình luận & Đánh giá Tin tức (Bảng comments) --}}
    <div class="table-card">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 55px;">ID</th>
                    <th style="width: 165px;">Người dùng</th>
                    <th style="width: 240px;">Bài viết tin tức</th>
                    <th>Nội dung bình luận</th>
                    <th style="width: 95px;">Đánh giá</th>
                    <th style="width: 115px;">Trạng thái</th>
                    <th style="width: 115px;">Ngày đăng</th>
                    <th style="width: 125px; text-align: right;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($comments as $c)
                <tr>
                    <td>#{{ $c->id }}</td>
                    <td>
                        <div style="font-weight: 600; color: var(--text);">{{ $c->user->name ?? '—' }}</div>
                        @if(!empty($c->user?->email))
                            <div style="font-size: 11.5px; color: var(--muted);">{{ $c->user->email }}</div>
                        @endif
                    </td>
                    <td>
                        @if ($c->news)
                            <a href="{{ route('news.show', $c->news->slug) }}#news-comments" target="_blank"
                               style="font-weight: 600; color: var(--text); text-decoration: none; display: block; line-height: 1.35;"
                               title="{{ $c->news->title }}">
                                {{ \Illuminate\Support\Str::limit($c->news->title, 55) }}
                                <i class="ti ti-external-link" style="font-size: 12px; color: var(--muted);"></i>
                            </a>
                            @if($c->news->category)
                                <span style="font-size: 11px; color: var(--muted);">{{ $c->news->category }}</span>
                            @endif
                        @else
                            <span style="color: var(--muted);">—</span>
                        @endif
                    </td>
                    <td>{{ \Illuminate\Support\Str::limit($c->content, 75) }}</td>
                    <td>
                        <span style="color: #F59E0B; font-weight: 700;">
                            ★ {{ $c->rating ?? 5 }}/5
                        </span>
                    </td>
                    <td>
                        @php
                            $isApproved = $c->status === true || $c->status === 1 || $c->status === '1' || $c->status === 'approved';
                        @endphp
                        @if ($isApproved)
                            <span class="badge success">Đã duyệt</span>
                        @else
                            <span class="badge danger">Đã ẩn</span>
                        @endif
                    </td>
                    <td style="font-size: 12.5px; color: var(--muted);">
                        {{ $c->created_at ? $c->created_at->format('d/m/Y H:i') : '—' }}
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <a href="{{ route('admin.comments.edit', $c) }}" class="action-link">Sửa</a>
                        <form method="POST" action="{{ route('admin.comments.destroy', $c) }}"
                              style="display:inline" data-confirm="Xóa bình luận này?">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="padding: 24px; text-align: center; color: var(--muted);">Chưa có bình luận nào.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">{{ $comments->links() }}</div>
@endif
@endsection