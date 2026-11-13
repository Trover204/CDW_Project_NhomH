<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\ChatbotMessageRequest;
use App\Services\ChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    public function __construct(
        protected ChatbotService $chatbotService
    ) {}

    /**
     * Trả về trạng thái quyền truy cập chatbot và tin nhắn chào mừng.
     */
    public function status(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'can_chat' => false,
                'reason' => 'unauthenticated',
                'message' => __('Vui lòng đăng nhập để sử dụng trợ lý ảo.'),
                'login_url' => route('login'),
            ], 200);
        }

        $subscription = $this->chatbotService->getActiveSubscription($user);

        if (! $subscription) {
            return response()->json([
                'can_chat' => false,
                'reason' => 'no_subscription',
                'user_name' => $user->name,
                'message' => __('Tính năng Trợ lý ảo CSKH & Gợi ý sản phẩm độc quyền dành riêng cho khách hàng đã đăng ký gói thành viên.'),
                'plans_url' => route('plans.index'),
            ], 200);
        }

        // Lấy 3 sản phẩm gợi ý ban đầu
        $products = $this->chatbotService->searchProducts('', 3);
        $planName = $subscription->plan?->name ?? __('Hội viên VIP');

        return response()->json([
            'can_chat' => true,
            'user' => [
                'name' => $user->name,
                'plan_name' => $planName,
                'expires_at' => $subscription->expires_at?->format('d/m/Y'),
            ],
            'initial_message' => __('Xin chào **:user**! 👋 Em là trợ lý chăm sóc khách hàng độc quyền dành riêng cho thành viên **:plan**.', [
                'user' => $user->name,
                'plan' => $planName,
            ]),
            'suggestions' => [
                __('Gợi ý áo đấu hot nhất'),
                __('Đặc quyền gói thành viên của tôi'),
                __('Hướng dẫn đặt sân thể thao'),
            ],
        ]);
    }

    /**
     * Xử lý gửi tin nhắn từ người dùng.
     */
    public function sendMessage(ChatbotMessageRequest $request): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => __('Vui lòng đăng nhập.'),
            ], 401);
        }

        $subscription = $this->chatbotService->getActiveSubscription($user);

        if (! $subscription) {
            return response()->json([
                'success' => false,
                'message' => __('Bạn cần sở hữu gói thành viên đang hoạt động để sử dụng chatbot.'),
            ], 403);
        }

        $validated = $request->validated();
        $message = $validated['message'];
        $action = $validated['action'] ?? null;

        $response = $this->chatbotService->handleConversation($user, $subscription, $message, $action);

        return response()->json([
            'success' => true,
            'reply' => $response['reply'],
            'type' => $response['type'],
            'data' => $response['data'],
            'suggestions' => $response['suggestions'],
            'timestamp' => now()->format('H:i'),
        ]);
    }
}
