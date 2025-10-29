<?php

namespace App\Repositories;

use PDO;

class PromotionRepository extends BaseRepository
{
    protected string $table = "promotions";
    protected string $primaryKey = "promotion_code";

    /**
     * 프로모션 코드로 조회
     *
     * @param string $promotionCode
     * @return array|null
     */
    public function findByCode(string $promotionCode): ?array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `{$this->table}`
            WHERE   `promotion_code` = :promotion_code
        SQL;

        return $this->db->selectOne($query, [
            "promotion_code" => ["value" => $promotionCode, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 현재 활성 중인 프로모션 목록 조회
     *
     * @param string|null $promotionType
     * @return array
     */
    public function findActivePromotions(?string $promotionType = null): array
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `is_active` = :is_active
              AND       `start_at` <= :now
              AND       `end_at` >= :now
              AND       (:promotion_type IS NULL OR `promotion_type` = :promotion_type)
            ORDER BY    `start_at` DESC
        SQL;

        return $this->db->select($query, [
            "is_active"         => ["value" => 1, "type" => PDO::PARAM_INT],
            "now"               => ["value" => $now, "type" => PDO::PARAM_STR],
            "promotion_type"    => ["value" => $promotionType, "type" => $promotionType === null ? PDO::PARAM_NULL : PDO::PARAM_STR]
        ]);
    }

    /**
     * 프로모션 생성
     *
     * @param array $data
     * @return int Affected rows
     */
    public function create(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `{$this->table}` (
                `promotion_code`,
                `name`,
                `promotion_type`,
                `discount_type`,
                `discount_value`,
                `start_at`,
                `end_at`,
                `is_active`,
                `created_at`,
                `updated_at`
            ) VALUES (
                :promotion_code,
                :name,
                :promotion_type,
                :discount_type,
                :discount_value,
                :start_at,
                :end_at,
                :is_active,
                :created_at,
                :updated_at
            )
        SQL;

        return $this->db->insert($query, [
            "promotion_code"    => ["value" => $data["promotion_code"], "type" => PDO::PARAM_STR],
            "name"              => ["value" => $data["name"], "type" => PDO::PARAM_STR],
            "promotion_type"    => ["value" => $data["promotion_type"], "type" => PDO::PARAM_STR],
            "discount_type"     => ["value" => $data["discount_type"], "type" => PDO::PARAM_STR],
            "discount_value"    => ["value" => $data["discount_value"], "type" => PDO::PARAM_STR],
            "start_at"          => ["value" => $data["start_at"], "type" => PDO::PARAM_STR],
            "end_at"            => ["value" => $data["end_at"], "type" => PDO::PARAM_STR],
            "is_active"         => ["value" => $data["is_active"] ?? 1, "type" => PDO::PARAM_INT],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR],
            "updated_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 프로모션 활성화 상태 업데이트
     *
     * @param string $promotionCode
     * @param int $isActive
     * @return int Affected rows
     */
    public function updateActiveStatus(string $promotionCode, int $isActive): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     `is_active` = :is_active,
                    `updated_at` = :updated_at
            WHERE   `promotion_code` = :promotion_code
        SQL;

        return $this->db->update($query, [
            "is_active"         => ["value" => $isActive, "type" => PDO::PARAM_INT],
            "updated_at"        => ["value" => $now, "type" => PDO::PARAM_STR],
            "promotion_code"    => ["value" => $promotionCode, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 프로모션 규칙 조회
     *
     * @param string $promotionCode
     * @return array
     */
    public function findRules(string $promotionCode): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `promotion_rules`
            WHERE       `promotion_code` = :promotion_code
            ORDER BY    `priority` DESC
        SQL;

        return $this->db->select($query, [
            "promotion_code" => ["value" => $promotionCode, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 프로모션 규칙 생성
     *
     * @param array $data
     * @return int Last Insert ID
     */
    public function createRule(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `promotion_rules` (
                `promotion_code`,
                `rule_type`,
                `rule_value`,
                `priority`,
                `created_at`
            ) VALUES (
                :promotion_code,
                :rule_type,
                :rule_value,
                :priority,
                :created_at
            )
        SQL;

        return $this->db->insert($query, [
            "promotion_code"    => ["value" => $data["promotion_code"], "type" => PDO::PARAM_STR],
            "rule_type"         => ["value" => $data["rule_type"], "type" => PDO::PARAM_STR],
            "rule_value"        => ["value" => json_encode($data["rule_value"]), "type" => PDO::PARAM_STR],
            "priority"          => ["value" => $data["priority"] ?? 0, "type" => PDO::PARAM_INT],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 주문에 프로모션 적용 기록
     *
     * @param array $data
     * @return int Last Insert ID
     */
    public function applyToOrder(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `order_promotions` (
                `order_id`,
                `promotion_code`,
                `coupon_code`,
                `discount_amount`,
                `applied_at`
            ) VALUES (
                :order_id,
                :promotion_code,
                :coupon_code,
                :discount_amount,
                :applied_at
            )
        SQL;

        return $this->db->insert($query, [
            "order_id"          => ["value" => $data["order_id"], "type" => PDO::PARAM_INT],
            "promotion_code"    => ["value" => $data["promotion_code"], "type" => PDO::PARAM_STR],
            "coupon_code"       => ["value" => $data["coupon_code"] ?? null, "type" => isset($data["coupon_code"]) ? PDO::PARAM_STR : PDO::PARAM_NULL],
            "discount_amount"   => ["value" => $data["discount_amount"], "type" => PDO::PARAM_STR],
            "applied_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 주문에 적용된 프로모션 조회
     *
     * @param int $orderId
     * @return array
     */
    public function findByOrderId(int $orderId): array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `order_promotions`
            WHERE   `order_id` = :order_id
        SQL;

        return $this->db->select($query, [
            "order_id" => ["value" => $orderId, "type" => PDO::PARAM_INT]
        ]);
    }
}
