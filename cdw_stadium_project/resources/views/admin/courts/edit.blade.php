@extends('admin.layouts.app')
@section('title', 'Sửa sân thể thao')

@section('content')
<h2 style="margin:0 0 14px;font-size:16px;color:#16241C;">Sửa sân: {{ $court->name }}</h2>
<form action="{{ route('admin.courts.update', $court) }}" method="POST"
      style="background:#fff;border:0.5px solid #E1E8E3;border-radius:10px;padding:18px;max-width:560px;">
    @csrf
    @method('PUT')
    @include('admin.courts.form')
</form>
@endsection
