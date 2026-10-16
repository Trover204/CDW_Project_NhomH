@extends('layouts.admin')
@section('title', 'Thêm tin tức')

@section('content')
<div class="topbar">
    <h1>Thêm tin tức mới</h1>
    <div class="right">
        <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card">
    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.news.form')
    </form>
</div>
@endsection
