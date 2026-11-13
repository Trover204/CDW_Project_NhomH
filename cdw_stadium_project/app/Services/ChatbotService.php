<?php

namespace App\Services;

use App\Models\Court;
use App\Models\News;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ChatbotService
{
    /**
     * Kiểm tra xem user có gói hội viên còn hiệu lực hay không.
     */
    public function getActiveSubscription(?User $user): ?Subscription
    {
        if (!$user) {
            return null;
        }

        return Subscription::with('plan')
            ->where('user_id', $user->id)
            ->where('status', true)
            ->where('expires_at', '>', now())
            ->latest('expires_at')
            ->first();
    }

    /**
     * Tìm kiếm gợi ý sản phẩm thể thao (ưu tiên Elasticsearch qua Laravel Scout nếu bật).
     */
    public function searchProducts(string $keyword, int $limit = 4): Collection
    {
        $keyword = trim($keyword);

        if ($keyword !== '' && in_array(config('scout.driver'), ['elasticsearch', 'elastic'], true)) {
            try {
                $searchResult = Product::search($keyword)
                    ->where('is_active', true)
                    ->take($limit)
                    ->get();

                if ($searchResult->isNotEmpty()) {
                    return $searchResult;
                }
            } catch (\Throwable $e) {
                // Fallback về database query
            }
        }

        $query = Product::where('is_active', true);

        if ($keyword !== '') {
            $terms = explode(' ', $keyword);
            $query->where(function ($q) use ($keyword, $terms) {
                $q->where('name', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('team', 'like', "%{$keyword}%");

                foreach ($terms as $t) {
                    if (mb_strlen($t) >= 2) {
                        $q->orWhere('name', 'like', "%{$t}%")
                            ->orWhere('team', 'like', "%{$t}%");
                    }
                }
            });
        }

        return $query->latest('id')->take($limit)->get();
    }

    /**
     * Xử lý hội thoại tự động & chăm sóc khách hàng VIP.
     */
    public function handleConversation(User $user, Subscription $subscription, string $message, ?string $action = null): array
    {
        $planName = $subscription->plan?->name ?? __('Hội viên VIP');
        $expiryDate = $subscription->expires_at?->format('d/m/Y') ?? __('Đang hoạt động');
        $cleaned = mb_strtolower(trim($message));

        // Format card sản phẩm dùng chung
        $formatProducts = function ($products) {
            return $products->map(function (Product $p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'team' => $p->team ?? __('Chính hãng'),
                    'price' => number_format((float) $p->cost_price, 0, ',', '.') . '₫',
                    'raw_price' => (float) $p->cost_price,
                    'image' => $p->image_url ?? asset('img_new/merch/ao_real_madrid_home_2425.jpg'),
                    'is_in_stock' => (bool) $p->is_in_stock,
                    'url' => route('products.checkout', $p->id),
                ];
            })->values()->all();
        };

        // 1. Action: Yêu cầu gợi ý sản phẩm hoặc tin nhắn chứa từ khóa mua sắm / áo / merch
        if (
            $action === 'suggest_products' ||
            Str::contains($cleaned, ['sản phẩm', 'áo đấu', 'áo bóng đá', 'merch', 'phụ kiện', 'mua áo', 'clb', 'quần áo', 'giày'])
        ) {
            // Trích xuất từ khóa tìm kiếm nếu có tên clb/đội tuyển
            $searchKey = '';
            $keywords = ['real madrid', 'manchester united', 'arsenal', 'barcelona', 'man city', 'liverpool', 'việt nam', 'argentina', 'bồ đào nha', 'psg', 'bayern', 'miami', 'al nassr'];
            foreach ($keywords as $kw) {
                if (Str::contains($cleaned, $kw)) {
                    $searchKey = $kw;
                    break;
                }
            }

            $products = $this->searchProducts($searchKey, 4);

            return [
                'reply' => __('Dưới đây là một số mẫu sản phẩm & áo đấu thể thao chính hãng nổi bật dành riêng cho quý hội viên :plan. Bạn có thể bấm để xem chi tiết và đặt mua ngay:', ['plan' => $planName]),
                'type' => 'products',
                'data' => [
                    'products' => $formatProducts($products),
                ],
                'suggestions' => [
                    __('Xem thêm áo Real Madrid'),
                    __('Xem thêm áo Manchester United'),
                    __('Đặc quyền gói thành viên của tôi'),
                    __('Cách đặt lịch giữ sân'),
                ],
            ];
        }

        // 2. Action: Hỏi về quyền lợi thành viên
        if (
            $action === 'membership_perks' ||
            Str::contains($cleaned, ['quyền lợi', 'gói thành viên', 'hội viên', 'vip', 'ưu đãi', 'giảm giá', 'hạn dùng', 'hạn sử dụng'])
        ) {
            $replyText = __('Quý khách đang sở hữu gói **:plan** (Thời hạn đến ngày **:date**).', [
                'plan' => $planName,
                'date' => $expiryDate,
            ]) . "\n\n" .
                __('Các quyền lợi nổi bật:') . "\n" .
                __('• Ưu đãi giảm giá trực tiếp khi thanh toán tiền giờ đặt sân.') . "\n" .
                __('• Ưu tiên hỗ trợ kỹ thuật và chăm sóc khách hàng trực tuyến 24/7.') . "\n" .
                __('• Được thông báo sớm các chương trình tặng voucher phụ kiện & áo đấu mới.');

            return [
                'reply' => $replyText,
                'type' => 'membership_info',
                'data' => [
                    'plan_name' => $planName,
                    'expiry_date' => $expiryDate,
                ],
                'suggestions' => [
                    __('Gợi ý áo đấu hot nhất'),
                    __('Đặt sân thể thao ngay'),
                    __('Hỗ trợ huỷ hoặc đổi lịch'),
                ],
            ];
        }

        // 3. Action: Hướng dẫn đặt sân & giữ chỗ
        if (
            $action === 'booking_help' ||
            Str::contains($cleaned, ['đặt sân', 'thuê sân', 'khung giờ', 'lịch trống', 'hủy sân', 'đổi sân', 'thanh toán'])
        ) {
            $courtsCount = Court::where('status', 'available')->count();

            $bookingText = __('Hệ thống Sân Thể Thao hiện có **:count sân** sẵn sàng đón quý khách.', ['count' => $courtsCount]) . "\n\n" .
                __('Quy trình đặt sân:') . "\n" .
                __('1. Truy cập mục **Danh sách sân** và lọc theo bộ môn (Bóng đá, Cầu lông, Pickleball, v.v.).') . "\n" .
                __('2. Chọn khung giờ còn trống và kiểm tra chiết khấu hội viên :plan.', ['plan' => $planName]) . "\n" .
                __('3. Xác nhận thanh toán qua VNPay hoặc Quét mã QR bảo mật để nhận vé xác nhận ngay lập tức.');

            return [
                'reply' => $bookingText,
                'type' => 'text',
                'data' => [
                    'booking_url' => route('courts.index'),
                ],
                'suggestions' => [
                    __('Đi đến trang đặt sân'),
                    __('Gợi ý trang phục thi đấu'),
                    __('Liên hệ hotline trực ban'),
                ],
            ];
        }

        // 4. Action: Tin tức & Mẹo tập luyện
        if (Str::contains($cleaned, ['tin tức', 'mẹo', 'tập luyện', 'giải đấu', 'sự kiện'])) {
            $news = News::where('status', 'published')->latest()->take(2)->get();
            $newsList = $news->map(fn ($n) => "• [{$n->title}](" . route('news.show', $n->slug) . ")")->implode("\n");

            return [
                'reply' => __('Tin tức và bí quyết thể thao mới nhất hôm nay:' . "\n\n" . ($newsList ?: __('Hiện chưa có bài viết mới.'))),
                'type' => 'text',
                'data' => [],
                'suggestions' => [
                    __('Gợi ý phụ kiện thể thao'),
                    __('Kiểm tra gói thành viên'),
                ],
            ];
        }

        // 5. Chào hỏi thông thường hoặc câu hỏi chăm sóc khách hàng
        $recommendedProducts = $this->searchProducts('', 3);
        $welcomeText = __('Xin chào **:user**! Em là trợ lý CSKH SportZone dành riêng cho quý hội viên **:plan**.', [
            'user' => $user->name,
            'plan' => $planName,
        ]) . "\n\n" .
            __('Mình có thể giúp gì cho quý khách hôm nay? Quý khách có thể yêu cầu gợi ý phụ kiện thể thao, kiểm tra quyền lợi hoặc tra cứu quy trình đặt sân bãi:');

        return [
            'reply' => $welcomeText,
            'type' => 'mixed',
            'data' => [
                'products' => $formatProducts($recommendedProducts),
            ],
            'suggestions' => [
                __('Gợi ý áo đấu hot nhất'),
                __('Quyền lợi gói thành viên'),
                __('Hướng dẫn đặt sân bóng'),
                __('Hotline trực ban 1900.6868'),
            ],
        ];
    }
}
