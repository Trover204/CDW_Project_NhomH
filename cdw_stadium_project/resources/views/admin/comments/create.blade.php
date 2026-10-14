@extends('layouts.admin')
@section('title', 'Thêm bình luận')

@section('content')
<div class="topbar">
    <h1>Thêm bình luận mới</h1>
    <div class="right">
        <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('admin.comments.store') }}">
        @include('admin.comments.form')
    </form>
</div>
@endsection