<?php

namespace App\Entities;

/**
 * PromotionPerformanceStats Entity - 프로모션 성과 통계 데이터 객체
 *
 * 프로모션별 성과 지표(사용 건수, 할인액, ROI)를 표현하는 Entity.
 */
class PromotionPerformanceStatsEntity
{
    /**
     * 통계 날짜 (COMPOSITE PK)
     */
    public string $statsDate;

    /**
     * 프로모션 코드 (COMPOSITE PK)
     */
    public string $promotionCode;

    /**
     * 사용 건수
     */
    public int $usageCount;

    /**
     * 총 할인액
     */
    public string $totalDiscountAmount;

    /**
     * 매출 기여도
     */
    public string $revenueContribution;

    /**
     * 투자 대비 효과 (ROI)
     */
    public string $roi;

    /**
     * 생성일시
     */
    public string $createdAt;

    /**
     * 생성자 - 배열 데이터로부터 Entity 생성
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->statsDate = $data["stats_date"];
        $this->promotionCode = $data["promotion_code"];
        $this->usageCount = (int) $data["usage_count"];
        $this->totalDiscountAmount = $data["total_discount_amount"];
        $this->revenueContribution = $data["revenue_contribution"];
        $this->roi = $data["roi"];
        $this->createdAt = $data["created_at"];
    }

    // ========================================
    // 비즈니스 로직 헬퍼 메서드
    // ========================================

    /**
     * 건당 평균 할인액 계산
     *
     * @return float
     */
    public function getAvgDiscountPerUsage(): float
    {
        if ($this->usageCount === 0) {
            return 0.0;
        }

        return round((float) $this->totalDiscountAmount / $this->usageCount, 2);
    }

    /**
     * ROI가 양수인지 확인 (수익성 있음)
     *
     * @return bool
     */
    public function hasPositiveRoi(): bool
    {
        return (float) $this->roi > 0;
    }

    /**
     * ROI가 음수인지 확인 (손실)
     *
     * @return bool
     */
    public function hasNegativeRoi(): bool
    {
        return (float) $this->roi < 0;
    }

    /**
     * 사용 건수가 많은지 확인 (100건 이상)
     *
     * @return bool
     */
    public function isHighUsage(): bool
    {
        return $this->usageCount >= 100;
    }

    /**
     * 사용 건수가 적은지 확인 (10건 미만)
     *
     * @return bool
     */
    public function isLowUsage(): bool
    {
        return $this->usageCount < 10;
    }

    /**
     * 매출 기여도가 높은지 확인 (1000 이상)
     *
     * @return bool
     */
    public function hasHighRevenueContribution(): bool
    {
        return (float) $this->revenueContribution >= 1000.0;
    }

    /**
     * 프로모션이 효과적인지 확인 (ROI > 100%)
     *
     * @return bool
     */
    public function isEffective(): bool
    {
        return (float) $this->roi > 100.0;
    }
}
