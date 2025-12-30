<?php

namespace App\Console\Commands;

use App\Database\DB;
use App\Repositories\StatisticsRepository;
use Illuminate\Console\Command;
use PDO;

class PromotionPerformanceStatsCommand extends Command
{
    /**
     * 커맨드 시그니처
     *
     * @var string
     */
    protected $signature = "stats:promotion-performance {date?}";

    /**
     * 커맨드 설명
     *
     * @var string
     */
    protected $description = "프로모션 성과 통계 집계";

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

        $this->info("=== 프로모션 성과 통계 집계 시작 ===");
        $this->info("대상 날짜: {$targetDate}");

        try {
            $statsList = $this->aggregatePromotionPerformanceStats($targetDate);

            if (empty($statsList)) {
                $this->warn("집계 대상 데이터가 없습니다.");
                return Command::SUCCESS;
            }

            $count = 0;
            foreach ($statsList as $stats) {
                $this->statsRepo->upsertPromotionPerformanceStats($stats);
                $count++;
            }

            $this->info("✅ 통계 집계 완료: {$count}개 프로모션");

            $tableData = [];
            foreach ($statsList as $stats) {
                $tableData[] = [
                    $stats["promotion_code"],
                    number_format($stats["usage_count"]),
                    number_format($stats["total_discount_amount"], 2),
                    number_format($stats["revenue_contribution"], 2),
                    number_format($stats["roi"], 2) . "%"
                ];
            }

            $this->table(
                ["프로모션 코드", "사용 건수", "총 할인액", "매출 기여도", "ROI"],
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
     * 프로모션 성과 통계 집계
     *
     * @param string $targetDate 대상 날짜
     * @return array 프로모션별 통계 배열
     */
    private function aggregatePromotionPerformanceStats(string $targetDate): array
    {
        $query = <<<SQL
            WITH promotion_orders AS (
                -- 프로모션이 적용된 주문 데이터
                SELECT
                    op.`promotion_code`,
                    op.`discount_amount`,
                    o.`final_amount`,
                    o.`order_id`,
                    o.`ordered_at`
                FROM
                    `order_promotions` op
                    INNER JOIN `orders` o
                        ON op.`order_id` = o.`order_id`
                WHERE
                    DATE(op.`applied_at`) = :target_date
                    AND o.`status` IN ('paid', 'confirmed', 'preparing', 'shipped', 'delivered')
            ),
            promotion_aggregated AS (
                -- 프로모션별 집계
                SELECT
                    :stats_date AS `stats_date`,
                    `promotion_code`,
                    COUNT(DISTINCT `order_id`) AS `usage_count`,
                    SUM(`discount_amount`) AS `total_discount_amount`,
                    SUM(`final_amount`) AS `revenue_contribution`,
                    -- ROI 계산: (매출 기여도 / 총 할인액) * 100
                    -- 할인액 대비 얼마나 매출을 발생시켰는지
                    CASE
                        WHEN SUM(`discount_amount`) > 0 THEN
                            ROUND((SUM(`final_amount`) / SUM(`discount_amount`)) * 100, 2)
                        ELSE 0
                    END AS `roi`
                FROM
                    promotion_orders
                GROUP BY
                    `promotion_code`
            ),
            promotion_with_details AS (
                -- 프로모션 상세 정보 조인 (서브쿼리 활용)
                SELECT
                    pa.*,
                    p.`name` AS `promotion_name`,
                    p.`promotion_type`,
                    -- 평균 할인액
                    ROUND(pa.`total_discount_amount` / pa.`usage_count`, 2) AS `avg_discount`,
                    -- 평균 주문 금액
                    ROUND(pa.`revenue_contribution` / pa.`usage_count`, 2) AS `avg_order_amount`
                FROM
                    promotion_aggregated pa
                    LEFT JOIN `promotions` p
                        ON pa.`promotion_code` = p.`promotion_code`
            )
            SELECT
                `stats_date`,
                `promotion_code`,
                `usage_count`,
                `total_discount_amount`,
                `revenue_contribution`,
                `roi`
            FROM
                promotion_with_details
            ORDER BY
                `revenue_contribution` DESC
        SQL;

        return $this->db->select($query, [
            "target_date"   => ["value" => $targetDate, "type" => PDO::PARAM_STR],
            "stats_date"    => ["value" => $targetDate, "type" => PDO::PARAM_STR]
        ]);
    }
}
