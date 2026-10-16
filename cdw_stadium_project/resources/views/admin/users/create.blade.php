@extends('layouts.admin')
@section('title', 'Thêm người dùng mới')

@section('content')
<div class="topbar">
    <h1>Thêm người dùng mới</h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
</div>

<div class="form-card" style="max-width: 600px;">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @include('admin.users.form')
    </form>
</div>
@endsection
