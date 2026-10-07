@extends('layouts.admin')
@section('title', 'Sửa bình luận')

@section('content')
    <div class="topbar"><h1>Sửa bình luận #{{ $comment->id }}</h1></div>
    <div class="card">
        <form method="POST" action="{{ route('admin.comments.update', $comment) }}">
            @method('PUT')
            @include('admin.comments._form')
        </form>
    </div>
@endsection