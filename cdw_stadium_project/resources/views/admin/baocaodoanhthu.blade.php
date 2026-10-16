@if(isset($totalRevenue))
    @include('admin.reports.revenue')
@else
    @include('admin.reports.revenue', [
        'period' => 'month',
        'selectedMonth' => date('Y-m'),
        'selectedYear' => date('Y'),
        'periodLabel' => 'Tháng ' . date('m/Y'),
        'totalRevenue' => 312500000,
        'completedBookingsCount' => 1284,
        'totalBookingsCount' => 1400,
        'averageOrderValue' => 243000,
        'growthRate' => 12.0,
        'daysInMonth' => [],
        'monthlyBreakdown' => [],
        'sportTypesBreakdown' => collect([]),
        'bookings' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
        'facilities' => collect([]),
        'sportTypes' => collect([]),
    ])
@endif