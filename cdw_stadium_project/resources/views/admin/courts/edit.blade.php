@extends('layouts.admin')
@section('title', 'Sửa sân thể thao')

@section('content')
<div class="topbar">
    <h1>Sửa sân: {{ $court->name }}</h1>
    <div class="right">
        <a href="{{ route('admin.courts.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card">
    <form action="{{ route('admin.courts.update', $court) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.courts.form')
        <input type="hidden" name="version" value="{{ $court->version }}">
    </form>
</div>
@endsection
