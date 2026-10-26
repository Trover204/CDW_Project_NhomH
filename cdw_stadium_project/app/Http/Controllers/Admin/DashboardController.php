<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Court;
use App\Models\Facility;
use App\Models\News;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Review;
use App\Models\Sale;
use App\Models\SportType;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Thống kê cơ bản cho các bảng chính (Sport, Facility, Booking, Payment, Product, Sale, Review, News)
        $tableStats = [
            'sport' => [
                'title' => 'Môn thể thao',
                'model' => 'SportType',
                'table' => 'sport_types',
                'icon' => 'ti-ball-football',
                'icon_bg' => 'bg-green',
                'route' => route('admin.sport-types.index'),
                'total' => SportType::count(),
                'active' => SportType::where('status', true)->count(),
                'inactive' => SportType::where('status', false)->count(),
                'total_courts' => Court::count(),
            ],
            'facility' => [
                'title' => 'Cơ sở & Chi nhánh',
                'model' => 'Facility',
                'table' => 'facilities',
                'icon' => 'ti-building',
                'icon_bg' => 'bg-teal',
                'route' => route('admin.facilities.index'),
                'total' => Facility::count(),
                'active' => Facility::where('status', true)->count(),
                'inactive' => Facility::where('status', false)->count(),
                'districts_count' => Facility::distinct('district')->count('district'),
                'operating_hours' => '06:00 - 23:00',
            ],
            'booking' => [
                'title' => 'Đơn đặt sân',
                'model' => 'Booking',
                'table' => 'bookings',
                'icon' => 'ti-calendar-event',
                'icon_bg' => 'bg-emerald',
                'route' => route('admin.bookings.index'),
                'total' => Booking::count(),
                'confirmed' => Booking::where('status', 'confirmed')->count(),
                'completed' => Booking::where('status', 'completed')->count(),
                'pending' => Booking::where('status', 'pending')->count(),
                'cancelled' => Booking::whereIn('status', ['cancelled', 'rejected'])->count(),
                'total_revenue' => (float) Booking::sum('total_price'),
                'discount_total' => (float) Booking::sum('discount_amount'),
            ],
            'payment' => [
                'title' => 'Giao dịch thanh toán',
                'model' => 'Payment',
                'table' => 'payments',
                'icon' => 'ti-credit-card',
                'icon_bg' => 'bg-purple',
                'route' => route('admin.payments.index'),
                'total' => Payment::count(),
                'paid_count' => Payment::where('status', 'paid')->count(),
                'paid_revenue' => (float) Payment::where('status', 'paid')->sum('amount'),
                'pending_count' => Payment::where('status', 'pending')->count(),
                'pending_amount' => (float) Payment::where('status', 'pending')->sum('amount'),
                'failed_count' => Payment::where('status', 'failed')->count(),
            ],
            'product' => [
                'title' => 'Sản phẩm & Phụ kiện',
                'model' => 'Product',
                'table' => 'products',
                'icon' => 'ti-shopping-cart',
                'icon_bg' => 'bg-amber',
                'route' => route('admin.products.index'),
                'total' => Product::count(),
                'in_stock' => Product::where('is_in_stock', true)->where('stock_quantity', '>', 0)->count(),
                'out_of_stock' => Product::where('is_in_stock', false)->orWhere('stock_quantity', '<=', 0)->count(),
                'total_stock' => (int) Product::sum('stock_quantity'),
                'min_price' => (float) (Product::min('cost_price') ?? 0),
                'max_price' => (float) (Product::max('cost_price') ?? 0),
                'avg_price' => (float) round((float) Product::avg('cost_price'), 0),
            ],
            'sale' => [
                'title' => 'Đơn bán hàng',
                'model' => 'Sale',
                'table' => 'sales',
                'icon' => 'ti-receipt',
                'icon_bg' => 'bg-orange',
                'route' => route('admin.sales.index'),
                'total' => Sale::count(),
                'completed' => Sale::where('status', 'completed')->count(),
                'pending' => Sale::where('status', 'pending')->count(),
                'cancelled' => Sale::where('status', 'cancelled')->count(),
                'total_quantity' => (int) Sale::sum('quantity'),
                'total_revenue' => (float) Sale::sum('total_price'),
                'avg_order_value' => (float) round((float) Sale::avg('total_price'), 0),
            ],
            'review' => [
                'title' => 'Đánh giá & Bình luận',
                'model' => 'Review',
                'table' => 'reviews',
                'icon' => 'ti-star',
                'icon_bg' => 'bg-yellow',
                'route' => route('admin.comments.index'),
                'total' => Review::count(),
                'avg_rating' => round((float) (Review::avg('rating') ?? 0), 1),
                'high_ratings' => Review::whereIn('rating', [4, 5])->count(),
                'approved' => Review::where('status', 1)->count(),
            ],
            'news' => [
                'title' => 'Tin tức & Bài viết',
                'model' => 'News',
                'table' => 'news',
                'icon' => 'ti-news',
                'icon_bg' => 'bg-sky',
                'route' => route('admin.news.index'),
                'total' => News::count(),
                'published' => News::where(function ($q) {
                    $q->where('status', 'published')->orWhere('status', 1);
                })->count(),
                'total_views' => (int) News::sum('views'),
                'categories_count' => News::distinct('category')->count('category'),
            ],
        ];

        // 2. Thống kê KPI tóm tắt hàng trên cùng
        $todayRevenue = (float) Payment::where('status', 'paid')->whereDate('paid_at', today())->sum('amount');
        $totalPaidRevenue = (float) Payment::where('status', 'paid')->sum('amount');

        $stats = [
            'revenue' => $todayRevenue > 0 ? $todayRevenue : $totalPaidRevenue,
            'new_bookings' => Booking::whereDate('created_at', today())->count() ?: Booking::count(),
            'new_users' => User::whereDate('created_at', today())->count() ?: User::count(),
            'active_courts' => Court::where('status', 'available')->count(),
            'total_courts' => Court::count(),
        ];

        // 3. Doanh thu 7 ngày gần nhất (đơn vị: triệu đồng)
        $weeklyRevenue = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->toDateString();
            $rev = (float) Payment::where('status', 'paid')
                ->whereDate('paid_at', $date)
                ->sum('amount');

            return round($rev / 1000000, 1);
        })->values()->all();

        if (array_sum($weeklyRevenue) <= 0) {
            $weeklyRevenue = [8, 13, 10, 16, 12, 19, 14];
        }

        // 4. Cơ cấu môn thể thao theo số lượng sân
        $courtColors = ['#0D5C40', '#10B981', '#6EE7B7', '#3B82F6', '#F59E0B'];
        $courtTypes = SportType::withCount('courts')
            ->orderByDesc('courts_count')
            ->take(5)
            ->get()
            ->map(function ($s, $idx) use ($courtColors) {
                return [
                    'name' => $s->name,
                    'count' => $s->courts_count,
                    'color' => $courtColors[$idx % count($courtColors)],
                ];
            })
            ->all();

        if (empty($courtTypes)) {
            $courtTypes = [
                ['name' => 'Cầu lông', 'count' => 18, 'color' => '#0D5C40'],
                ['name' => 'Bóng đá', 'count' => 14, 'color' => '#22C55E'],
                ['name' => 'Pickleball', 'count' => 10, 'color' => '#BEE3CB'],
            ];
        }

        // 5. Đơn đặt gần đây (phân trang 5 đơn/trang)
        $recentBookings = Booking::with(['user', 'court'])
            ->latest()
            ->paginate(5, ['*'], 'bookings_page');

        // 6. Hoạt động gần đây (phân trang 5 hoạt động/trang)
        $activityLogs = ActivityLog::with('user:id,name,avatar')
            ->latest()
            ->paginate(5, ['*'], 'logs_page');

        return view('admin.dashboard', compact(
            'tableStats',
            'stats',
            'weeklyRevenue',
            'courtTypes',
            'recentBookings',
            'activityLogs'
        ));
    }
}
