@extends('layouts.admin')
@section('title', 'Thêm loại môn thể thao')

@section('content')
<div class="topbar">
    <h1>Thêm loại môn thể thao</h1>
    <div class="right">
        <a href="{{ route('admin.sport-types.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card">
    <form action="{{ route('admin.sport-types.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.sport-types.form')
    </form>
</div>
@endsection