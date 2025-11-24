<?php

namespace App\Entities;

/**
 * Promotion Entity - 프로모션 도메인 데이터 객체
 *
 * Repository에서 조회한 배열 데이터를 받아 순수 데이터 + 헬퍼 메서드만 제공한다.
 * Service/Validator 레이어가 상태를 직관적으로 판별할 수 있도록 boolean 헬퍼를 포함한다.
 */
class PromotionEntity
{
    /**
     * 프로모션 코드 (PRIMARY KEY)
     */
    public string $promotionCode;

    /**
     * 프로모션 명칭
     */
    public string $name;

    /**
     * 프로모션 유형 (cart_discount|product_discount|shipping_discount|coupon)
     */
    public string $promotionType;

    /**
     * 할인 유형 (percentage|fixed_amount)
     */
    public string $discountType;

    /**
     * 할인값 (%, 금액)
     */
    public string $discountValue;

    /**
     * 시작일시
     */
    public string $startAt;

    /**
     * 종료일시
     */
    public string $endAt;

    /**
     * 활성화 여부 (0|1)
     */
    public int $isActive;

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
        $this->promotionCode = $data["promotion_code"];
        $this->name = $data["name"];
        $this->promotionType = $data["promotion_type"];
        $this->discountType = $data["discount_type"];
        $this->discountValue = $data["discount_value"];
        $this->startAt = $data["start_at"];
        $this->endAt = $data["end_at"];
        $this->isActive = (int) $data["is_active"];
        $this->createdAt = $data["created_at"];
        $this->updatedAt = $data["updated_at"];
    }

    // ========================================
    // 상태 헬퍼
    // ========================================

    /**
     * 활성화 여부
     */
    public function isActive(): bool
    {
        return $this->isActive === 1;
    }

    /**
     * 비활성화 여부
     */
    public function isInactive(): bool
    {
        return $this->isActive === 0;
    }

    /**
     * 진행 중인 프로모션 여부 (현재 시각 기준)
     */
    public function isOngoing(): bool
    {
        $now = date("Y-m-d H:i:s");
        return $this->startAt <= $now && $now <= $this->endAt;
    }

    /**
     * 예정된 프로모션 여부 (아직 시작 안 함)
     */
    public function isScheduled(): bool
    {
        $now = date("Y-m-d H:i:s");
        return $this->startAt > $now;
    }

    /**
     * 종료된 프로모션 여부
     */
    public function isExpired(): bool
    {
        $now = date("Y-m-d H:i:s");
        return $this->endAt < $now;
    }

    // ========================================
    // 프로모션 유형 헬퍼
    // ========================================

    /**
     * 장바구니 할인 프로모션 여부
     */
    public function isCartDiscount(): bool
    {
        return $this->promotionType === "cart_discount";
    }

    /**
     * 상품 할인 프로모션 여부
     */
    public function isProductDiscount(): bool
    {
        return $this->promotionType === "product_discount";
    }

    /**
     * 배송비 할인 프로모션 여부
     */
    public function isShippingDiscount(): bool
    {
        return $this->promotionType === "shipping_discount";
    }

    /**
     * 쿠폰 프로모션 여부
     */
    public function isCouponPromotion(): bool
    {
        return $this->promotionType === "coupon";
    }

    // ========================================
    // 할인 유형 헬퍼
    // ========================================

    /**
     * 퍼센트 할인 여부
     */
    public function isPercentageDiscount(): bool
    {
        return $this->discountType === "percentage";
    }

    /**
     * 고정 금액 할인 여부
     */
    public function isFixedAmountDiscount(): bool
    {
        return $this->discountType === "fixed_amount";
    }

    // ========================================
    // 비즈니스 헬퍼
    // ========================================

    /**
     * 사용 가능한 프로모션 여부 (활성화 + 진행 중)
     */
    public function isAvailable(): bool
    {
        return $this->isActive() && $this->isOngoing();
    }
}
