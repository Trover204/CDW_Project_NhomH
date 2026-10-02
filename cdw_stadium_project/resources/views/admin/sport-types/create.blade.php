@extends('admin.layouts.app')
@section('title', 'Thêm loại môn')

@section('content')
<h2 style="margin:0 0 14px;font-size:16px;color:#16241C;">Thêm loại môn thể thao</h2>
<form action="{{ route('admin.sport-types.store') }}" method="POST" enctype="multipart/form-data"
      style="background:#fff;border:0.5px solid #E1E8E3;border-radius:10px;padding:18px;max-width:560px;">
    @csrf
    @include('admin.sport-types._form')
</form>
@endsection