<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RevenueReportRequest;
use App\Models\Facility;
use App\Models\Payment;
use App\Models\SportType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Báo cáo doanh thu tập trung theo bảng payments:
 * gồm tổng doanh thu và phân rã theo từng nguồn (booking, sale, subscription) cũng như loại sân.
 */
class RevenueReportController extends Controller
{
    public function index(RevenueReportRequest $request)
    {
        $data = $this->prepareReportData($request);

        return view('admin.report', $data);
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
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['BÁO CÁO DOANH THU (THEO THANH TOÁN) - SÂN NHÓM H']);
            fputcsv($handle, ['Kỳ báo cáo:', $data['periodLabel']]);
            fputcsv($handle, ['Ngày xuất file:', now()->format('d/m/Y H:i:s')]);
            fputcsv($handle, []);

            // 1. Chỉ số tổng quan
            fputcsv($handle, ['I. TỔNG QUAN CHỈ SỐ DOANH THU']);
            fputcsv($handle, ['Chỉ số', 'Giá trị', 'Ghi chú / So với kỳ trước']);
            fputcsv($handle, [
                'Tổng doanh thu đã thu',
                number_format($data['totalRevenue'], 0, ',', '.').' VNĐ',
                $data['growthRate'] !== null ? ($data['growthRate'] >= 0 ? "+{$data['growthRate']}%" : "{$data['growthRate']}%") : 'N/A',
            ]);
            fputcsv($handle, ['Doanh thu Đặt sân (Booking)', number_format($data['bookingRevenue'], 0, ',', '.').' VNĐ', $data['bookingPaymentsCount'].' giao dịch']);
            fputcsv($handle, ['Doanh thu Bán hàng (Sale)', number_format($data['saleRevenue'], 0, ',', '.').' VNĐ', $data['salePaymentsCount'].' giao dịch']);
            fputcsv($handle, ['Doanh thu Gói thành viên (Subscription)', number_format($data['subscriptionRevenue'], 0, ',', '.').' VNĐ', $data['subscriptionPaymentsCount'].' giao dịch']);
            fputcsv($handle, ['Số giao dịch thành công', $data['paidPaymentsCount'].' giao dịch', '']);
            fputcsv($handle, ['Giá trị giao dịch trung bình', number_format($data['averageOrderValue'], 0, ',', '.').' VNĐ', '']);
            fputcsv($handle, []);

            // 2. Doanh thu theo nguồn thanh toán
            fputcsv($handle, ['II. CƠ CẤU DOANH THU THEO NGUỒN THANH TOÁN']);
            fputcsv($handle, ['STT', 'Nguồn doanh thu', 'Số giao dịch', 'Doanh thu (VNĐ)', 'Tỷ trọng (%)']);
            foreach ($data['sourcesBreakdown'] as $index => $src) {
                fputcsv($handle, [
                    $index + 1,
                    $src['name'],
                    $src['count'],
                    number_format($src['revenue'], 0, ',', '.'),
                    $src['percent'].'%',
                ]);
            }
            fputcsv($handle, []);

            // 3. Doanh thu theo loại sân
            fputcsv($handle, ['III. DOANH THU THEO LOẠI SÂN']);
            fputcsv($handle, ['STT', 'Loại sân / Môn thể thao', 'Số giao dịch', 'Doanh thu (VNĐ)', 'Tỷ trọng (%)']);
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

            // 4. Thống kê doanh thu theo 12 tháng
            fputcsv($handle, ['IV. THỐNG KÊ DOANH THU THEO THÁNG NĂM '.$data['selectedYear']]);
            fputcsv($handle, ['Tháng', 'Số GD', 'Đặt sân (VNĐ)', 'Bán hàng (VNĐ)', 'Gói TV (VNĐ)', 'Tổng doanh thu (VNĐ)', 'TB/GD (VNĐ)', 'Tăng trưởng']);
            foreach ($data['monthlyBreakdown'] as $m) {
                fputcsv($handle, [
                    $m['month_label'],
                    $m['completed'],
                    number_format($m['booking_revenue'], 0, ',', '.'),
                    number_format($m['sale_revenue'], 0, ',', '.'),
                    number_format($m['subscription_revenue'], 0, ',', '.'),
                    number_format($m['revenue'], 0, ',', '.'),
                    number_format($m['aov'], 0, ',', '.'),
                    $m['growth'] !== null ? ($m['growth'] >= 0 ? "+{$m['growth']}%" : "{$m['growth']}%") : 'N/A',
                ]);
            }
            fputcsv($handle, []);

