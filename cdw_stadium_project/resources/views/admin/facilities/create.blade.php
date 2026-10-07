@extends('admin.layouts.app')
@section('title', 'Thêm cơ sở')

@section('content')
<h2 style="margin:0 0 14px;font-size:16px;color:#16241C;">Thêm cơ sở mới</h2>
<form action="{{ route('admin.facilities.store') }}" method="POST"
      style="background:#fff;border:0.5px solid #E1E8E3;border-radius:10px;padding:18px;max-width:560px;">
    @csrf
    @include('admin.facilities.form')
</form>
@endsection
