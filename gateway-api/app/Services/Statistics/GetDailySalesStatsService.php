<?php

namespace App\Services\Statistics;

use App\Entities\DailySalesStatsEntity;
use App\Exceptions\NotFoundException;
use App\Repositories\StatisticsRepository;
use App\Services\BaseService;

/**
 * 일별 매출 통계 조회 Service
 *
 * GET /api/statistics/daily-sales?start_date={date}&end_date={date}
 */
class GetDailySalesStatsService extends BaseService
{
    private StatisticsRepository $statsRepo;

    public function __construct()
    {
        parent::__construct();
        $this->statsRepo = new StatisticsRepository($this->db);
    }

    /**
     * 일별 매출 통계 조회 (날짜 범위)
     *
     * @param array $filters ["start_date", "end_date"]
     * @return array
     */
    protected function execute(...$args): array
    {
        $filters = $args[0];

        $startDate = $filters["start_date"];
        $endDate = $filters["end_date"];

        // Repository에서 날짜 범위 조회
        $results = $this->statsRepo->findDailySalesRange($startDate, $endDate);

        if (empty($results)) {
            throw new NotFoundException("NO_STATISTICS_DATA");
        }

        // array를 Entity로 변환
        $entities = array_map(fn($row) => new DailySalesStatsEntity($row), $results);

        // Entity를 응답 배열로 변환
        return [
            "data" => array_map(fn(DailySalesStatsEntity $entity) => [
                "stats_date" => $entity->statsDate,
                "total_orders" => $entity->totalOrders,
                "total_amount" => $entity->totalAmount,
                "total_discount" => $entity->totalDiscount,
                "net_amount" => $entity->netAmount,
                "avg_order_value" => $entity->avgOrderValue,
                "discount_rate" => $entity->getDiscountRate(), // 헬퍼 메서드 활용
                "created_at" => $entity->createdAt,
                "updated_at" => $entity->updatedAt
            ], $entities),
            "summary" => [
                "start_date" => $startDate,
                "end_date" => $endDate,
                "total_days" => count($entities)
            ]
        ];
    }
}
