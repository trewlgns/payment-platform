<?php

namespace App\Services\Statistics;

use App\Entities\DailyPgStatsEntity;
use App\Exceptions\NotFoundException;
use App\Repositories\StatisticsRepository;
use App\Services\BaseService;

/**
 * 일별 PG 통계 조회 Service
 *
 * GET /api/statistics/daily-pg?start_date={date}&end_date={date}&pg_provider_code={code}
 */
class GetDailyPgStatsService extends BaseService
{
    private StatisticsRepository $statsRepo;

    public function __construct()
    {
        parent::__construct();
        $this->statsRepo = new StatisticsRepository($this->db);
    }

    /**
     * 일별 PG 통계 조회 (날짜 범위 + PG 필터)
     *
     * @param array $filters ["start_date", "end_date", "pg_provider_code"?]
     * @return array
     */
    protected function execute(...$args): array
    {
        $filters = $args[0];

        $startDate = $filters["start_date"];
        $endDate = $filters["end_date"];
        $pgProviderCode = $filters["pg_provider_code"] ?? null;

        // Repository에서 날짜 범위 + PG 필터 조회
        $results = $this->statsRepo->findDailyPgStatsRange($startDate, $endDate, $pgProviderCode);

        if (empty($results)) {
            throw new NotFoundException("NO_PG_STATISTICS_DATA");
        }

        // array를 Entity로 변환
        $entities = array_map(fn($row) => new DailyPgStatsEntity($row), $results);

        // Entity를 응답 배열로 변환
        return [
            "data" => array_map(fn(DailyPgStatsEntity $entity) => [
                "stats_date" => $entity->statsDate,
                "pg_provider_code" => $entity->pgProviderCode,
                "success_count" => $entity->successCount,
                "fail_count" => $entity->failCount,
                "total_count" => $entity->getTotalCount(), // 헬퍼 메서드
                "total_amount" => $entity->totalAmount,
                "avg_response_time_ms" => $entity->avgResponseTimeMs,
                "success_rate" => $entity->getSuccessRate(), // 헬퍼 메서드
                "fail_rate" => $entity->getFailRate(), // 헬퍼 메서드
                "created_at" => $entity->createdAt
            ], $entities),
            "summary" => [
                "start_date" => $startDate,
                "end_date" => $endDate,
                "pg_provider_code" => $pgProviderCode,
                "total_records" => count($entities)
            ]
        ];
    }
}
