@extends('layouts.admin')
@section('title', 'Sửa bình luận')

@section('content')
<div class="topbar">
    <h1>Sửa bình luận #{{ $comment->id }}</h1>
    <div class="right">
        <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('admin.comments.update', $comment) }}">
        @method('PUT')
        @include('admin.comments.form')
        <input type="hidden" name="version" value="{{ $comment->version }}">
    </form>
</div>
@endsection