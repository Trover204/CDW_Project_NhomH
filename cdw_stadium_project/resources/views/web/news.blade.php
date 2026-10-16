@extends('web.layouts.app')

@section('title', 'Tin tức & Khuyến mãi — Sân Nhóm H')

@section('content')
<div style="font-family:Arial,Helvetica,sans-serif;max-width:1200px;margin:24px auto;padding:0 16px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <h1 style="font-size:22px;font-weight:700;color:#16241C;margin:0;">Tin tức &amp; mẹo chơi thể thao</h1>
    </div>

    @if(isset($news) && $news->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:20px;">
            @foreach($news as $item)
                <div style="border:0.5px solid #E1E8E3;border-radius:12px;overflow:hidden;background:#FFFFFF;display:flex;flex-direction:column;box-shadow:0 2px 6px rgba(0,0,0,0.02);">
                    <div style="height:140px;background:#E6F5EB;display:flex;align-items:center;justify-content:center;">
                        @if($item->thumbnail)
                            <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->title }}" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <i class="ti ti-news" style="font-size:36px;color:#178A4C;" aria-hidden="true"></i>
                        @endif
                    </div>
                    <div style="padding:16px;display:flex;flex-direction:column;flex:1;">
                        @if($item->category)
                            <span style="display:inline-block;align-self:flex-start;background:#FBEFE3;color:#C2570A;font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;margin-bottom:8px;">
                                {{ $item->category }}
                            </span>
                        @endif
                        <h3 style="font-size:15px;font-weight:700;color:#16241C;margin:0 0 8px;line-height:1.4;">
                            <a href="{{ route('news.show', $item->slug) }}" style="color:inherit;text-decoration:none;">
                                {{ $item->title }}
                            </a>
                        </h3>
                        <p style="font-size:13px;color:#6B7A70;margin:0 0 12px;flex:1;line-height:1.5;">
                            {{ Str::limit(strip_tags($item->summary ?? $item->content), 90) }}
                        </p>
                        <div style="font-size:11px;color:#8AA89B;display:flex;justify-content:space-between;align-items:center;border-top:1px solid #F0F4F1;padding-top:10px;">
                            <span>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '' }}</span>
                            <a href="{{ route('news.show', $item->slug) }}" style="color:#178A4C;font-weight:600;text-decoration:none;font-size:12px;">Đọc tiếp &rarr;</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:24px;">
            {{ $news->links() }}
        </div>
    @else
        <div style="text-align:center;padding:48px;background:#FFFFFF;border-radius:12px;border:1px solid #E1E8E3;">
            <p style="color:#6B7A70;font-size:14px;margin:0;">Chưa có bài viết nào được đăng.</p>
        </div>
    @endif
</div>
@endsection