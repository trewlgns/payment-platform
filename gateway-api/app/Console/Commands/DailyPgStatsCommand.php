<?php

namespace App\Console\Commands;

use App\Database\DB;
use App\Repositories\StatisticsRepository;
use Illuminate\Console\Command;
use PDO;

class DailyPgStatsCommand extends Command
{
    /**
     * 커맨드 시그니처
     *
     * @var string
     */
    protected $signature = "stats:daily-pg {date?}";

    /**
     * 커맨드 설명
     *
     * @var string
     */
    protected $description = "PG별 일별 통계 집계";

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

        $this->info("=== PG별 일별 통계 집계 시작 ===");
        $this->info("대상 날짜: {$targetDate}");

        try {
            $statsList = $this->aggregateDailyPgStats($targetDate);

            if (empty($statsList)) {
                $this->warn("집계 대상 데이터가 없습니다.");
                return Command::SUCCESS;
            }

            $count = 0;
            foreach ($statsList as $stats) {
                $this->statsRepo->upsertDailyPgStats($stats);
                $count++;
            }

            $this->info("✅ 통계 집계 완료: {$count}개 PG");

            $tableData = [];
            foreach ($statsList as $stats) {
                $tableData[] = [
                    $stats["pg_provider_code"],
                    number_format($stats["success_count"]),
                    number_format($stats["fail_count"]),
                    number_format($stats["total_amount"], 2),
                    number_format($stats["avg_response_time_ms"]) . "ms"
                ];
            }

            $this->table(
                ["PG", "성공 건수", "실패 건수", "총 거래액", "평균 응답시간"],
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
     * PG별 통계 집계
     *
     * @param string $targetDate 대상 날짜
     * @return array PG별 통계 배열
     */
    private function aggregateDailyPgStats(string $targetDate): array
    {
        $query = <<<SQL
            WITH payment_summary AS (
                -- 결제 데이터 요약
                SELECT
                    p.`pg_provider_code`,
                    p.`status`,
                    p.`amount`,
                    pt.`response_time_ms`
                FROM
                    `payments` p
                    LEFT JOIN `payment_transactions` pt
                        ON p.`payment_id` = pt.`payment_id`
                WHERE
                    DATE(p.`created_at`) = :target_date
            ),
            pg_aggregated AS (
                -- PG별 집계
                SELECT
                    :stats_date AS `stats_date`,
                    `pg_provider_code`,
                    SUM(CASE WHEN `status` = 'approved' THEN 1 ELSE 0 END) AS `success_count`,
                    SUM(CASE WHEN `status` = 'failed' THEN 1 ELSE 0 END) AS `fail_count`,
                    COALESCE(
                        SUM(CASE WHEN `status` = 'approved' THEN `amount` ELSE 0 END),
                        0
                    ) AS `total_amount`,
                    COALESCE(
                        AVG(`response_time_ms`),
                        0
                    ) AS `avg_response_time_ms`
                FROM
                    payment_summary
                GROUP BY
                    `pg_provider_code`
                HAVING
                    COUNT(*) > 0
            )
            SELECT
                `stats_date`,
                `pg_provider_code`,
                `success_count`,
                `fail_count`,
                `total_amount`,
                CAST(`avg_response_time_ms` AS UNSIGNED) AS `avg_response_time_ms`
            FROM
                pg_aggregated
            ORDER BY
                `total_amount` DESC
        SQL;

        return $this->db->select($query, [
            "target_date"   => ["value" => $targetDate, "type" => PDO::PARAM_STR],
            "stats_date"    => ["value" => $targetDate, "type" => PDO::PARAM_STR]
        ]);
    }
}
