<?php

namespace App\Entities;

/**
 * Coupon Entity - 쿠폰 도메인 데이터 객체
 *
 * Repository에서 조회한 배열 데이터를 받아 순수 데이터 + 헬퍼 메서드만 제공한다.
 * Service/Validator 레이어가 상태를 직관적으로 판별할 수 있도록 boolean 헬퍼를 포함한다.
 */
class CouponEntity
{
    /**
     * 쿠폰 코드 (PRIMARY KEY)
     */
    public string $couponCode;

    /**
     * 프로모션 코드 (FOREIGN KEY)
     */
    public string $promotionCode;

    /**
     * 발급 수량
     */
    public int $issuedCount;

    /**
     * 최대 사용 횟수
     */
    public int $maxUsage;

    /**
     * 만료일시
     */
    public string $expiresAt;

    /**
     * 쿠폰 상태 (issued|used|expired|cancelled)
     */
    public string $status;

    /**
     * 생성일시
     */
    public string $createdAt;

    /**
     * 수정일시
     */
    public string $updatedAt;

    /**
     * 생성자 - 배열 데이터로부터 Entity 생성
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->couponCode = $data["coupon_code"];
        $this->promotionCode = $data["promotion_code"];
        $this->issuedCount = (int) $data["issued_count"];
        $this->maxUsage = (int) $data["max_usage"];
        $this->expiresAt = $data["expires_at"];
        $this->status = $data["status"];
        $this->createdAt = $data["created_at"];
        $this->updatedAt = $data["updated_at"];
    }

    // ========================================
    // 상태 헬퍼
    // ========================================

    /**
     * 발급 상태 (아직 사용 안 함)
     */
    public function isIssued(): bool
    {
        return $this->status === "issued";
    }

    /**
     * 사용 완료 상태
     */
    public function isUsed(): bool
    {
        return $this->status === "used";
    }

    /**
     * 만료 상태
     */
    public function isExpired(): bool
    {
        return $this->status === "expired";
    }

    /**
     * 취소 상태
     */
    public function isCancelled(): bool
    {
        return $this->status === "cancelled";
    }

    // ========================================
    // 만료 시각 헬퍼
    // ========================================

    /**
     * 현재 시각 기준 만료 여부 (시각 비교)
     */
    public function isExpiredByTime(): bool
    {
        $now = date("Y-m-d H:i:s");
        return $this->expiresAt < $now;
    }

    /**
     * 아직 만료되지 않은 상태 (시각 비교)
     */
    public function isNotExpiredByTime(): bool
    {
        return !$this->isExpiredByTime();
    }

    // ========================================
    // 사용 가능 여부 헬퍼
    // ========================================

    /**
     * 사용 가능한 쿠폰 여부 (발급 상태 + 만료 안 됨)
     */
    public function isUsable(): bool
    {
        return $this->isIssued() && $this->isNotExpiredByTime();
    }

    /**
     * 사용 불가능한 쿠폰 여부
     */
    public function isNotUsable(): bool
    {
        return !$this->isUsable();
    }

    // ========================================
    // 사용 횟수 헬퍼
    // ========================================

    /**
     * 사용 횟수 도달 여부
     */
    public function hasReachedMaxUsage(): bool
    {
        return $this->issuedCount >= $this->maxUsage;
    }

    /**
     * 추가 사용 가능 여부
     */
    public function canBeUsedAgain(): bool
    {
        return $this->issuedCount < $this->maxUsage;
    }
}
