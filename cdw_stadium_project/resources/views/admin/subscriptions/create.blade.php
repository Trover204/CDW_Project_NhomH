@extends('layouts.admin')
@section('title', 'Thêm Subscription')

@section('content')
<div class="topbar">
    <h1>Thêm Subscription mới</h1>
    <div class="right">
        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('admin.subscriptions.store') }}">
        @include('admin.subscriptions.form')
    </form>
</div>
@endsection
