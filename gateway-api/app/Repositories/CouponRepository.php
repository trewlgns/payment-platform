<?php

namespace App\Repositories;

use PDO;

class CouponRepository extends BaseRepository
{
    protected string $table = "coupons";
    protected string $primaryKey = "coupon_code";

    /**
     * 쿠폰 코드로 조회
     *
     * @param string $couponCode
     * @return array|null
     */
    public function findByCode(string $couponCode): ?array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `{$this->table}`
            WHERE   `coupon_code` = :coupon_code
        SQL;

        return $this->db->selectOne($query, [
            "coupon_code" => ["value" => $couponCode, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 프로모션별 쿠폰 목록 조회
     *
     * @param string $promotionCode
     * @param string|null $status
     * @return array
     */
    public function findByPromotion(string $promotionCode, ?string $status = null): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `promotion_code` = :promotion_code
              AND       (:status IS NULL OR `status` = :status)
            ORDER BY    `created_at` DESC
        SQL;

        return $this->db->select($query, [
            "promotion_code"    => ["value" => $promotionCode, "type" => PDO::PARAM_STR],
            "status"            => ["value" => $status, "type" => $status === null ? PDO::PARAM_NULL : PDO::PARAM_STR]
        ]);
    }

    /**
     * 사용 가능한 쿠폰인지 확인
     *
     * @param string $couponCode
     * @return bool
     */
    public function isAvailable(string $couponCode): bool
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            SELECT  COUNT(*) as count
            FROM    `{$this->table}`
            WHERE   `coupon_code` = :coupon_code
              AND   `status` = :status
              AND   `expires_at` >= :now
              AND   `issued_count` < `max_usage`
        SQL;

        $result = $this->db->selectOne($query, [
            "coupon_code"   => ["value" => $couponCode, "type" => PDO::PARAM_STR],
            "status"        => ["value" => "issued", "type" => PDO::PARAM_STR],
            "now"           => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);

        return ((int) ($result["count"] ?? 0)) > 0;
    }

    /**
     * 쿠폰 생성
     *
     * @param array $data
     * @return int Affected rows
     */
    public function create(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `{$this->table}` (
                `coupon_code`,
                `promotion_code`,
                `issued_count`,
                `max_usage`,
                `expires_at`,
                `status`,
                `created_at`,
                `updated_at`
            ) VALUES (
                :coupon_code,
                :promotion_code,
                :issued_count,
                :max_usage,
                :expires_at,
                :status,
                :created_at,
                :updated_at
            )
        SQL;

        return $this->db->insert($query, [
            "coupon_code"       => ["value" => $data["coupon_code"], "type" => PDO::PARAM_STR],
            "promotion_code"    => ["value" => $data["promotion_code"], "type" => PDO::PARAM_STR],
            "issued_count"      => ["value" => $data["issued_count"] ?? 0, "type" => PDO::PARAM_INT],
            "max_usage"         => ["value" => $data["max_usage"] ?? 1, "type" => PDO::PARAM_INT],
            "expires_at"        => ["value" => $data["expires_at"], "type" => PDO::PARAM_STR],
            "status"            => ["value" => $data["status"] ?? "issued", "type" => PDO::PARAM_STR],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR],
            "updated_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 쿠폰 상태 업데이트
     *
     * @param string $couponCode
     * @param string $status
     * @return int Affected rows
     */
    public function updateStatus(string $couponCode, string $status): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     `status` = :status,
                    `updated_at` = :updated_at
            WHERE   `coupon_code` = :coupon_code
        SQL;

        return $this->db->update($query, [
            "status"        => ["value" => $status, "type" => PDO::PARAM_STR],
            "updated_at"    => ["value" => $now, "type" => PDO::PARAM_STR],
            "coupon_code"   => ["value" => $couponCode, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 쿠폰 발급 카운트 증가
     *
     * @param string $couponCode
     * @return int Affected rows
     */
    public function incrementIssuedCount(string $couponCode): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     `issued_count` = `issued_count` + 1,
                    `updated_at` = :updated_at
            WHERE   `coupon_code` = :coupon_code
        SQL;

        return $this->db->update($query, [
            "updated_at"    => ["value" => $now, "type" => PDO::PARAM_STR],
            "coupon_code"   => ["value" => $couponCode, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 쿠폰 사용 기록
     *
     * @param array $data
     * @return int Last Insert ID
     */
    public function redeem(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `coupon_redemptions` (
                `coupon_code`,
                `customer_email`,
                `order_id`,
                `redeemed_at`,
                `status`,
                `created_at`
            ) VALUES (
                :coupon_code,
                :customer_email,
                :order_id,
                :redeemed_at,
                :status,
                :created_at
            )
        SQL;

        return $this->db->insert($query, [
            "coupon_code"       => ["value" => $data["coupon_code"], "type" => PDO::PARAM_STR],
            "customer_email"    => ["value" => $data["customer_email"], "type" => PDO::PARAM_STR],
            "order_id"          => ["value" => $data["order_id"], "type" => PDO::PARAM_INT],
            "redeemed_at"       => ["value" => $now, "type" => PDO::PARAM_STR],
            "status"            => ["value" => "redeemed", "type" => PDO::PARAM_STR],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 쿠폰 사용 취소
     *
     * @param int $redemptionId
     * @return int Affected rows
     */
    public function cancelRedemption(int $redemptionId): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            UPDATE  `coupon_redemptions`
            SET     `status` = :status,
                    `cancelled_at` = :cancelled_at
            WHERE   `redemption_id` = :redemption_id
        SQL;

        return $this->db->update($query, [
            "status"            => ["value" => "cancelled", "type" => PDO::PARAM_STR],
            "cancelled_at"      => ["value" => $now, "type" => PDO::PARAM_STR],
            "redemption_id"     => ["value" => $redemptionId, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * 고객의 쿠폰 사용 이력 조회
     *
     * @param string $customerEmail
     * @return array
     */
    public function findRedemptionsByCustomer(string $customerEmail): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `coupon_redemptions`
            WHERE       `customer_email` = :customer_email
            ORDER BY    `redeemed_at` DESC
        SQL;

        return $this->db->select($query, [
            "customer_email" => ["value" => $customerEmail, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 주문의 쿠폰 사용 이력 조회
     *
     * @param int $orderId
     * @return array
     */
    public function findRedemptionsByOrder(int $orderId): array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `coupon_redemptions`
            WHERE   `order_id` = :order_id
        SQL;

        return $this->db->select($query, [
            "order_id" => ["value" => $orderId, "type" => PDO::PARAM_INT]
        ]);
    }
}
