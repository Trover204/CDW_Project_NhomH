@extends('layouts.admin')
@section('title', 'Thêm Đặt sân')

@section('content')
<div class="topbar">
    <h1>Thêm Đặt sân</h1>
</div>

<div class="table-card" style="padding: 24px;">
    <form action="{{ route('admin.bookings.store') }}" method="POST">
        @include('admin.bookings.form')
    </form>
</div>
@endsection
