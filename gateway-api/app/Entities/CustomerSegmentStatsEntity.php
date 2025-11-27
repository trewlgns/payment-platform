<?php

namespace App\Entities;

/**
 * CustomerSegmentStats Entity - 고객 세그먼트 통계 데이터 객체
 *
 * 고객 세그먼트별(신규, 일반, VIP) 통계 데이터를 표현하는 Entity.
 */
class CustomerSegmentStatsEntity
{
    /**
     * 통계 날짜 (COMPOSITE PK)
     */
    public string $statsDate;

    /**
     * 고객 세그먼트 (COMPOSITE PK: new|regular|vip)
     */
    public string $segment;

    /**
     * 고객 수
     */
    public int $customerCount;

    /**
     * 총 주문 수
     */
    public int $totalOrders;

    /**
     * 총 매출액
     */
    public string $totalAmount;

    /**
     * 재구매율 (%)
     */
    public string $repeatRate;

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
        $this->segment = $data["segment"];
        $this->customerCount = (int) $data["customer_count"];
        $this->totalOrders = (int) $data["total_orders"];
        $this->totalAmount = $data["total_amount"];
        $this->repeatRate = $data["repeat_rate"];
        $this->createdAt = $data["created_at"];
    }

    // ========================================
    // 세그먼트 헬퍼 메서드
    // ========================================

    /**
     * 신규 고객 세그먼트인지 확인
     *
     * @return bool
     */
    public function isNewSegment(): bool
    {
        return $this->segment === "new";
    }

    /**
     * 일반 고객 세그먼트인지 확인
     *
     * @return bool
     */
    public function isRegularSegment(): bool
    {
        return $this->segment === "regular";
    }

    /**
     * VIP 고객 세그먼트인지 확인
     *
     * @return bool
     */
    public function isVipSegment(): bool
    {
        return $this->segment === "vip";
    }

    // ========================================
    // 비즈니스 로직 헬퍼 메서드
    // ========================================

    /**
     * 고객당 평균 주문 수 계산
     *
     * @return float
     */
    public function getAvgOrdersPerCustomer(): float
    {
        if ($this->customerCount === 0) {
            return 0.0;
        }

        return round($this->totalOrders / $this->customerCount, 2);
    }

    /**
     * 고객당 평균 매출 계산
     *
     * @return float
     */
    public function getAvgAmountPerCustomer(): float
    {
        if ($this->customerCount === 0) {
            return 0.0;
        }

        return round((float) $this->totalAmount / $this->customerCount, 2);
    }

    /**
     * 주문당 평균 금액 계산
     *
     * @return float
     */
    public function getAvgAmountPerOrder(): float
    {
        if ($this->totalOrders === 0) {
            return 0.0;
        }

        return round((float) $this->totalAmount / $this->totalOrders, 2);
    }

    /**
     * 재구매율이 높은지 확인 (50% 이상)
     *
     * @return bool
     */
    public function hasHighRepeatRate(): bool
    {
        return (float) $this->repeatRate >= 50.0;
    }

    /**
     * 고객이 있는지 확인
     *
     * @return bool
     */
    public function hasCustomers(): bool
    {
        return $this->customerCount > 0;
    }
}
