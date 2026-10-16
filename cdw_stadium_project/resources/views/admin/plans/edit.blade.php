@extends('layouts.admin')
@section('title', 'Sửa Plan')

@section('content')
<div class="topbar">
    <h1>Sửa Plan #{{ $plan->id }}</h1>
    <div class="right">
        <a href="{{ route('admin.plans.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card">
    <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
        @method('PUT')
        @include('admin.plans.form')
    </form>
</div>
@endsection
