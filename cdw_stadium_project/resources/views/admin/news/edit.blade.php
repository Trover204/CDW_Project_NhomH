@extends('layouts.admin')
@section('title', 'Sửa tin tức')

@section('content')
<div class="topbar">
    <h1>Cập nhật tin tức</h1>
    <div class="right">
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card">
    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.news.form')
    </form>
</div>
@endsection
