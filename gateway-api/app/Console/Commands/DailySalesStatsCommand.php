<?php

namespace App\Console\Commands;

use App\Database\DB;
use App\Repositories\StatisticsRepository;
use Illuminate\Console\Command;
use PDO;

class DailySalesStatsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "stats:daily-sales {date?}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "일별 매출 통계 집계 (Window Function 활용)";

    private DB $db;
    private StatisticsRepository $statsRepo;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // DB 및 Repository 초기화
        $this->db = new DB();
        $this->statsRepo = new StatisticsRepository($this->db);

        // 집계 대상 날짜 (인자로 받거나 어제 날짜)
        $targetDate = $this->argument("date") ?? date("Y-m-d", strtotime("-1 day"));

        $this->info("=== 일별 매출 통계 집계 시작 ===");
        $this->info("대상 날짜: {$targetDate}");

        try {
            // 1. Window Function을 활용한 일별 매출 통계 집계
            $stats = $this->aggregateDailySales($targetDate);

            if (empty($stats)) {
                $this->warn("집계 대상 데이터가 없습니다.");
                return Command::SUCCESS;
            }

            // 2. 통계 테이블에 UPSERT
            $this->statsRepo->upsertDailySales($stats);

            $this->info("✅ 통계 집계 완료");
            $this->table(
                ["항목", "값"],
                [
                    ["총 주문 수", number_format($stats["total_orders"])],
                    ["총 매출액", number_format($stats["total_amount"], 2)],
                    ["총 할인액", number_format($stats["total_discount"], 2)],
                    ["순매출액", number_format($stats["net_amount"], 2)],
                    ["평균 주문 금액", number_format($stats["avg_order_value"], 2)]
                ]
            );

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ 통계 집계 실패: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    /**
     * Window Function을 활용한 일별 매출 통계 집계
     *
     * Window Function 활용 예시:
     * - SUM() OVER(): 누적 매출 계산
     * - LAG(): 전일 매출 조회
     * - LEAD(): 익일 매출 조회 (향후 확장 가능)
     * - ROW_NUMBER(): 순위 계산
     *
     * @param string $targetDate
     * @return array
     */
    private function aggregateDailySales(string $targetDate): array
    {
        $query = <<<SQL
            WITH daily_order_summary AS (
                -- 일별 주문 집계 (기본 데이터)
                SELECT
                    DATE(`ordered_at`) AS `order_date`,
                    COUNT(*) AS `total_orders`,
                    SUM(`total_amount`) AS `total_amount`,
                    SUM(`discount_amount`) AS `total_discount`,
                    SUM(`final_amount`) AS `net_amount`,
                    AVG(`final_amount`) AS `avg_order_value`
                FROM
                    `orders`
                WHERE
                    `status` IN ('paid', 'confirmed', 'preparing', 'shipped', 'delivered')
                    AND DATE(`ordered_at`) = :target_date
                GROUP BY
                    DATE(`ordered_at`)
            ),
            daily_with_window AS (
                -- Window Function 활용: 누적 매출, 전일 대비 등
                SELECT
                    `order_date`,
                    `total_orders`,
                    `total_amount`,
                    `total_discount`,
                    `net_amount`,
                    `avg_order_value`,
                    -- Window Function 1: 누적 매출 (현재까지의 총 매출)
                    SUM(`net_amount`) OVER (
                        ORDER BY `order_date`
                        ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
                    ) AS `cumulative_sales`,
                    -- Window Function 2: 전일 매출 조회 (LAG)
                    LAG(`net_amount`, 1) OVER (
                        ORDER BY `order_date`
                    ) AS `previous_day_sales`,
                    -- Window Function 3: 7일 이동 평균 (ROLLING AVERAGE)
                    AVG(`net_amount`) OVER (
                        ORDER BY `order_date`
                        ROWS BETWEEN 6 PRECEDING AND CURRENT ROW
                    ) AS `moving_avg_7days`,
                    -- Window Function 4: 순위 계산 (ROW_NUMBER)
                    ROW_NUMBER() OVER (
                        ORDER BY `net_amount` DESC
                    ) AS `sales_rank`
                FROM
                    daily_order_summary
            )
            SELECT
                :stats_date AS `stats_date`,
                COALESCE(`total_orders`, 0) AS `total_orders`,
                COALESCE(`total_amount`, 0) AS `total_amount`,
                COALESCE(`total_discount`, 0) AS `total_discount`,
                COALESCE(`net_amount`, 0) AS `net_amount`,
                COALESCE(`avg_order_value`, 0) AS `avg_order_value`
            FROM
                daily_with_window
            WHERE
                `order_date` = :target_date
            LIMIT 1
        SQL;

        $result = $this->db->selectOne($query, [
            "target_date"   => ["value" => $targetDate, "type" => PDO::PARAM_STR],
            "stats_date"    => ["value" => $targetDate, "type" => PDO::PARAM_STR]
        ]);

        // 결과가 없으면 빈 배열 반환
        if (!$result) {
            return [];
        }

        return $result;
    }
}
