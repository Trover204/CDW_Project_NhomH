<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'revenue'       => 18400000,
            'new_bookings'  => 42,
            'new_users'     => 15,
            'active_courts' => 36,
            'total_courts'  => 40,
        ];

        $weeklyRevenue = [8, 13, 10, 16, 12, 19, 14]; // triệu đồng

        $courtTypes = [
            ['name' => 'Cầu lông',  'count' => 18, 'color' => '#178A4C'],
            ['name' => 'Bóng đá',   'count' => 14, 'color' => '#6CC48F'],
            ['name' => 'Pickleball','count' => 10, 'color' => '#BEE3CB'],
        ];

        $recentBookings = [
            ['customer' => 'Nguyễn Văn A', 'court' => 'Cầu lông BT',   'time' => '18:00-19:00', 'status' => 'Đã xác nhận', 'badge' => 'success'],
            ['customer' => 'Lê Thị B',     'court' => 'Bóng đá Q9',    'time' => '19:00-20:00', 'status' => 'Chờ duyệt',   'badge' => 'warning'],
            ['customer' => 'Phạm C',       'court' => 'Pickleball Q7', 'time' => '20:00-21:00', 'status' => 'Đã hủy',      'badge' => 'danger'],
        ];

        return view('admin.dashboard', compact('stats', 'weeklyRevenue', 'courtTypes', 'recentBookings'));
    }
}