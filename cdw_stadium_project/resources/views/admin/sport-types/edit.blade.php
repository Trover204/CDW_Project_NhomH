@extends('layouts.admin')
@section('title', 'Sửa loại môn thể thao')

@section('content')
<div class="topbar">
    <h1>Sửa: {{ $sportType->name }}</h1>
    <div class="right">
        <a href="{{ route('admin.sport-types.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card">
    <form action="{{ route('admin.sport-types.update', $sportType) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.sport-types.form')
        <input type="hidden" name="version" value="{{ $sportType->version }}">
    </form>
</div>
@endsection