            // 5. Doanh thu theo ngày
            fputcsv($handle, ['V. DOANH THU THEO TỪNG NGÀY TRONG KỲ']);
            fputcsv($handle, ['Ngày', 'Số giao dịch', 'Đặt sân (VNĐ)', 'Bán hàng (VNĐ)', 'Gói TV (VNĐ)', 'Tổng doanh thu (VNĐ)']);
            foreach ($data['daysInMonth'] as $d) {
                fputcsv($handle, [
                    $d['date'],
                    $d['count'],
                    number_format($d['booking_revenue'], 0, ',', '.'),
                    number_format($d['sale_revenue'], 0, ',', '.'),
                    number_format($d['subscription_revenue'], 0, ',', '.'),
                    number_format($d['revenue'], 0, ',', '.'),
                ]);
            }
            fputcsv($handle, []);

            // 6. Danh sách chi tiết giao dịch thanh toán
            fputcsv($handle, ['VI. DANH SÁCH GIAO DỊCH THANH TOÁN TRONG KỲ']);
            fputcsv($handle, ['Mã GD', 'Loại nguồn', 'Mã đơn nguồn', 'Khách hàng', 'Nội dung', 'Phương thức TT', 'Số tiền (VNĐ)', 'Thời gian TT', 'Trạng thái']);
            foreach ($data['allPayments'] as $p) {
                fputcsv($handle, [
                    $p->transaction_code ?? ('#PAY-'.$p->id),
                    $p->type_label,
                    $p->reference,
                    $p->customer_name,
                    $p->description,
                    $p->method_label,
                    number_format($p->amount, 0, ',', '.'),
                    optional($p->paid_at ?? $p->created_at)->format('d/m/Y H:i'),
                    $p->status_label,
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
        $selectedType = $request->input('payable_type');

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
            $monthDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
            $startDate = $monthDate->copy()->startOfMonth();
            $endDate = $monthDate->copy()->endOfMonth();
            $prevStartDate = $monthDate->copy()->subMonth()->startOfMonth();
            $prevEndDate = $monthDate->copy()->subMonth()->endOfMonth();
            $periodLabel = "Tháng {$monthDate->format('m/Y')}";
        }

        $hasCourtFilter = $request->filled('facility_id') || $request->filled('sport_type_id');

        /**
         * Xây dựng query trên bảng payments (chỉ lấy các giao dịch đã thanh toán `status = paid`).
         * Thời점 ghi nhận doanh thu: COALESCE(paid_at, created_at).
         */
        $buildPaidPaymentsQuery = function (Carbon $start, Carbon $end, ?string $onlyType = null) use ($request, $hasCourtFilter, $selectedType): Builder {
            $effectiveType = $onlyType ?? $selectedType;

            $query = Payment::query()
                ->where('payments.status', 'paid')
                ->whereRaw('COALESCE(payments.paid_at, payments.created_at) BETWEEN ? AND ?', [
                    $start->toDateTimeString(),
                    $end->toDateTimeString(),
                ]);

            if ($effectiveType) {
                $query->where('payments.payable_type', $effectiveType);
            }

            if ($hasCourtFilter) {
                // Khi lọc theo cơ sở / môn thể thao thì chỉ tính các payment thuộc đặt sân của cơ sở / môn đó
                $query->where('payments.payable_type', Payment::TYPE_BOOKING)
                    ->whereIn('payments.payable_id', function ($sub) use ($request) {
                        $sub->select('bookings.id')
                            ->from('bookings')
                            ->join('courts', 'bookings.court_id', '=', 'courts.id');

                        if ($request->filled('facility_id')) {
                            $sub->where('courts.facility_id', $request->facility_id);
                        }
                        if ($request->filled('sport_type_id')) {
                            $sub->where('courts.sport_type_id', $request->sport_type_id);
                        }
                    });
            }

            return $query;
        };

        $currentPaymentsQuery = $buildPaidPaymentsQuery($startDate, $endDate);

        // Phân rã doanh thu theo từng phần (Booking, Sale, Subscription) từ bảng payments
        $byTypeRaw = (clone $currentPaymentsQuery)
            ->selectRaw('payments.payable_type, SUM(payments.amount) as revenue, COUNT(*) as p_count')
            ->groupBy('payments.payable_type')
            ->get()
            ->keyBy('payable_type');

        $bookingRevenue = (float) ($byTypeRaw->get(Payment::TYPE_BOOKING)->revenue ?? 0);
        $saleRevenue = (float) ($byTypeRaw->get(Payment::TYPE_SALE)->revenue ?? 0);
        $subscriptionRevenue = (float) ($byTypeRaw->get(Payment::TYPE_SUBSCRIPTION)->revenue ?? 0);

        $bookingPaymentsCount = (int) ($byTypeRaw->get(Payment::TYPE_BOOKING)->p_count ?? 0);
        $salePaymentsCount = (int) ($byTypeRaw->get(Payment::TYPE_SALE)->p_count ?? 0);
        $subscriptionPaymentsCount = (int) ($byTypeRaw->get(Payment::TYPE_SUBSCRIPTION)->p_count ?? 0);

        $totalRevenue = $bookingRevenue + $saleRevenue + $subscriptionRevenue;
        $paidPaymentsCount = $bookingPaymentsCount + $salePaymentsCount + $subscriptionPaymentsCount;
        $averageOrderValue = $paidPaymentsCount > 0 ? round($totalRevenue / $paidPaymentsCount) : 0.0;

        // Tổng số giao dịch phát sinh (mọi trạng thái) trong kỳ
        $allStatusQuery = Payment::query()
            ->whereRaw('COALESCE(payments.paid_at, payments.created_at) BETWEEN ? AND ?', [
                $startDate->toDateTimeString(),
                $endDate->toDateTimeString(),
            ]);
        if ($selectedType) {
            $allStatusQuery->where('payments.payable_type', $selectedType);
        }
        $totalPaymentsCount = $hasCourtFilter ? $paidPaymentsCount : (int) $allStatusQuery->count();

        // Giữ các biến tương thích với view/test hiện có
        $completedBookingsCount = $paidPaymentsCount;
        $totalBookingsCount = $totalPaymentsCount;
        $totalSalesCount = $salePaymentsCount;

        // Cơ cấu theo nguồn thanh toán (3 phần)
        $sourcesBreakdown = collect(Payment::TYPES)->map(function (string $label, string $type) use ($byTypeRaw, $totalRevenue) {
            $row = $byTypeRaw->get($type);
            $rev = $row ? (float) $row->revenue : 0.0;
            $cnt = $row ? (int) $row->p_count : 0;

            return [
                'type' => $type,
                'name' => $label,
                'count' => $cnt,
                'revenue' => $rev,
                'percent' => $totalRevenue > 0 ? round(($rev / $totalRevenue) * 100, 1) : 0.0,
                'color' => Payment::TYPE_COLORS[$type] ?? '#178A4C',
            ];
        })->values();

        // Cơ cấu theo phương thức thanh toán
        $methodsRaw = (clone $currentPaymentsQuery)
            ->selectRaw('payments.payment_method, SUM(payments.amount) as revenue, COUNT(*) as p_count')
            ->groupBy('payments.payment_method')
            ->orderByDesc('revenue')
            ->get();

        $paymentMethodsBreakdown = $methodsRaw->map(function ($row) use ($totalRevenue) {
            $rev = (float) $row->revenue;

            return [
                'method' => $row->payment_method,
                'name' => Payment::METHODS[$row->payment_method] ?? strtoupper((string) $row->payment_method),
                'count' => (int) $row->p_count,
                'revenue' => $rev,
                'percent' => $totalRevenue > 0 ? round(($rev / $totalRevenue) * 100, 1) : 0.0,
            ];
        });

        // So sánh tăng trưởng kỳ trước
        $prevRevenue = (float) $buildPaidPaymentsQuery($prevStartDate, $prevEndDate)->sum('payments.amount');
        $growthRate = null;
        if ($prevRevenue > 0) {
            $growthRate = round((($totalRevenue - $prevRevenue) / $prevRevenue) * 100, 1);
        } elseif ($totalRevenue > 0) {
            $growthRate = 100.0;
        }

        $isSqlite = DB::getDriverName() === 'sqlite';
        $effectiveTs = 'COALESCE(payments.paid_at, payments.created_at)';
        $dateExpr = $isSqlite ? "date({$effectiveTs})" : "DATE({$effectiveTs})";
        $monthExpr = $isSqlite ? "CAST(strftime('%m', {$effectiveTs}) AS INTEGER)" : "MONTH({$effectiveTs})";

        // Daily breakdown theo ngày trong kỳ
        $dailyRows = (clone $currentPaymentsQuery)
            ->selectRaw("
                {$dateExpr} as p_date,
                SUM(payments.amount) as revenue,
                SUM(CASE WHEN payments.payable_type = 'booking' THEN payments.amount ELSE 0 END) as booking_revenue,
                SUM(CASE WHEN payments.payable_type = 'sale' THEN payments.amount ELSE 0 END) as sale_revenue,
                SUM(CASE WHEN payments.payable_type = 'subscription' THEN payments.amount ELSE 0 END) as subscription_revenue,
                COUNT(*) as p_count
            ")
            ->groupBy('p_date')
            ->get()
            ->keyBy('p_date');

        $daysInMonth = [];
        $curr = $startDate->copy();
        $maxDailyRevenue = 0.0;
        $chartEnd = $endDate->copy();
        if ($curr->diffInDays($chartEnd) > 31) {
            $chartEnd = $curr->copy()->addDays(30);
        }

        while ($curr->lte($chartEnd)) {
            $dateStr = $curr->toDateString();
            $item = $dailyRows->get($dateStr);
            $rev = $item ? (float) $item->revenue : 0.0;
            $count = $item ? (int) $item->p_count : 0;

            if ($rev > $maxDailyRevenue) {
                $maxDailyRevenue = $rev;
            }

            $daysInMonth[] = [
                'day' => $curr->day,
                'date' => $dateStr,
                'label' => $curr->format('d/m'),
                'revenue' => $rev,
                'booking_revenue' => $item ? (float) $item->booking_revenue : 0.0,
                'sale_revenue' => $item ? (float) $item->sale_revenue : 0.0,
                'subscription_revenue' => $item ? (float) $item->subscription_revenue : 0.0,
                'count' => $count,
            ];
            $curr->addDay();
        }

        foreach ($daysInMonth as &$dayItem) {
            $dayItem['is_max'] = ($maxDailyRevenue > 0 && $dayItem['revenue'] == $maxDailyRevenue);
            $dayItem['height_percent'] = $maxDailyRevenue > 0
                ? max(8, (int) round(($dayItem['revenue'] / $maxDailyRevenue) * 100))
                : 8;
        }
        unset($dayItem);

        // Monthly breakdown 12 tháng trong năm
        $yearlyStart = Carbon::create($selectedYear, 1, 1)->startOfDay();
        $yearlyEnd = Carbon::create($selectedYear, 12, 31)->endOfDay();

        $monthlyStats = $buildPaidPaymentsQuery($yearlyStart, $yearlyEnd)
            ->selectRaw("
                {$monthExpr} as month_num,
                SUM(payments.amount) as revenue,
                SUM(CASE WHEN payments.payable_type = 'booking' THEN payments.amount ELSE 0 END) as booking_revenue,
                SUM(CASE WHEN payments.payable_type = 'sale' THEN payments.amount ELSE 0 END) as sale_revenue,
                SUM(CASE WHEN payments.payable_type = 'subscription' THEN payments.amount ELSE 0 END) as subscription_revenue,
                COUNT(*) as total_count
            ")
            ->groupBy('month_num')
            ->get()
            ->keyBy('month_num');

        $monthlyBreakdown = [];
        $maxMonthlyRevenue = 0.0;
        $prevMonthRev = null;
        for ($m = 1; $m <= 12; $m++) {
            $item = $monthlyStats->get($m);
            $rev = $item ? (float) $item->revenue : 0.0;
            $count = $item ? (int) $item->total_count : 0;
            $aov = $count > 0 ? round($rev / $count) : 0.0;

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
                'booking_revenue' => $item ? (float) $item->booking_revenue : 0.0,
                'sale_revenue' => $item ? (float) $item->sale_revenue : 0.0,
                'subscription_revenue' => $item ? (float) $item->subscription_revenue : 0.0,
                'count' => $count,
                'completed' => $count,
                'aov' => $aov,
                'growth' => $monthGrowth,
            ];

            $prevMonthRev = $rev;
        }

        foreach ($monthlyBreakdown as &$mItem) {
            $mItem['is_max'] = ($maxMonthlyRevenue > 0 && $mItem['revenue'] == $maxMonthlyRevenue);
            $mItem['height_percent'] = $maxMonthlyRevenue > 0
                ? max(8, (int) round(($mItem['revenue'] / $maxMonthlyRevenue) * 100))
                : 8;
        }
        unset($mItem);

        // Sport types breakdown (dựa trên các payment của booking đã thu)
        $sportTypes = SportType::all();

        $sportPaymentsQuery = Payment::query()
            ->join('bookings', function ($join) {
                $join->on('payments.payable_id', '=', 'bookings.id')
                    ->where('payments.payable_type', '=', Payment::TYPE_BOOKING);
            })
            ->join('courts', 'bookings.court_id', '=', 'courts.id')
            ->where('payments.status', 'paid')
            ->whereRaw('COALESCE(payments.paid_at, payments.created_at) BETWEEN ? AND ?', [
                $startDate->toDateTimeString(),
                $endDate->toDateTimeString(),
            ]);

        if ($request->filled('facility_id')) {
            $sportPaymentsQuery->where('courts.facility_id', $request->facility_id);
        }

        if ($request->filled('sport_type_id')) {
            $sportPaymentsQuery->where('courts.sport_type_id', $request->sport_type_id);
        }

        $sportTypeStats = $sportPaymentsQuery
            ->selectRaw('courts.sport_type_id, COUNT(payments.id) as p_count, SUM(payments.amount) as p_revenue')
            ->groupBy('courts.sport_type_id')
            ->get()
            ->keyBy('sport_type_id');

        $sportTypesBreakdown = $sportTypes->map(function ($sportType) use ($sportTypeStats) {
            $stats = $sportTypeStats->get($sportType->id);

            return [
                'id' => $sportType->id,
                'name' => $sportType->name,
                'count' => $stats ? (int) $stats->p_count : 0,
                'revenue' => $stats ? (float) $stats->p_revenue : 0.0,
            ];
        });

        $totalSportRevenue = $sportTypesBreakdown->sum('revenue');
        $colors = ['#178A4C', '#6CC48F', '#BEE3CB', '#F59E0B', '#3B82F6', '#8B5CF6'];
        $sportTypesBreakdown = $sportTypesBreakdown->map(function ($item, $idx) use ($totalSportRevenue, $colors) {
            $item['percent'] = $totalSportRevenue > 0 ? round(($item['revenue'] / $totalSportRevenue) * 100, 1) : 0.0;
            $item['color'] = $colors[$idx % count($colors)];

            return $item;
        })->sortByDesc('revenue')->values();

        // Danh sách các giao dịch thanh toán trong kỳ (phân trang)
        $payments = (clone $currentPaymentsQuery)
            ->with(['user', 'payable'])
            ->orderByDesc(DB::raw('COALESCE(payments.paid_at, payments.created_at)'))
            ->paginate(10)
            ->appends($request->query());

        $allPayments = (clone $currentPaymentsQuery)
            ->with(['user', 'payable'])
            ->orderByDesc(DB::raw('COALESCE(payments.paid_at, payments.created_at)'))
            ->get();

        $facilities = Facility::all();

        return [
            'period' => $period,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'selectedType' => $selectedType,
            'periodLabel' => $periodLabel,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $totalRevenue,
            'bookingRevenue' => $bookingRevenue,
            'saleRevenue' => $saleRevenue,
            'subscriptionRevenue' => $subscriptionRevenue,
            'bookingPaymentsCount' => $bookingPaymentsCount,
            'salePaymentsCount' => $salePaymentsCount,
            'subscriptionPaymentsCount' => $subscriptionPaymentsCount,
            'paidPaymentsCount' => $paidPaymentsCount,
            'totalPaymentsCount' => $totalPaymentsCount,
            'completedBookingsCount' => $completedBookingsCount,
            'totalBookingsCount' => $totalBookingsCount,
            'totalSalesCount' => $totalSalesCount,
            'averageOrderValue' => $averageOrderValue,
            'growthRate' => $growthRate,
            'prevRevenue' => $prevRevenue,
            'sourcesBreakdown' => $sourcesBreakdown,
            'paymentMethodsBreakdown' => $paymentMethodsBreakdown,
            'daysInMonth' => $daysInMonth,
            'maxDailyRevenue' => $maxDailyRevenue,
            'monthlyBreakdown' => $monthlyBreakdown,
            'maxMonthlyRevenue' => $maxMonthlyRevenue,
            'sportTypesBreakdown' => $sportTypesBreakdown,
            'payments' => $payments,
            'allPayments' => $allPayments,
            'facilities' => $facilities,
            'sportTypes' => $sportTypes,
        ];
    }
}
