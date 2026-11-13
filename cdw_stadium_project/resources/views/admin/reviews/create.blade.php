@extends('layouts.admin')
@section('title', 'Thêm đánh giá sân')

@section('content')
<div class="topbar">
    <h1>Thêm đánh giá sân thể thao</h1>
    <div class="right">
        <a href="{{ route('admin.comments.index', ['tab' => 'court-reviews']) }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('admin.reviews.store') }}">
        @include('admin.reviews.form')
    </form>
</div>
@endsection
