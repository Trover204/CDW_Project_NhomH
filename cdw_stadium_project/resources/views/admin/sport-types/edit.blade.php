@extends('admin.layouts.app')
@section('title', 'Sửa loại môn')

@section('content')
<h2 style="margin:0 0 14px;font-size:16px;color:#16241C;">Sửa: {{ $sportType->name }}</h2>
<form action="{{ route('admin.sport-types.update', $sportType) }}" method="POST" enctype="multipart/form-data"
      style="background:#fff;border:0.5px solid #E1E8E3;border-radius:10px;padding:18px;max-width:560px;">
    @csrf @method('PUT')
    @include('admin.sport-types.form')
    <input type="hidden" name="version" value="{{ $sportType->version }}">
</form>
@endsection