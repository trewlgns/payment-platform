<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     * Accept-Language 헤더를 확인하여 언어 설정 (기본값: ko)
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $defaultLocale = config("app.locale", "ko");

        // Accept-Language 헤더에서 언어 추출 (없으면 기본값 사용)
        $locale = $request->header("Accept-Language", $defaultLocale);

        // 쉼표로 구분된 경우 첫 번째 언어만 사용 (예: "ko,en;q=0.9" -> "ko")
        if (strpos($locale, ',') !== false) {
            $locale = explode(',', $locale)[0];
        }

        // 세미콜론 제거 (예: "ko;q=0.9" -> "ko")
        if (strpos($locale, ';') !== false) {
            $locale = explode(';', $locale)[0];
        }

        // 공백 제거 및 소문자 변환
        $locale = trim(strtolower($locale));

        // en-US, en_US 와 같이 하위 로케일이 붙은 경우 기본 언어만 사용
        $normalizedLocale = str_replace("_", "-", $locale);
        if (str_contains($normalizedLocale, "-")) {
            $normalizedLocale = explode("-", $normalizedLocale)[0];
        }

        // 지원하는 언어 목록
        $supportedLocales = ["ko", "en"];

        // 지원하지 않는 언어는 기본값 'ko' 사용
        if (!in_array($locale, $supportedLocales, true)) {
            $locale = in_array($normalizedLocale, $supportedLocales, true)
                ? $normalizedLocale
                : $defaultLocale;
        }

        // Laravel 언어 설정
        app()->setLocale($locale);

        return $next($request);
    }
}
