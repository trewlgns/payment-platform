<?php

namespace App\Enums;

/**
 * PG 제공자 코드 Enum
 *
 * 지원하는 PG사 목록을 정의
 */
enum PgProviderCode: string
{
    case MOCK = "MOCK";
    case TOSS = "TOSS";
    case KAKAO = "KAKAO";

    /**
     * 문자열을 Enum으로 변환 (nullable)
     *
     * @param string|null $value
     * @return self|null
     */
    public static function fromString(?string $value): ?self
    {
        if ($value === null) {
            return null;
        }

        return self::tryFrom($value);
    }

    /**
     * 사용자 친화적인 이름 반환
     *
     * @return string
     */
    public function getDisplayName(): string
    {
        return match($this) {
            self::MOCK => "Mock PG (테스트용)",
            self::TOSS => "토스페이먼츠",
            self::KAKAO => "카카오페이",
        };
    }

    /**
     * 모든 PG 코드 목록 반환 (배열)
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
