<?php

namespace App\Entities;

/**
 * DailySalesStats Entity - 일별 매출 통계 데이터 객체
 *
 * 일별 매출 통계 데이터를 표현하는 Entity.
 * Repository에서 조회한 배열 데이터를 순수 데이터 객체로 변환한다.
 */
class DailySalesStatsEntity
{
    /**
     * 통계 날짜 (PRIMARY KEY)
     */
    public string $statsDate;

    /**
     * 총 주문 수
     */
    public int $totalOrders;

    /**
     * 총 매출액
     */
    public string $totalAmount;

    /**
     * 총 할인액
     */
    public string $totalDiscount;

    /**
     * 순매출액 (총 매출 - 총 할인)
     */
    public string $netAmount;

    /**
     * 평균 주문 금액
     */
    public string $avgOrderValue;

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
        $this->statsDate = $data["stats_date"];
        $this->totalOrders = (int) $data["total_orders"];
        $this->totalAmount = $data["total_amount"];
        $this->totalDiscount = $data["total_discount"];
        $this->netAmount = $data["net_amount"];
        $this->avgOrderValue = $data["avg_order_value"];
        $this->createdAt = $data["created_at"];
        $this->updatedAt = $data["updated_at"];
    }

    // ========================================
    // 비즈니스 로직 헬퍼 메서드
    // ========================================

    /**
     * 할인율 계산 (%)
     *
     * @return float
     */
    public function getDiscountRate(): float
    {
        if ($this->totalAmount === "0.00" || $this->totalAmount === "0") {
            return 0.0;
        }

        return round(((float) $this->totalDiscount / (float) $this->totalAmount) * 100, 2);
    }

    /**
     * 주문이 있는지 확인
     *
     * @return bool
     */
    public function hasOrders(): bool
    {
        return $this->totalOrders > 0;
    }

    /**
     * 순매출이 양수인지 확인
     *
     * @return bool
     */
    public function hasPositiveNetAmount(): bool
    {
        return (float) $this->netAmount > 0;
    }
}
