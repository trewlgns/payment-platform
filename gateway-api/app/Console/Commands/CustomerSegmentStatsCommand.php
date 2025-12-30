<?php

namespace App\Console\Commands;

use App\Database\DB;
use App\Repositories\StatisticsRepository;
use Illuminate\Console\Command;
use PDO;

class CustomerSegmentStatsCommand extends Command
{
    /**
     * 커맨드 시그니처
     *
     * @var string
     */
    protected $signature = "stats:customer-segment {date?}";

    /**
     * 커맨드 설명
     *
     * @var string
     */
    protected $description = "고객 세그먼트 통계 집계";

    private DB $db;
    private StatisticsRepository $statsRepo;

    /**
     * 커맨드 실행
     */
    public function handle(): int
    {
        $this->db = new DB();
        $this->statsRepo = new StatisticsRepository($this->db);

        $targetDate = $this->argument("date") ?? date("Y-m-d", strtotime("-1 day"));

        $this->info("=== 고객 세그먼트 통계 집계 시작 ===");
        $this->info("대상 날짜: {$targetDate}");

        try {
            $statsList = $this->aggregateCustomerSegmentStats($targetDate);

            if (empty($statsList)) {
                $this->warn("집계 대상 데이터가 없습니다.");
                return Command::SUCCESS;
            }

            $count = 0;
            foreach ($statsList as $stats) {
                $this->statsRepo->upsertCustomerSegmentStats($stats);
                $count++;
            }

            $this->info("✅ 통계 집계 완료: {$count}개 세그먼트");

            $tableData = [];
            foreach ($statsList as $stats) {
                $tableData[] = [
                    $stats["segment"],
                    number_format($stats["customer_count"]),
                    number_format($stats["total_orders"]),
                    number_format($stats["total_amount"], 2),
                    number_format($stats["repeat_rate"], 2) . "%"
                ];
            }

            $this->table(
                ["세그먼트", "고객 수", "주문 수", "총 매출액", "재구매율"],
                $tableData
            );

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ 통계 집계 실패: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    /**
     * 고객 세그먼트 통계 집계
     *
     * @param string $targetDate 대상 날짜
     * @return array 세그먼트별 통계 배열
     */
    private function aggregateCustomerSegmentStats(string $targetDate): array
    {
        $query = <<<SQL
            WITH customer_orders AS (
                -- 고객별 주문 데이터
                SELECT
                    o.`customer_email`,
                    COUNT(DISTINCT o.`order_id`) AS `order_count`,
                    SUM(o.`final_amount`) AS `total_amount`,
                    MAX(o.`ordered_at`) AS `last_order_date`,
                    MIN(o.`ordered_at`) AS `first_order_date`,
                    DATEDIFF(:target_date, MAX(o.`ordered_at`)) AS `recency_days`
                FROM
                    `orders` o
                WHERE
                    o.`status` IN ('paid', 'confirmed', 'preparing', 'shipped', 'delivered')
                    AND DATE(o.`ordered_at`) <= :target_date
                GROUP BY
                    o.`customer_email`
            ),
            customer_segments AS (
                -- RFM 분석 기반 고객 세그먼트 분류
                SELECT
                    `customer_email`,
                    `order_count`,
                    `total_amount`,
                    `recency_days`,
                    CASE
                        -- VIP: 최근 30일 이내 구매 + 3회 이상 주문 + 10만원 이상 구매
                        WHEN `recency_days` <= 30 AND `order_count` >= 3 AND `total_amount` >= 100000 THEN 'VIP'
                        -- 일반: 최근 90일 이내 구매 + 2회 이상 주문
                        WHEN `recency_days` <= 90 AND `order_count` >= 2 THEN '일반'
                        -- 신규: 주문 1회
                        WHEN `order_count` = 1 THEN '신규'
                        -- 휴면: 90일 초과 미구매
                        ELSE '휴면'
                    END AS `segment`,
                    -- 재구매 여부 (2회 이상 주문)
                    CASE WHEN `order_count` >= 2 THEN 1 ELSE 0 END AS `is_repeat_customer`
                FROM
                    customer_orders
            ),
            segment_aggregated AS (
                -- 세그먼트별 집계
                SELECT
                    :stats_date AS `stats_date`,
                    `segment`,
                    COUNT(DISTINCT `customer_email`) AS `customer_count`,
                    SUM(`order_count`) AS `total_orders`,
                    SUM(`total_amount`) AS `total_amount`,
                    -- 재구매율 계산 (재구매 고객 수 / 전체 고객 수 * 100)
                    ROUND(
                        SUM(`is_repeat_customer`) * 100.0 / COUNT(DISTINCT `customer_email`),
                        2
                    ) AS `repeat_rate`
                FROM
                    customer_segments
                GROUP BY
                    `segment`
            )
            SELECT
                `stats_date`,
                `segment`,
                `customer_count`,
                `total_orders`,
                `total_amount`,
                `repeat_rate`
            FROM
                segment_aggregated
            ORDER BY
                FIELD(`segment`, 'VIP', '일반', '신규', '휴면')
        SQL;

        return $this->db->select($query, [
            "target_date"   => ["value" => $targetDate, "type" => PDO::PARAM_STR],
            "stats_date"    => ["value" => $targetDate, "type" => PDO::PARAM_STR]
        ]);
    }
}
