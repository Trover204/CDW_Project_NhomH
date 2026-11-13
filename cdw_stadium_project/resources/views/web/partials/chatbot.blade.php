@php
    $currentUser = Auth::user();
    $activeSub = null;
    if ($currentUser) {
        $activeSub = \App\Models\Subscription::with('plan')
            ->where('user_id', $currentUser->id)
            ->where('status', true)
            ->where('expires_at', '>', now())
            ->latest('expires_at')
            ->first();
    }
    $hasPlan = (bool) $activeSub;
@endphp

{{-- Floating Chatbot Trigger Button at Bottom-Right --}}
<div id="sportzone-chatbot-root">
    <button id="chatbot-toggle-btn" class="chatbot-floating-btn" type="button" aria-label="{{ __('Trợ lý CSKH & Gợi ý sản phẩm') }}">
        <div class="chatbot-btn-inner">
            <i class="ti ti-message-chatbot chatbot-icon-open"></i>
            <i class="ti ti-x chatbot-icon-close" style="display: none;"></i>
        </div>
        <span class="chatbot-floating-pulse"></span>
        <span class="chatbot-btn-badge">{{ __('VIP') }}</span>
    </button>

    {{-- Chatbot Drawer / Overlay Card --}}
    <div id="chatbot-overlay" class="chatbot-overlay-panel" style="display: none;">
        {{-- Header --}}
        <div class="chatbot-header">
            <div class="chatbot-header-info">
                <div class="chatbot-avatar-badge">
                    <i class="ti ti-headset"></i>
                    <span class="chatbot-status-dot"></span>
                </div>
                <div>
                    <h3 class="chatbot-title">{{ __('Trợ lý AI SportZone') }}</h3>
                    <p class="chatbot-subtitle">
                        @if($hasPlan)
                            <span class="chatbot-plan-chip"><i class="ti ti-crown"></i> {{ $activeSub->plan?->name ?? __('Hội viên VIP') }}</span>
                        @else
                            {{ __('CSKH & Tư vấn sản phẩm') }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="chatbot-header-actions">
                <button type="button" id="chatbot-minimize-btn" class="chatbot-action-btn" title="{{ __('Thu nhỏ') }}">
                    <i class="ti ti-minus"></i>
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="chatbot-body" id="chatbot-message-list">
            @guest
                {{-- Trạng thái chưa đăng nhập --}}
                <div class="chatbot-auth-gate">
                    <div class="gate-icon-circle"><i class="ti ti-lock"></i></div>
                    <h4>{{ __('Đăng nhập để trải nghiệm') }}</h4>
                    <p>{{ __('Trợ lý CSKH và gợi ý sản phẩm thể thao độc quyền dành riêng cho khách hàng của SportZone.') }}</p>
                    <a href="{{ route('login') }}" class="chatbot-gate-btn">
                        <i class="ti ti-login"></i> {{ __('Đăng nhập ngay') }}
                    </a>
                </div>
            @else
                @if(!$hasPlan)
                    {{-- Trạng thái chưa có gói thành viên --}}
                    <div class="chatbot-auth-gate">
                        <div class="gate-icon-circle vip-circle"><i class="ti ti-crown"></i></div>
                        <h4>{{ __('Đặc quyền Hội viên VIP') }}</h4>
                        <p>{{ __('Xin chào :name! Tính năng Trợ lý ảo AI CSKH & Gợi ý áo đấu/sản phẩm độc quyền chỉ mở cho thành viên có gói hoạt động.', ['name' => $currentUser->name]) }}</p>
                        <a href="{{ route('plans.index') }}" class="chatbot-gate-btn vip-btn">
                            <i class="ti ti-sparkles"></i> {{ __('Khám phá Gói Thành Viên') }}
                        </a>
                    </div>
                @else
                    {{-- Đã đăng nhập & có gói thành viên --}}
                    <div class="chatbot-message bot-msg">
                        <div class="msg-avatar"><i class="ti ti-robot"></i></div>
                        <div class="msg-content">
                            <div class="msg-bubble">
                                {{ __('Xin chào :name! 👋 Em là trợ lý chăm sóc khách hàng và gợi ý sản phẩm dành riêng cho quý hội viên **:plan**.', [
                                    'name' => $currentUser->name,
                                    'plan' => $activeSub->plan?->name ?? __('VIP')
                                ]) }}
                                <br><br>
                                {{ __('Quý khách cần em tư vấn mẫu áo thi đấu mới nhất, kiểm tra đặc quyền gói thành viên hay hướng dẫn đặt sân bãi?') }}
                            </div>
                            <span class="msg-time">{{ now()->format('H:i') }}</span>
                        </div>
                    </div>

                    {{-- Quick Action Chips --}}
                    <div class="chatbot-quick-chips" id="chatbot-chips-wrap">
                        <button type="button" class="quick-chip" data-action="suggest_products">
                            <i class="ti ti-shirt"></i> {{ __('Gợi ý áo đấu hot') }}
                        </button>
                        <button type="button" class="quick-chip" data-action="membership_perks">
                            <i class="ti ti-crown"></i> {{ __('Quyền lợi của tôi') }}
                        </button>
                        <button type="button" class="quick-chip" data-action="booking_help">
                            <i class="ti ti-calendar-event"></i> {{ __('Hướng dẫn đặt sân') }}
                        </button>
                    </div>
                @endif
            @endguest
        </div>

        {{-- Footer Input (Chỉ khả dụng khi đã đăng nhập & có gói thành viên) --}}
        @if($currentUser && $hasPlan)
            <form id="chatbot-form" class="chatbot-footer">
                @csrf
                <div class="chatbot-input-wrap">
                    <input type="text" id="chatbot-input" name="message" placeholder="{{ __('Nhập câu hỏi, tên đội bóng, phụ kiện cần tìm...') }}" autocomplete="off" maxlength="1000">
                    <button type="submit" id="chatbot-send-btn" class="chatbot-send-btn" aria-label="{{ __('Gửi tin nhắn') }}">
                        <i class="ti ti-send"></i>
                    </button>
                </div>
            </form>
        @else
            <div class="chatbot-footer-locked">
                <i class="ti ti-shield-lock"></i>
                <span>
                    @guest
                        {{ __('Đăng nhập tài khoản để chat') }}
                    @else
                        {{ __('Kích hoạt gói thành viên để mở khóa') }}
                    @endguest
                </span>
            </div>
        @endif
    </div>
</div>

<style>
/* =====================================================
   SPORTZONE CHATBOT OVERLAY (BOTTOM-RIGHT)
===================================================== */
#sportzone-chatbot-root {
    position: fixed;
    right: 24px;
    bottom: 24px;
    z-index: 99999;
    font-family: 'Be Vietnam Pro', -apple-system, BlinkMacSystemFont, sans-serif;
}

