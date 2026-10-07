@extends('layouts.admin')
@section('title', 'Thêm bình luận')

@section('content')
    <div class="topbar"><h1>Thêm bình luận</h1></div>
    <div class="card">
        <form method="POST" action="{{ route('admin.comments.store') }}">
            @include('admin.comments.form')
        </form>
    </div>
@endsection