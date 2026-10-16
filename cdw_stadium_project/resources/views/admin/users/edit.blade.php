@extends('layouts.admin')
@section('title', 'Sửa người dùng')

@section('content')
<div class="topbar">
    <h1>Sửa người dùng: <span style="color: var(--primary);">{{ $user->name }}</span></h1>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
</div>

<div class="form-card" style="max-width: 600px;">
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @method('PUT')
        @include('admin.users.form')
    </form>
</div>
@endsection
