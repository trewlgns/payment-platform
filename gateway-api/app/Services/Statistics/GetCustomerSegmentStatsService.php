<?php

namespace App\Services\Statistics;

use App\Entities\CustomerSegmentStatsEntity;
use App\Exceptions\NotFoundException;
use App\Repositories\StatisticsRepository;
use App\Services\BaseService;

/**
 * 고객 세그먼트 통계 조회 Service
 *
 * GET /api/statistics/customer-segment?stats_date={date}
 */
class GetCustomerSegmentStatsService extends BaseService
{
    private StatisticsRepository $statsRepo;

    public function __construct()
    {
        parent::__construct();
        $this->statsRepo = new StatisticsRepository($this->db);
    }

    /**
     * 고객 세그먼트 통계 조회 (단일 날짜)
     *
     * @param array $filters ["stats_date"]
     * @return array
     */
    protected function execute(...$args): array
    {
        $filters = $args[0];

        $statsDate = $filters["stats_date"];

        // Repository에서 단일 날짜 조회
        $results = $this->statsRepo->findCustomerSegmentStats($statsDate);

        if (empty($results)) {
            throw new NotFoundException("NO_CUSTOMER_SEGMENT_STATISTICS_DATA");
        }

        // array를 Entity로 변환
        $entities = array_map(fn($row) => new CustomerSegmentStatsEntity($row), $results);

        // 세그먼트별 집계
        $totalCustomers = array_sum(array_map(fn($e) => $e->customerCount, $entities));
        $totalOrders = array_sum(array_map(fn($e) => $e->totalOrders, $entities));
        $totalAmount = array_sum(array_map(fn($e) => (float) $e->totalAmount, $entities));

        // Entity를 응답 배열로 변환
        return [
            "data" => array_map(fn(CustomerSegmentStatsEntity $entity) => [
                "segment" => $entity->segment,
                "customer_count" => $entity->customerCount,
                "total_orders" => $entity->totalOrders,
                "total_amount" => $entity->totalAmount,
                "repeat_rate" => $entity->repeatRate,
                "avg_orders_per_customer" => $entity->getAvgOrdersPerCustomer(), // 헬퍼 메서드
                "avg_amount_per_customer" => $entity->getAvgAmountPerCustomer(), // 헬퍼 메서드
                "avg_amount_per_order" => $entity->getAvgAmountPerOrder() // 헬퍼 메서드
            ], $entities),
            "summary" => [
                "stats_date" => $statsDate,
                "total_customers" => $totalCustomers,
                "total_orders" => $totalOrders,
                "total_amount" => number_format($totalAmount, 2, ".", "")
            ]
        ];
    }
}
