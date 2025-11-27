<?php

namespace App\Services\Statistics;

use App\Entities\PromotionPerformanceStatsEntity;
use App\Exceptions\NotFoundException;
use App\Repositories\StatisticsRepository;
use App\Services\BaseService;

/**
 * 프로모션 성과 통계 조회 Service
 *
 * GET /api/statistics/promotion-performance?start_date={date}&end_date={date}&promotion_code={code}
 */
class GetPromotionPerformanceStatsService extends BaseService
{
    private StatisticsRepository $statsRepo;

    public function __construct()
    {
        parent::__construct();
        $this->statsRepo = new StatisticsRepository($this->db);
    }

    /**
     * 프로모션 성과 통계 조회 (날짜 범위 + 프로모션 필터)
     *
     * @param array $filters ["start_date", "end_date", "promotion_code"?]
     * @return array
     */
    protected function execute(...$args): array
    {
        $filters = $args[0];

        $startDate = $filters["start_date"];
        $endDate = $filters["end_date"];
        $promotionCode = $filters["promotion_code"] ?? null;

        // Repository에서 날짜 범위 + 프로모션 필터 조회
        $results = $this->statsRepo->findPromotionPerformanceStatsRange($startDate, $endDate, $promotionCode);

        if (empty($results)) {
            throw new NotFoundException("NO_PROMOTION_PERFORMANCE_STATISTICS_DATA");
        }

        // array를 Entity로 변환
        $entities = array_map(fn($row) => new PromotionPerformanceStatsEntity($row), $results);

        // 전체 집계
        $totalUsageCount = array_sum(array_map(fn($e) => $e->usageCount, $entities));
        $totalDiscountAmount = array_sum(array_map(fn($e) => (float) $e->totalDiscountAmount, $entities));
        $totalRevenueContribution = array_sum(array_map(fn($e) => (float) $e->revenueContribution, $entities));

        // Entity를 응답 배열로 변환
        return [
            "data" => array_map(fn(PromotionPerformanceStatsEntity $entity) => [
                "stats_date" => $entity->statsDate,
                "promotion_code" => $entity->promotionCode,
                "usage_count" => $entity->usageCount,
                "total_discount_amount" => $entity->totalDiscountAmount,
                "revenue_contribution" => $entity->revenueContribution,
                "roi" => $entity->roi,
                "avg_discount_per_usage" => $entity->getAvgDiscountPerUsage(), // 헬퍼 메서드
                "is_effective" => $entity->isEffective() // 헬퍼 메서드 (ROI > 100%)
            ], $entities),
            "summary" => [
                "start_date" => $startDate,
                "end_date" => $endDate,
                "promotion_code" => $promotionCode,
                "total_usage_count" => $totalUsageCount,
                "total_discount_amount" => number_format($totalDiscountAmount, 2, ".", ""),
                "total_revenue_contribution" => number_format($totalRevenueContribution, 2, ".", ""),
                "total_records" => count($entities)
            ]
        ];
    }
}
