@extends('layouts.admin')
@section('title', 'Thêm Plan')

@section('content')
<div class="topbar">
    <h1>Thêm Plan mới</h1>
    <div class="right">
        <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('admin.plans.store') }}">
        @include('admin.plans.form')
    </form>
</div>
@endsection
