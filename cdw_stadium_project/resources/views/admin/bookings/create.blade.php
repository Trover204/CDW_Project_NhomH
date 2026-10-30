@extends('layouts.admin')
@section('title', 'Thêm Đặt sân')

@section('content')
<div class="topbar">
    <h1>Thêm Đặt sân mới</h1>
    <div class="right">
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card" style="max-width: 720px;">
    <form action="{{ route('admin.bookings.store') }}" method="POST">
        @include('admin.bookings.form')
    </form>
</div>
@endsection
