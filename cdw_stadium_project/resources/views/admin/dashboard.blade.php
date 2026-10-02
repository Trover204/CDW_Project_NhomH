@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="topbar">
        <h1>Tổng quan hôm nay</h1>
        <div class="right">
            <input class="search" type="text" placeholder="Tìm đơn, sân...">
            <div class="avatar">NA</div>
        </div>
    </div>

    {{-- 4 thẻ thống kê --}}
    <div class="stats">
        <div class="card stat">
            <div class="label">Doanh thu hôm nay</div>
            <div class="value">1000000</div>
        </div>
        <div class="card stat">
            <div class="label">Đơn đặt mới</div>
            <div class="value">100</div>
        </div>
        <div class="card stat">
            <div class="label">Người dùng mới</div>
            <div class="value">10</div>
        </div>
        <div class="card stat">
            <div class="label">Sân đang hoạt động</div>
            <div class="value green">5</div>
        </div>
    </div>

    <div class="charts">
        {{-- Biểu đồ cột --}}
        <div class="card">
            <h3>Doanh thu 7 ngày qua</h3>
        
            <div class="bars">
             
            </div>
        </div>

        {{-- Biểu đồ tròn bằng CSS --}}
        <div class="card">
            <h3>Loại sân được đặt</h3>
      
            <div class="donut-wrap">
                <ul class="legend">
                 
                </ul>
            </div>
        </div>
    </div>

    {{-- Bảng đơn gần đây --}}
    <div class="section-title">Đơn đặt gần đây</div>
    <div class="table">
        <div class="row head">
            <div>Khách hàng</div><div>Sân</div><div>Khung giờ</div><div>Trạng thái</div>
        </div>
      
    </div>
@endsection