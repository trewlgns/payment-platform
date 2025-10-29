<?php

namespace App\Repositories;

use PDO;

class StatisticsRepository extends BaseRepository
{
    protected string $table = "daily_sales_stats";
    protected string $primaryKey = "stats_date";

    /**
     * 일별 매출 통계 조회 (단일 날짜)
     *
     * @param string $statsDate
     * @return array|null
     */
    public function findDailySales(string $statsDate): ?array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `daily_sales_stats`
            WHERE   `stats_date` = :stats_date
        SQL;

        return $this->db->selectOne($query, [
            "stats_date" => ["value" => $statsDate, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 일별 매출 통계 조회 (날짜 범위)
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function findDailySalesRange(string $startDate, string $endDate): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `daily_sales_stats`
            WHERE       `stats_date` BETWEEN :start_date AND :end_date
            ORDER BY    `stats_date` ASC
        SQL;

        return $this->db->select($query, [
            "start_date"    => ["value" => $startDate, "type" => PDO::PARAM_STR],
            "end_date"      => ["value" => $endDate, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 일별 매출 통계 생성 또는 업데이트 (UPSERT)
     *
     * @param array $data
     * @return int Affected rows
     */
    public function upsertDailySales(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `daily_sales_stats` (
                `stats_date`,
                `total_orders`,
                `total_amount`,
                `total_discount`,
                `net_amount`,
                `avg_order_value`,
                `created_at`,
                `updated_at`
            ) VALUES (
                :stats_date,
                :total_orders,
                :total_amount,
                :total_discount,
                :net_amount,
                :avg_order_value,
                :created_at,
                :updated_at
            )
            ON DUPLICATE KEY UPDATE
                `total_orders` = VALUES(`total_orders`),
                `total_amount` = VALUES(`total_amount`),
                `total_discount` = VALUES(`total_discount`),
                `net_amount` = VALUES(`net_amount`),
                `avg_order_value` = VALUES(`avg_order_value`),
                `updated_at` = VALUES(`updated_at`)
        SQL;

        return $this->db->insert($query, [
            "stats_date"        => ["value" => $data["stats_date"], "type" => PDO::PARAM_STR],
            "total_orders"      => ["value" => $data["total_orders"], "type" => PDO::PARAM_INT],
            "total_amount"      => ["value" => $data["total_amount"], "type" => PDO::PARAM_STR],
            "total_discount"    => ["value" => $data["total_discount"], "type" => PDO::PARAM_STR],
            "net_amount"        => ["value" => $data["net_amount"], "type" => PDO::PARAM_STR],
            "avg_order_value"   => ["value" => $data["avg_order_value"], "type" => PDO::PARAM_STR],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR],
            "updated_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 일별 PG 통계 조회 (단일 날짜 + PG)
     *
     * @param string $statsDate
     * @param string $pgProviderCode
     * @return array|null
     */
    public function findDailyPgStats(string $statsDate, string $pgProviderCode): ?array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `daily_pg_stats`
            WHERE   `stats_date` = :stats_date
              AND   `pg_provider_code` = :pg_provider_code
        SQL;

        return $this->db->selectOne($query, [
            "stats_date"        => ["value" => $statsDate, "type" => PDO::PARAM_STR],
            "pg_provider_code"  => ["value" => $pgProviderCode, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 일별 PG 통계 조회 (날짜 범위)
     *
     * @param string $startDate
     * @param string $endDate
     * @param string|null $pgProviderCode
     * @return array
     */
    public function findDailyPgStatsRange(string $startDate, string $endDate, ?string $pgProviderCode = null): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `daily_pg_stats`
            WHERE       `stats_date` BETWEEN :start_date AND :end_date
              AND       (:pg_provider_code IS NULL OR `pg_provider_code` = :pg_provider_code)
            ORDER BY    `stats_date` ASC, `pg_provider_code` ASC
        SQL;

        return $this->db->select($query, [
            "start_date"        => ["value" => $startDate, "type" => PDO::PARAM_STR],
            "end_date"          => ["value" => $endDate, "type" => PDO::PARAM_STR],
            "pg_provider_code"  => ["value" => $pgProviderCode, "type" => $pgProviderCode === null ? PDO::PARAM_NULL : PDO::PARAM_STR]
        ]);
    }

    /**
     * 일별 PG 통계 생성 또는 업데이트 (UPSERT)
     *
     * @param array $data
     * @return int Affected rows
     */
    public function upsertDailyPgStats(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `daily_pg_stats` (
                `stats_date`,
                `pg_provider_code`,
                `success_count`,
                `fail_count`,
                `total_amount`,
                `avg_response_time_ms`,
                `created_at`
            ) VALUES (
                :stats_date,
                :pg_provider_code,
                :success_count,
                :fail_count,
                :total_amount,
                :avg_response_time_ms,
                :created_at
            )
            ON DUPLICATE KEY UPDATE
                `success_count` = VALUES(`success_count`),
                `fail_count` = VALUES(`fail_count`),
                `total_amount` = VALUES(`total_amount`),
                `avg_response_time_ms` = VALUES(`avg_response_time_ms`)
        SQL;

        return $this->db->insert($query, [
            "stats_date"            => ["value" => $data["stats_date"], "type" => PDO::PARAM_STR],
            "pg_provider_code"      => ["value" => $data["pg_provider_code"], "type" => PDO::PARAM_STR],
            "success_count"         => ["value" => $data["success_count"], "type" => PDO::PARAM_INT],
            "fail_count"            => ["value" => $data["fail_count"], "type" => PDO::PARAM_INT],
            "total_amount"          => ["value" => $data["total_amount"], "type" => PDO::PARAM_STR],
            "avg_response_time_ms"  => ["value" => $data["avg_response_time_ms"], "type" => PDO::PARAM_INT],
            "created_at"            => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 고객 세그먼트 통계 조회 (단일 날짜)
     *
     * @param string $statsDate
     * @return array
     */
    public function findCustomerSegmentStats(string $statsDate): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `customer_segment_stats`
            WHERE       `stats_date` = :stats_date
            ORDER BY    `segment` ASC
        SQL;

        return $this->db->select($query, [
            "stats_date" => ["value" => $statsDate, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 고객 세그먼트 통계 생성 또는 업데이트 (UPSERT)
     *
     * @param array $data
     * @return int Affected rows
     */
    public function upsertCustomerSegmentStats(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `customer_segment_stats` (
                `stats_date`,
                `segment`,
                `customer_count`,
                `total_orders`,
                `total_amount`,
                `repeat_rate`,
                `created_at`
            ) VALUES (
                :stats_date,
                :segment,
                :customer_count,
                :total_orders,
                :total_amount,
                :repeat_rate,
                :created_at
            )
            ON DUPLICATE KEY UPDATE
                `customer_count` = VALUES(`customer_count`),
                `total_orders` = VALUES(`total_orders`),
                `total_amount` = VALUES(`total_amount`),
                `repeat_rate` = VALUES(`repeat_rate`)
        SQL;

        return $this->db->insert($query, [
            "stats_date"        => ["value" => $data["stats_date"], "type" => PDO::PARAM_STR],
            "segment"           => ["value" => $data["segment"], "type" => PDO::PARAM_STR],
            "customer_count"    => ["value" => $data["customer_count"], "type" => PDO::PARAM_INT],
            "total_orders"      => ["value" => $data["total_orders"], "type" => PDO::PARAM_INT],
            "total_amount"      => ["value" => $data["total_amount"], "type" => PDO::PARAM_STR],
            "repeat_rate"       => ["value" => $data["repeat_rate"], "type" => PDO::PARAM_STR],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 프로모션 성과 통계 조회 (단일 날짜)
     *
     * @param string $statsDate
     * @return array
     */
    public function findPromotionPerformanceStats(string $statsDate): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `promotion_performance_stats`
            WHERE       `stats_date` = :stats_date
            ORDER BY    `revenue_contribution` DESC
        SQL;

        return $this->db->select($query, [
            "stats_date" => ["value" => $statsDate, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 프로모션 성과 통계 조회 (날짜 범위)
     *
     * @param string $startDate
     * @param string $endDate
     * @param string|null $promotionCode
     * @return array
     */
    public function findPromotionPerformanceStatsRange(
        string $startDate,
        string $endDate,
        ?string $promotionCode = null
    ): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `promotion_performance_stats`
            WHERE       `stats_date` BETWEEN :start_date AND :end_date
              AND       (:promotion_code IS NULL OR `promotion_code` = :promotion_code)
            ORDER BY    `stats_date` ASC, `revenue_contribution` DESC
        SQL;

        return $this->db->select($query, [
            "start_date"        => ["value" => $startDate, "type" => PDO::PARAM_STR],
            "end_date"          => ["value" => $endDate, "type" => PDO::PARAM_STR],
            "promotion_code"    => ["value" => $promotionCode, "type" => $promotionCode === null ? PDO::PARAM_NULL : PDO::PARAM_STR]
        ]);
    }

    /**
     * 프로모션 성과 통계 생성 또는 업데이트 (UPSERT)
     *
     * @param array $data
     * @return int Affected rows
     */
    public function upsertPromotionPerformanceStats(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `promotion_performance_stats` (
                `stats_date`,
                `promotion_code`,
                `usage_count`,
                `total_discount_amount`,
                `revenue_contribution`,
                `roi`,
                `created_at`
            ) VALUES (
                :stats_date,
                :promotion_code,
                :usage_count,
                :total_discount_amount,
                :revenue_contribution,
                :roi,
                :created_at
            )
            ON DUPLICATE KEY UPDATE
                `usage_count` = VALUES(`usage_count`),
                `total_discount_amount` = VALUES(`total_discount_amount`),
                `revenue_contribution` = VALUES(`revenue_contribution`),
                `roi` = VALUES(`roi`)
        SQL;

        return $this->db->insert($query, [
            "stats_date"            => ["value" => $data["stats_date"], "type" => PDO::PARAM_STR],
            "promotion_code"        => ["value" => $data["promotion_code"], "type" => PDO::PARAM_STR],
            "usage_count"           => ["value" => $data["usage_count"], "type" => PDO::PARAM_INT],
            "total_discount_amount" => ["value" => $data["total_discount_amount"], "type" => PDO::PARAM_STR],
            "revenue_contribution"  => ["value" => $data["revenue_contribution"], "type" => PDO::PARAM_STR],
            "roi"                   => ["value" => $data["roi"], "type" => PDO::PARAM_STR],
            "created_at"            => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }
}
