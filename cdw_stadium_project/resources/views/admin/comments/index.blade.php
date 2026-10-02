@extends('layouts.admin')
@section('title', 'Bình luận')

@section('content')
    <div class="topbar">
        <h1>Quản lý bình luận</h1>
        <div class="right">
            <a href="{{ route('admin.comments.create') }}">+ Thêm bình luận</a>
        </div>
    </div>

    @if (session('success'))
        <div class="card" style="color:#178A4C">{{ session('success') }}</div>
    @endif

    <div class="table">
        <div class="row head">
            <div>Người dùng</div><div>Sân</div><div>Nội dung</div><div>Sao</div><div>Trạng thái</div><div>Thao tác</div>
        </div>

        @forelse ($comments as $c)
            <div class="row">
                <div>{{ $c->user->name ?? '—' }}</div>
                <div>{{ $c->court->name ?? '—' }}</div>
                <div>{{ \Illuminate\Support\Str::limit($c->content, 50) }}</div>
                <div>{{ $c->rating ?? '—' }}</div>
                <div>
                    <span class="badge {{ ['approved'=>'success','pending'=>'warning','hidden'=>'danger'][$c->status] }}">
                        {{ ['approved'=>'Đã duyệt','pending'=>'Chờ duyệt','hidden'=>'Ẩn'][$c->status] }}
                    </span>
                </div>
                <div>
                    <a href="{{ route('admin.comments.edit', $c) }}">Sửa</a>
                    <form method="POST" action="{{ route('admin.comments.destroy', $c) }}"
                          style="display:inline" onsubmit="return confirm('Xóa bình luận này?')">
                        @csrf @method('DELETE')
                        <button type="submit">Xóa</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="row"><div>Chưa có bình luận nào.</div></div>
        @endforelse
    </div>

    {{ $comments->links() }}
@endsection