/* Floating Trigger Button */
.chatbot-floating-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #FFFFFF;
    border: none;
    cursor: pointer;
    box-shadow: 0 8px 25px rgba(236, 72, 153, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s;
}
.chatbot-floating-btn:hover {
    transform: scale(1.08) translateY(-2px);
    box-shadow: 0 12px 30px rgba(236, 72, 153, 0.6);
}
.chatbot-btn-inner {
    font-size: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.chatbot-floating-pulse {
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    border: 2px solid #FF2E93;
    animation: chatbotPulse 2.2s infinite;
    pointer-events: none;
}
@keyframes chatbotPulse {
    0% { transform: scale(0.95); opacity: 0.9; }
    70% { transform: scale(1.35); opacity: 0; }
    100% { transform: scale(1.35); opacity: 0; }
}
.chatbot-btn-badge {
    position: absolute;
    top: -3px;
    right: -3px;
    background: #181824;
    color: #F472B6;
    border: 1.5px solid #F472B6;
    font-size: 10px;
    font-weight: 800;
    padding: 1px 6px;
    border-radius: 999px;
    letter-spacing: 0.5px;
}

/* Overlay Panel Window */
.chatbot-overlay-panel {
    position: absolute;
    right: 0;
    bottom: 74px;
    width: 380px;
    max-width: calc(100vw - 32px);
    height: 540px;
    max-height: calc(100vh - 120px);
    background: var(--bg-white, #FFFFFF);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 20px;
    box-shadow: 0 18px 45px rgba(24, 24, 36, 0.22);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: chatbotSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    transform-origin: bottom right;
}
@keyframes chatbotSlideUp {
    0% { opacity: 0; transform: translateY(20px) scale(0.95); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}

/* Header */
.chatbot-header {
    background: linear-gradient(135deg, #181824 0%, #242436 100%);
    color: #FFFFFF;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 2px solid #EC4899;
}
.chatbot-header-info {
    display: flex;
    align-items: center;
    gap: 12px;
}
.chatbot-avatar-badge {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    position: relative;
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.35);
}
.chatbot-status-dot {
    position: absolute;
    bottom: -2px;
    right: -2px;
    width: 11px;
    height: 11px;
    background: #10B981;
    border: 2px solid #181824;
    border-radius: 50%;
}
.chatbot-title {
    margin: 0;
    font-size: 15px;
    font-weight: 700;
    color: #FFFFFF;
    letter-spacing: 0.3px;
}
.chatbot-subtitle {
    margin: 2px 0 0;
    font-size: 11.5px;
    color: #D1D5DB;
}
.chatbot-plan-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: rgba(236, 72, 153, 0.25);
    color: #F472B6;
    padding: 1px 8px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 11px;
}
.chatbot-action-btn {
    background: rgba(255, 255, 255, 0.12);
    border: none;
    color: #FFFFFF;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.2s;
}
.chatbot-action-btn:hover {
    background: rgba(255, 255, 255, 0.22);
}

/* Body & Message Stream */
.chatbot-body {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: var(--bg-page, #FAF7F9);
    display: flex;
    flex-direction: column;
    gap: 14px;
    scroll-behavior: smooth;
}
.chatbot-message {
    display: flex;
    gap: 10px;
    max-width: 90%;
    animation: fadeIn 0.25s ease;
}
.chatbot-message.bot-msg {
    align-self: flex-start;
}
.chatbot-message.user-msg {
    align-self: flex-end;
    flex-direction: row-reverse;
}
.msg-avatar {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    background: #FCE7F3;
    color: #EC4899;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.user-msg .msg-avatar {
    background: #181824;
    color: #FFFFFF;
}
.msg-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.msg-bubble {
    padding: 12px 14px;
    border-radius: 14px;
    font-size: 13.5px;
    line-height: 1.5;
    word-break: break-word;
}
.bot-msg .msg-bubble {
    background: var(--bg-white, #FFFFFF);
    color: var(--ink, #181824);
    border: 1px solid var(--line, #F1E4EC);
    border-top-left-radius: 4px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}
.user-msg .msg-bubble {
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #FFFFFF;
    border-top-right-radius: 4px;
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.25);
}
.msg-time {
    font-size: 10.5px;
    color: var(--ink-muted, #9CA3AF);
    padding: 0 4px;
}
.user-msg .msg-time {
    text-align: right;
}

/* Auth Gate Container */
.chatbot-auth-gate {
    margin: auto 0;
    text-align: center;
    padding: 24px 16px;
    background: var(--bg-white, #FFFFFF);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
}
.gate-icon-circle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #FEE2E2;
    color: #EF4444;
    font-size: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
}
.gate-icon-circle.vip-circle {
    background: #FDF2F8;
    color: #EC4899;
    box-shadow: 0 4px 16px rgba(236, 72, 153, 0.2);
}
.chatbot-auth-gate h4 {
    margin: 0 0 6px;
    font-size: 16px;
    font-weight: 700;
    color: var(--ink, #181824);
}
.chatbot-auth-gate p {
    margin: 0 0 16px;
    font-size: 13px;
    color: var(--ink-soft, #6B7280);
    line-height: 1.5;
}
.chatbot-gate-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #181824;
    color: #FFFFFF;
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none;
    transition: transform 0.2s, background 0.2s;
}
.chatbot-gate-btn:hover {
    background: #000000;
    transform: translateY(-2px);
}
.chatbot-gate-btn.vip-btn {
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    box-shadow: 0 4px 14px rgba(236, 72, 153, 0.35);
}

/* Quick Action Chips */
.chatbot-quick-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 4px;
}
.quick-chip {
    background: var(--bg-white, #FFFFFF);
    border: 1px solid var(--line, #F1E4EC);
    color: var(--ink-soft, #4B5563);
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s;
}
.quick-chip i {
    color: #EC4899;
}
.quick-chip:hover {
    border-color: #EC4899;
    color: #EC4899;
    background: #FDF2F8;
    transform: translateY(-1px);
}

/* Product Cards Carousel inside Chat */
.chatbot-products-carousel {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 8px;
}
.chat-product-item {
    display: flex;
    align-items: center;
    gap: 10px;
    background: var(--bg-white, #FFFFFF);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 12px;
    padding: 8px 10px;
    text-decoration: none;
    color: inherit;
    transition: all 0.2s;
}
.chat-product-item:hover {
    border-color: #EC4899;
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.15);
    transform: translateY(-1px);
}
.chat-product-img {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    object-fit: cover;
    background: #F9FAFB;
    flex-shrink: 0;
}
.chat-product-details {
    flex: 1;
    min-width: 0;
}
.chat-product-name {
    font-size: 12.5px;
    font-weight: 700;
    color: var(--ink, #181824);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin: 0 0 2px;
}
.chat-product-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 11.5px;
}
.chat-product-price {
    font-weight: 800;
    color: #EC4899;
}
.chat-product-cta {
    font-size: 11px;
    color: #2563EB;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 2px;
}

/* Typing Indicator */
.chatbot-typing-indicator {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 8px 14px;
    background: var(--bg-white, #FFFFFF);
    border: 1px solid var(--line, #F1E4EC);
    border-radius: 14px;
    border-top-left-radius: 4px;
    width: fit-content;
}
.chatbot-typing-indicator span {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #EC4899;
    animation: typingBounce 1.4s infinite ease-in-out both;
}
.chatbot-typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
.chatbot-typing-indicator span:nth-child(2) { animation-delay: -0.16s; }
@keyframes typingBounce {
    0%, 80%, 100% { transform: scale(0); opacity: 0.3; }
    40% { transform: scale(1); opacity: 1; }
}

/* Footer Input Area */
.chatbot-footer {
    padding: 10px 14px;
    background: var(--bg-white, #FFFFFF);
    border-top: 1px solid var(--line, #F1E4EC);
}
.chatbot-input-wrap {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--bg-page, #FAF7F9);
    border: 1.5px solid var(--line, #F1E4EC);
    border-radius: 999px;
    padding: 4px 6px 4px 14px;
    transition: border-color 0.2s, background 0.2s;
}
.chatbot-input-wrap:focus-within {
    border-color: #EC4899;
    background: #FFFFFF;
    box-shadow: 0 0 0 3px rgba(236, 72, 153, 0.12);
}
.chatbot-input-wrap input {
    flex: 1;
    border: none;
    outline: none;
    background: transparent;
    font-size: 13px;
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
    font-family: inherit;
}
.chatbot-input-wrap input::placeholder {
    color: #9CA3AF !important;
    -webkit-text-fill-color: #9CA3AF !important;
}
.chatbot-send-btn {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, #FF2E93 0%, #DB2777 100%);
    color: #FFFFFF;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: transform 0.2s, opacity 0.2s;
    flex-shrink: 0;
}
.chatbot-send-btn:hover {
    transform: scale(1.08);
}
.chatbot-send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}
.chatbot-footer-locked {
    padding: 12px 16px;
    background: var(--bg-white, #FFFFFF);
    border-top: 1px solid var(--line, #F1E4EC);
    font-size: 12px;
    color: var(--ink-muted, #9CA3AF);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

/* Dark Theme Adaptations */
[data-theme="dark"] .chatbot-overlay-panel {
    background: #181824;
    border-color: #2D2D42;
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.55);
}
[data-theme="dark"] .chatbot-body {
    background: #0F0F17;
}
[data-theme="dark"] .bot-msg .msg-bubble {
    background: #1F1F30;
    color: #F3F4F6;
    border-color: #2D2D42;
}
[data-theme="dark"] .chatbot-footer {
    background: #181824;
    border-color: #2D2D42;
}
[data-theme="dark"] .chatbot-input-wrap {
    background: #FFFFFF !important;
    border-color: #FCE7F3 !important;
}
[data-theme="dark"] .chatbot-input-wrap input {
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
}
[data-theme="dark"] .quick-chip {
    background: #1F1F30;
    border-color: #2D2D42;
    color: #E2E8F0;
}
[data-theme="dark"] .quick-chip:hover {
    background: rgba(236, 72, 153, 0.2);
    border-color: #EC4899;
    color: #F472B6;
}
[data-theme="dark"] .chat-product-item {
    background: #1F1F30;
    border-color: #2D2D42;
}
[data-theme="dark"] .chat-product-name {
    color: #F3F4F6;
}
[data-theme="dark"] .chatbot-auth-gate {
    background: #181824;
    border-color: #2D2D42;
}
[data-theme="dark"] .chatbot-auth-gate h4 {
    color: #FFFFFF;
}
[data-theme="dark"] .chatbot-auth-gate p {
    color: #9CA3AF;
}
[data-theme="dark"] .chatbot-typing-indicator {
    background: #1F1F30;
    border-color: #2D2D42;
}
[data-theme="dark"] .chatbot-footer-locked {
    background: #181824;
    border-color: #2D2D42;
}

@media (max-width: 480px) {
    #sportzone-chatbot-root {
        right: 16px;
        bottom: 16px;
    }
    .chatbot-overlay-panel {
        width: calc(100vw - 32px);
        height: 480px;
        bottom: 68px;
    }
}
</style>

<script nonce="{{ $cspNonce ?? '' }}">
document.addEventListener('DOMContentLoaded', function () {
    const root = document.getElementById('sportzone-chatbot-root');
    if (!root) return;

    const toggleBtn = document.getElementById('chatbot-toggle-btn');
    const overlay = document.getElementById('chatbot-overlay');
    const minimizeBtn = document.getElementById('chatbot-minimize-btn');
    const iconOpen = toggleBtn.querySelector('.chatbot-icon-open');
    const iconClose = toggleBtn.querySelector('.chatbot-icon-close');
    const messageList = document.getElementById('chatbot-message-list');
    const form = document.getElementById('chatbot-form');
    const input = document.getElementById('chatbot-input');
    const sendBtn = document.getElementById('chatbot-send-btn');
    const chipsWrap = document.getElementById('chatbot-chips-wrap');

    const canChat = {{ $currentUser && $hasPlan ? 'true' : 'false' }};

    function toggleChatbot() {
        const isHidden = overlay.style.display === 'none' || overlay.style.display === '';
        if (isHidden) {
            overlay.style.display = 'flex';
            iconOpen.style.display = 'none';
            iconClose.style.display = 'inline-block';
            if (input) input.focus();
            scrollToBottom();
        } else {
            overlay.style.display = 'none';
            iconOpen.style.display = 'inline-block';
            iconClose.style.display = 'none';
        }
    }

    if (toggleBtn) toggleBtn.addEventListener('click', toggleChatbot);
    if (minimizeBtn) minimizeBtn.addEventListener('click', toggleChatbot);

    function scrollToBottom() {
        if (messageList) {
            messageList.scrollTop = messageList.scrollHeight;
        }
    }

    function appendUserMessage(text) {
        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const div = document.createElement('div');
        div.className = 'chatbot-message user-msg';
        div.innerHTML = `
            <div class="msg-avatar"><i class="ti ti-user"></i></div>
            <div class="msg-content">
                <div class="msg-bubble">${escapeHtml(text)}</div>
                <span class="msg-time">${time}</span>
            </div>
        `;
        messageList.appendChild(div);
        scrollToBottom();
    }

    function showTypingIndicator() {
        const div = document.createElement('div');
        div.id = 'chatbot-typing';
        div.className = 'chatbot-message bot-msg';
        div.innerHTML = `
            <div class="msg-avatar"><i class="ti ti-robot"></i></div>
            <div class="msg-content">
                <div class="chatbot-typing-indicator">
                    <span></span><span></span><span></span>
                </div>
            </div>
        `;
        messageList.appendChild(div);
        scrollToBottom();
    }

    function removeTypingIndicator() {
        const typing = document.getElementById('chatbot-typing');
        if (typing) typing.remove();
    }

    function escapeHtml(string) {
        return String(string)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function formatBotText(raw) {
        let text = escapeHtml(raw);
        // Replace bold **text**
        text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // Replace line breaks
        text = text.replace(/\n/g, '<br>');
        return text;
    }

    function appendBotResponse(reply, type, data, suggestions) {
        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        const div = document.createElement('div');
        div.className = 'chatbot-message bot-msg';

        let productsHtml = '';
        if (data && data.products && data.products.length > 0) {
            productsHtml = '<div class="chatbot-products-carousel">';
            data.products.forEach(p => {
                productsHtml += `
                    <a href="${p.url}" class="chat-product-item" target="_blank">
                        <img src="${p.image}" alt="${escapeHtml(p.name)}" class="chat-product-img">
                        <div class="chat-product-details">
                            <h5 class="chat-product-name">${escapeHtml(p.name)}</h5>
                            <div class="chat-product-meta">
                                <span class="chat-product-price">${p.price}</span>
                                <span class="chat-product-cta">${escapeHtml(p.team)} <i class="ti ti-arrow-right"></i></span>
                            </div>
                        </div>
                    </a>
                `;
            });
            productsHtml += '</div>';
        }

        div.innerHTML = `
            <div class="msg-avatar"><i class="ti ti-robot"></i></div>
            <div class="msg-content">
                <div class="msg-bubble">
                    ${formatBotText(reply)}
                    ${productsHtml}
                </div>
                <span class="msg-time">${time}</span>
            </div>
        `;
        messageList.appendChild(div);

        // Update chips if suggestions exist
        if (chipsWrap && suggestions && suggestions.length > 0) {
            chipsWrap.innerHTML = '';
            suggestions.forEach(s => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'quick-chip';
                btn.innerHTML = `<i class="ti ti-sparkles"></i> ${escapeHtml(s)}`;
                btn.addEventListener('click', () => {
                    handleUserQuery(s, null);
                });
                chipsWrap.appendChild(btn);
            });
            messageList.appendChild(chipsWrap);
        }

        scrollToBottom();
    }

    async function handleUserQuery(messageText, action) {
        if (!messageText || !messageText.trim()) return;
        if (!canChat) return;

        appendUserMessage(messageText);
        if (input) input.value = '';
        if (sendBtn) sendBtn.disabled = true;
        showTypingIndicator();

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                || '{{ csrf_token() }}';

            const res = await fetch('{{ route('chatbot.message') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    message: messageText,
                    action: action
                })
            });

            removeTypingIndicator();

            if (res.ok) {
                const data = await res.json();
                appendBotResponse(data.reply, data.type, data.data, data.suggestions);
            } else {
                const err = await res.json().catch(() => ({}));
                appendBotResponse(err.message || '{{ __('Hệ thống trợ lý ảo đang bảo trì. Vui lòng thử lại sau.') }}', 'text', null, []);
            }
        } catch (e) {
            removeTypingIndicator();
            appendBotResponse('{{ __('Không thể kết nối đến máy chủ. Quý khách vui lòng kiểm tra kết nối mạng.') }}', 'text', null, []);
        } finally {
            if (sendBtn) sendBtn.disabled = false;
            if (input) input.focus();
        }
    }

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const text = input.value.trim();
            if (text) {
                handleUserQuery(text, 'chat');
            }
        });
    }

    // Attach click events to initial quick chips
    document.querySelectorAll('.quick-chip').forEach(chip => {
        chip.addEventListener('click', function () {
            const action = this.getAttribute('data-action');
            const text = this.textContent.trim();
            handleUserQuery(text, action);
        });
    });
});
</script>
