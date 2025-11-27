<?php

namespace App\Entities;

/**
 * DailyPgStats Entity - 일별 PG 통계 데이터 객체
 *
 * PG 제공자별 일별 성공/실패 통계 데이터를 표현하는 Entity.
 */
class DailyPgStatsEntity
{
    /**
     * 통계 날짜 (COMPOSITE PK)
     */
    public string $statsDate;

    /**
     * PG 제공자 코드 (COMPOSITE PK)
     */
    public string $pgProviderCode;

    /**
     * 성공 건수
     */
    public int $successCount;

    /**
     * 실패 건수
     */
    public int $failCount;

    /**
     * 총 거래액
     */
    public string $totalAmount;

    /**
     * 평균 응답 시간 (ms)
     */
    public int $avgResponseTimeMs;

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
        $this->pgProviderCode = $data["pg_provider_code"];
        $this->successCount = (int) $data["success_count"];
        $this->failCount = (int) $data["fail_count"];
        $this->totalAmount = $data["total_amount"];
        $this->avgResponseTimeMs = (int) $data["avg_response_time_ms"];
        $this->createdAt = $data["created_at"];
    }

    // ========================================
    // 비즈니스 로직 헬퍼 메서드
    // ========================================

    /**
     * 총 거래 건수 (성공 + 실패)
     *
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->successCount + $this->failCount;
    }

    /**
     * 성공률 계산 (%)
     *
     * @return float
     */
    public function getSuccessRate(): float
    {
        $total = $this->getTotalCount();

        if ($total === 0) {
            return 0.0;
        }

        return round(($this->successCount / $total) * 100, 2);
    }

    /**
     * 실패율 계산 (%)
     *
     * @return float
     */
    public function getFailRate(): float
    {
        $total = $this->getTotalCount();

        if ($total === 0) {
            return 0.0;
        }

        return round(($this->failCount / $total) * 100, 2);
    }

    /**
     * 거래가 있는지 확인
     *
     * @return bool
     */
    public function hasTransactions(): bool
    {
        return $this->getTotalCount() > 0;
    }

    /**
     * 응답 시간이 느린지 확인 (1초 이상)
     *
     * @return bool
     */
    public function isSlowResponse(): bool
    {
        return $this->avgResponseTimeMs >= 1000;
    }

    /**
     * 성공률이 낮은지 확인 (90% 미만)
     *
     * @return bool
     */
    public function hasLowSuccessRate(): bool
    {
        return $this->getSuccessRate() < 90.0;
    }
}
