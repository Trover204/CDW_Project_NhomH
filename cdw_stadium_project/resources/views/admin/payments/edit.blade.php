@extends('layouts.admin')
@section('title', 'Sửa Thanh toán')

@section('content')
<div class="topbar">
    <h1>Sửa Thanh toán</h1>
</div>

<div class="table-card" style="padding: 24px;">
    <form action="{{ route('admin.payments.update', $payment) }}" method="POST">
        @method('PUT')
        @include('admin.payments.form', ['payment' => $payment])
    </form>
</div>
@endsection
