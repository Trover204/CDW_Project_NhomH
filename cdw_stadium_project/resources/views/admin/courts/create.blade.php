@extends('layouts.admin')
@section('title', 'Thêm sân thể thao')

@section('content')
<div class="topbar">
    <h1>Thêm sân thể thao mới</h1>
    <div class="right">
        <a href="{{ route('admin.courts.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="form-card">
    <form action="{{ route('admin.courts.store') }}" method="POST">
        @csrf
        @include('admin.courts.form')
    </form>
</div>
@endsection
