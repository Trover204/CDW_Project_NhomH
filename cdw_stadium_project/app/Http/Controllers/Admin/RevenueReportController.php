<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RevenueReportRequest;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Facility;
use App\Models\SportType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RevenueReportController extends Controller
{
    public function index(RevenueReportRequest $request)
    {
        $data = $this->prepareReportData($request);

        return view('admin.reports.revenue', $data);
    }

    public function exportExcel(RevenueReportRequest $request): StreamedResponse
    {
        $data = $this->prepareReportData($request);

        $filename = 'bao-cao-doanh-thu-'.($data['selectedMonth'] ?? date('Y-m')).'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($data) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so Excel opens Vietnamese characters correctly
            fwrite($handle, "\xEF\xBB\xBF");

            // Header
            fputcsv($handle, ['BÁO CÁO DOANH THU - SÂN NHÓM H']);
            fputcsv($handle, ['Kỳ báo cáo:', $data['periodLabel']]);
            fputcsv($handle, ['Ngày xuất file:', now()->format('d/m/Y H:i:s')]);
            fputcsv($handle, []);

            // 1. Chỉ số tổng quan
            fputcsv($handle, ['I. TỔNG QUAN CHỈ SỐ DOANH THU']);
            fputcsv($handle, ['Chỉ số', 'Giá trị', 'So với kỳ trước']);
            fputcsv($handle, [
                'Tổng doanh thu',
                number_format($data['totalRevenue'], 0, ',', '.').' VNĐ',
                $data['growthRate'] !== null ? ($data['growthRate'] >= 0 ? "+{$data['growthRate']}%" : "{$data['growthRate']}%") : 'N/A',
            ]);
            fputcsv($handle, ['Số đơn hoàn thành', $data['completedBookingsCount'].' đơn', '']);
            fputcsv($handle, ['Tổng số lượt đặt', $data['totalBookingsCount'].' lượt', '']);
            fputcsv($handle, ['Giá trị đơn trung bình', number_format($data['averageOrderValue'], 0, ',', '.').' VNĐ', '']);
            fputcsv($handle, []);

            // 2. Doanh thu theo loại môn thể thao / sân
            fputcsv($handle, ['II. DOANH THU THEO LOẠI SÂN']);
            fputcsv($handle, ['STT', 'Loại sân / Môn thể thao', 'Số đơn đặt', 'Doanh thu (VNĐ)', 'Tỷ trọng (%)']);
            foreach ($data['sportTypesBreakdown'] as $index => $item) {
                fputcsv($handle, [
                    $index + 1,
                    $item['name'],
                    $item['count'],
                    number_format($item['revenue'], 0, ',', '.'),
                    $item['percent'].'%',
                ]);
            }
            fputcsv($handle, []);

            // 3. Thống kê doanh thu theo 12 tháng trong năm
            fputcsv($handle, ['III. THỐNG KÊ DOANH THU THEO THÁNG NĂM '.$data['selectedYear']]);
            fputcsv($handle, ['Tháng', 'Tổng số đơn', 'Đơn hoàn thành', 'Doanh thu (VNĐ)', 'Doanh thu TB/đơn (VNĐ)', 'Tăng trưởng tháng trước']);
            foreach ($data['monthlyBreakdown'] as $m) {
                fputcsv($handle, [
                    $m['month_label'],
                    $m['count'],
                    $m['completed'],
                    number_format($m['revenue'], 0, ',', '.'),
                    number_format($m['aov'], 0, ',', '.'),
                    $m['growth'] !== null ? ($m['growth'] >= 0 ? "+{$m['growth']}%" : "{$m['growth']}%") : 'N/A',
                ]);
            }
            fputcsv($handle, []);

            // 4. Doanh thu theo ngày trong kỳ
            fputcsv($handle, ['IV. DOANH THU THEO TỪNG NGÀY TRONG KỲ']);
            fputcsv($handle, ['Ngày', 'Số đơn', 'Doanh thu (VNĐ)']);
            foreach ($data['daysInMonth'] as $d) {
                fputcsv($handle, [
                    $d['date'],
                    $d['count'],
                    number_format($d['revenue'], 0, ',', '.'),
                ]);
            }
            fputcsv($handle, []);

            // 5. Chi tiết các đơn đặt
            fputcsv($handle, ['V. DANH SÁCH CHI TIẾT ĐƠN ĐẶT TRONG KỲ']);
            fputcsv($handle, ['Mã đơn', 'Khách hàng', 'SĐT', 'Sân', 'Loại sân', 'Ngày đặt', 'Khung giờ', 'Tổng tiền (VNĐ)', 'Trạng thái đơn', 'Phương thức TT', 'Trạng thái TT']);
            foreach ($data['allBookings'] as $b) {
                fputcsv($handle, [
                    '#BK-'.str_pad($b->id, 5, '0', STR_PAD_LEFT),
                    $b->customer_name,
                    $b->customer_phone,
                    $b->court ? $b->court->name : 'N/A',
                    $b->court && $b->court->sportType ? $b->court->sportType->name : 'N/A',
                    $b->booking_date,
                    substr($b->start_time, 0, 5).' - '.substr($b->end_time, 0, 5),
                    number_format($b->total_price, 0, ',', '.'),
                    $b->status,
                    $b->payment ? $b->payment->payment_method : 'N/A',
                    $b->payment ? $b->payment->status : 'N/A',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(RevenueReportRequest $request)
    {
        $data = $this->prepareReportData($request);

        return view('admin.reports.pdf', $data);
    }

    protected function prepareReportData(RevenueReportRequest $request): array
    {
        $period = $request->input('period', 'month');
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $selectedYear = (int) ($request->input('year') ?? substr($selectedMonth, 0, 4));

        if ($period === 'custom' && $request->filled('from_date') && $request->filled('to_date')) {
            $startDate = Carbon::parse($request->from_date)->startOfDay();
            $endDate = Carbon::parse($request->to_date)->endOfDay();
            $diffDays = $endDate->diffInDays($startDate) + 1;
            $prevStartDate = $startDate->copy()->subDays($diffDays);
            $prevEndDate = $startDate->copy()->subSecond();
            $periodLabel = $startDate->format('d/m/Y').' - '.$endDate->format('d/m/Y');
        } elseif ($period === 'year') {
            $startDate = Carbon::create($selectedYear, 1, 1)->startOfDay();
            $endDate = Carbon::create($selectedYear, 12, 31)->endOfDay();
            $prevStartDate = Carbon::create($selectedYear - 1, 1, 1)->startOfDay();
            $prevEndDate = Carbon::create($selectedYear - 1, 12, 31)->endOfDay();
            $periodLabel = "Năm {$selectedYear}";
        } else {
            // Default month
            $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
            $startDate = $monthDate->copy()->startOfMonth();
            $endDate = $monthDate->copy()->endOfMonth();
            $prevStartDate = $monthDate->copy()->subMonth()->startOfMonth();
            $prevEndDate = $monthDate->copy()->subMonth()->endOfMonth();
            $periodLabel = "Tháng {$monthDate->format('m/Y')}";
        }

        $buildBookingQuery = function ($start, $end) use ($request) {
            $query = Booking::query()
                ->whereBetween('booking_date', [$start->toDateString(), $end->toDateString()])
                ->whereIn('status', ['completed', 'confirmed']);

            if ($request->filled('facility_id')) {
                $query->whereHas('court', function ($q) use ($request) {
                    $q->where('facility_id', $request->facility_id);
                });
            }

            if ($request->filled('sport_type_id')) {
                $query->whereHas('court', function ($q) use ($request) {
                    $q->where('sport_type_id', $request->sport_type_id);
                });
            }

            return $query;
        };

        $currentBookingsQuery = $buildBookingQuery($startDate, $endDate);
        $totalRevenue = (float) (clone $currentBookingsQuery)->sum('total_price');
        $completedBookingsCount = (clone $currentBookingsQuery)->where('status', 'completed')->count();
        $totalBookingsCount = (clone $currentBookingsQuery)->count();
        $averageOrderValue = $completedBookingsCount > 0 ? round($totalRevenue / $completedBookingsCount) : 0;

        $prevBookingsQuery = $buildBookingQuery($prevStartDate, $prevEndDate);
        $prevRevenue = (float) (clone $prevBookingsQuery)->sum('total_price');
        $growthRate = null;
        if ($prevRevenue > 0) {
            $growthRate = round((($totalRevenue - $prevRevenue) / $prevRevenue) * 100, 1);
        } elseif ($totalRevenue > 0) {
            $growthRate = 100.0;
        }

        $isSqlite = DB::getDriverName() === 'sqlite';
        $monthExpr = $isSqlite ? "CAST(strftime('%m', booking_date) AS INTEGER)" : 'MONTH(booking_date)';
        $dateExpr = $isSqlite ? 'date(booking_date)' : 'DATE(booking_date)';

        // Daily breakdown for selected period/month
        $dailyRaw = (clone $currentBookingsQuery)
            ->selectRaw("{$dateExpr} as b_date, SUM(total_price) as revenue, COUNT(*) as b_count")
            ->groupBy('b_date')
            ->get()
            ->keyBy('b_date');

        $daysInMonth = [];
        $curr = $startDate->copy();
        $maxDailyRevenue = 0;
        // Limit daily chart loop to 31 days to keep UI fast
        $chartEnd = $endDate->copy();
        if ($curr->diffInDays($chartEnd) > 31) {
            $chartEnd = $curr->copy()->addDays(30);
        }

        while ($curr->lte($chartEnd)) {
            $dateStr = $curr->toDateString();
            $item = $dailyRaw->get($dateStr);
            $rev = $item ? (float) $item->revenue : 0;
            $count = $item ? (int) $item->b_count : 0;

            if ($rev > $maxDailyRevenue) {
                $maxDailyRevenue = $rev;
            }

            $daysInMonth[] = [
                'day' => $curr->day,
                'date' => $dateStr,
                'label' => $curr->format('d/m'),
                'revenue' => $rev,
                'count' => $count,
            ];
            $curr->addDay();
        }

        foreach ($daysInMonth as &$dayItem) {
            $dayItem['is_max'] = ($maxDailyRevenue > 0 && $dayItem['revenue'] == $maxDailyRevenue);
            $dayItem['height_percent'] = $maxDailyRevenue > 0 ? max(8, (int) round(($dayItem['revenue'] / $maxDailyRevenue) * 100)) : 8;
        }
        unset($dayItem);

        // Monthly breakdown for selected year (12 months)
        $yearlyStart = Carbon::create($selectedYear, 1, 1)->startOfDay();
        $yearlyEnd = Carbon::create($selectedYear, 12, 31)->endOfDay();

        $monthlyStats = $buildBookingQuery($yearlyStart, $yearlyEnd)
            ->selectRaw("{$monthExpr} as month_num, SUM(total_price) as revenue, COUNT(*) as total_count, SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count")
            ->groupBy('month_num')
            ->get()
            ->keyBy('month_num');

        $monthlyBreakdown = [];
        $maxMonthlyRevenue = 0;
        $prevMonthRev = null;
        for ($m = 1; $m <= 12; $m++) {
            $item = $monthlyStats->get($m);
            $rev = $item ? (float) $item->revenue : 0;
            $count = $item ? (int) $item->total_count : 0;
            $completed = $item ? (int) $item->completed_count : 0;
            $aov = $completed > 0 ? round($rev / $completed) : 0;

            if ($rev > $maxMonthlyRevenue) {
                $maxMonthlyRevenue = $rev;
            }

            $monthGrowth = null;
            if ($prevMonthRev !== null && $prevMonthRev > 0) {
                $monthGrowth = round((($rev - $prevMonthRev) / $prevMonthRev) * 100, 1);
            } elseif ($prevMonthRev !== null && $prevMonthRev == 0 && $rev > 0) {
                $monthGrowth = 100.0;
            }

            $monthlyBreakdown[] = [
                'month' => $m,
                'month_label' => "Tháng $m",
                'month_key' => sprintf('%04d-%02d', $selectedYear, $m),
                'revenue' => $rev,
                'count' => $count,
                'completed' => $completed,
                'aov' => $aov,
                'growth' => $monthGrowth,
            ];

            $prevMonthRev = $rev;
        }

        foreach ($monthlyBreakdown as &$mItem) {
            $mItem['is_max'] = ($maxMonthlyRevenue > 0 && $mItem['revenue'] == $maxMonthlyRevenue);
            $mItem['height_percent'] = $maxMonthlyRevenue > 0 ? max(8, (int) round(($mItem['revenue'] / $maxMonthlyRevenue) * 100)) : 8;
        }
        unset($mItem);

        // Sport types breakdown
        $sportTypes = SportType::all();

        // Build a grouped query for all bookings within the date range, joined with courts
        $bookingsQuery = Booking::join('courts', 'bookings.court_id', '=', 'courts.id')
            ->whereBetween('bookings.booking_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereIn('bookings.status', ['completed', 'confirmed']);

        if ($request->filled('facility_id')) {
            $bookingsQuery->where('courts.facility_id', $request->facility_id);
        }

        $sportTypeStats = $bookingsQuery
            ->selectRaw('courts.sport_type_id, COUNT(bookings.id) as b_count, SUM(bookings.total_price) as b_revenue')
            ->groupBy('courts.sport_type_id')
            ->get()
            ->keyBy('sport_type_id');

        $sportTypesBreakdown = $sportTypes->map(function ($sportType) use ($sportTypeStats) {
            $stats = $sportTypeStats->get($sportType->id);

            return [
                'id' => $sportType->id,
                'name' => $sportType->name,
                'count' => $stats ? (int) $stats->b_count : 0,
                'revenue' => $stats ? (float) $stats->b_revenue : 0,
            ];
        });

        $totalSportRevenue = $sportTypesBreakdown->sum('revenue');
        $colors = ['#178A4C', '#6CC48F', '#BEE3CB', '#F59E0B', '#3B82F6', '#8B5CF6'];
        $sportTypesBreakdown = $sportTypesBreakdown->map(function ($item, $idx) use ($totalSportRevenue, $colors) {
            $item['percent'] = $totalSportRevenue > 0 ? round(($item['revenue'] / $totalSportRevenue) * 100, 1) : 0;
            $item['color'] = $colors[$idx % count($colors)];

            return $item;
        })->sortByDesc('revenue')->values();

        // Paginated bookings for display
        $bookings = (clone $currentBookingsQuery)
            ->with(['court.sportType', 'court.facility', 'user', 'payment'])
            ->latest('booking_date')
            ->paginate(10)
            ->appends($request->query());

        // All bookings for export
        $allBookings = (clone $currentBookingsQuery)
            ->with(['court.sportType', 'court.facility', 'user', 'payment'])
            ->latest('booking_date')
            ->get();

        $facilities = Facility::all();

        return [
            'period' => $period,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'periodLabel' => $periodLabel,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $totalRevenue,
            'completedBookingsCount' => $completedBookingsCount,
            'totalBookingsCount' => $totalBookingsCount,
            'averageOrderValue' => $averageOrderValue,
            'growthRate' => $growthRate,
            'prevRevenue' => $prevRevenue,
            'daysInMonth' => $daysInMonth,
            'maxDailyRevenue' => $maxDailyRevenue,
            'monthlyBreakdown' => $monthlyBreakdown,
            'maxMonthlyRevenue' => $maxMonthlyRevenue,
            'sportTypesBreakdown' => $sportTypesBreakdown,
            'bookings' => $bookings,
            'allBookings' => $allBookings,
            'facilities' => $facilities,
            'sportTypes' => $sportTypes,
        ];
    }
}
