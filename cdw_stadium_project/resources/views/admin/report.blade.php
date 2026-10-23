@if(isset($totalRevenue))
    @include('admin.reports.revenue')
@else
    @include('admin.reports.revenue', [
        'period' => 'month',
        'selectedMonth' => date('Y-m'),
        'selectedYear' => date('Y'),
        'selectedType' => null,
        'periodLabel' => 'Tháng ' . date('m/Y'),
        'totalRevenue' => 312500000,
        'bookingRevenue' => 210000000,
        'saleRevenue' => 52500000,
        'subscriptionRevenue' => 50000000,
        'bookingPaymentsCount' => 980,
        'salePaymentsCount' => 210,
        'subscriptionPaymentsCount' => 94,
        'paidPaymentsCount' => 1284,
        'totalPaymentsCount' => 1400,
        'completedBookingsCount' => 1284,
        'totalBookingsCount' => 1400,
        'averageOrderValue' => 243000,
        'growthRate' => 12.0,
        'sourcesBreakdown' => collect([]),
        'paymentMethodsBreakdown' => collect([]),
        'daysInMonth' => [],
        'monthlyBreakdown' => [],
        'sportTypesBreakdown' => collect([]),
        'payments' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10),
        'facilities' => collect([]),
        'sportTypes' => collect([]),
    ])
@endif
