<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

/**
 * Thiết lập Content-Security-Policy (CSP) và các security header bổ trợ cho phản hồi HTTP.
 *
 * - Mỗi request sinh một nonce ngẫu nhiên qua Vite::useCspNonce(); biến $cspNonce được chia sẻ
 *   cho mọi Blade view để gắn vào các thẻ <script nonce="{{ $cspNonce }}"> hợp lệ.
 * - script-src dùng nonce (không dùng 'unsafe-inline' hay 'unsafe-eval'), nhờ đó chặn thực thi
 *   mọi inline script hoặc inline event handler (onclick, onerror...) bị chèn qua XSS.
 */
class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $nonce = Vite::useCspNonce();
        view()->share('cspNonce', $nonce);

        /** @var Response $response */
        $response = $next($request);

        if (! $response->headers->has('Content-Security-Policy')) {
            $response->headers->set('Content-Security-Policy', $this->buildPolicy($nonce));
        }

        $response->headers->set('X-Content-Type-Options', 'nosniff', false);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN', false);
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin', false);

        return $response;
    }

    private function buildPolicy(string $nonce): string
    {
        $directives = [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
            "object-src 'none'",
            "script-src 'self' 'nonce-{$nonce}'",
            "script-src-attr 'none'",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net https://cdn.jsdelivr.net",
            "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net https://cdn.jsdelivr.net",
            "img-src 'self' data: blob: https:",
            "connect-src 'self'",
        ];

        return implode('; ', $directives);
    }
}
