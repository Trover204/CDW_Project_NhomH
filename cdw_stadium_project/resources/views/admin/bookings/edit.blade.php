@extends('layouts.admin')
@section('title', 'Sửa Đặt sân')

@section('content')
<div class="topbar">
    <h1>Sửa Đặt sân #{{ $booking->id }}</h1>
    <div class="right">
        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card" style="max-width: 720px;">
    <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
        @method('PUT')
        @include('admin.bookings.form', ['booking' => $booking])
    </form>
</div>
@endsection
