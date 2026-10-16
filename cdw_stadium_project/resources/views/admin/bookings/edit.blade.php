@extends('layouts.admin')
@section('title', 'Sửa Đặt sân')

@section('content')
<div class="topbar">
    <h1>Sửa Đặt sân</h1>
</div>

<div class="table-card" style="padding: 24px;">
    <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
        @method('PUT')
        @include('admin.bookings.form', ['booking' => $booking])
    </form>
</div>
@endsection
