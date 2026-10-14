@extends('layouts.admin')
@section('title', 'Sửa cơ sở')

@section('content')
<div class="topbar">
    <h1>Sửa cơ sở: {{ $facility->name }}</h1>
    <div class="right">
        <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card">
    <form action="{{ route('admin.facilities.update', $facility) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.facilities.form')
        <input type="hidden" name="version" value="{{ $facility->version }}">
    </form>
</div>
@endsection
