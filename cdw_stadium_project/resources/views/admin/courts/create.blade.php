@extends('admin.layouts.app')
@section('title', 'Thêm sân thể thao')

@section('content')
<h2 style="margin:0 0 14px;font-size:16px;color:#16241C;">Thêm sân thể thao mới</h2>
<form action="{{ route('admin.courts.store') }}" method="POST"
      style="background:#fff;border:0.5px solid #E1E8E3;border-radius:10px;padding:18px;max-width:560px;">
    @csrf
    @include('admin.courts.form')
</form>
@endsection
