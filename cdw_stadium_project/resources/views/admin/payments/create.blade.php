@extends('layouts.admin')
@section('title', 'Thêm Thanh toán')

@section('content')
<div class="topbar">
    <h1>Thêm Thanh toán</h1>
</div>

<div class="table-card" style="padding: 24px;">
    <form action="{{ route('admin.payments.store') }}" method="POST">
        @include('admin.payments.form')
    </form>
</div>
@endsection
