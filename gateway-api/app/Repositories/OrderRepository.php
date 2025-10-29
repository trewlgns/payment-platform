<?php

namespace App\Repositories;

use PDO;

class OrderRepository extends BaseRepository
{
    protected string $table = "orders";
    protected string $primaryKey = "order_id";

    /**
     * ID로 주문 정보 조회
     *
     * @param int $orderId
     * @return array|null
     */
    public function findById(int $orderId): ?array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `{$this->table}`
            WHERE   `order_id` = :order_id
        SQL;

        return $this->db->selectOne($query, [
            "order_id" => ["value" => $orderId, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * 고객 이메일로 주문 목록 조회
     *
     * @param string $customerEmail
     * @param int $limit
     * @return array
     */
    public function findByCustomerEmail(string $customerEmail, int $limit = 10): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `customer_email` = :customer_email
            ORDER BY    `ordered_at` DESC
            LIMIT       :limit
        SQL;

        return $this->db->select($query, [
            "customer_email"    => ["value" => $customerEmail, "type" => PDO::PARAM_STR],
            "limit"             => ["value" => $limit, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * 주문 생성
     *
     * @param array $data
     * @return int Last Insert ID
     */
    public function create(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `{$this->table}` (
                `customer_email`,
                `total_amount`,
                `discount_amount`,
                `final_amount`,
                `status`,
                `ordered_at`,
                `created_at`,
                `updated_at`
            ) VALUES (
                :customer_email,
                :total_amount,
                :discount_amount,
                :final_amount,
                :status,
                :ordered_at,
                :created_at,
                :updated_at
            )
        SQL;

        return $this->db->insert($query, [
            "customer_email"    => ["value" => $data["customer_email"], "type" => PDO::PARAM_STR],
            "total_amount"      => ["value" => $data["total_amount"], "type" => PDO::PARAM_STR],
            "discount_amount"   => ["value" => $data["discount_amount"], "type" => PDO::PARAM_STR],
            "final_amount"      => ["value" => $data["final_amount"], "type" => PDO::PARAM_STR],
            "status"            => ["value" => $data["status"], "type" => PDO::PARAM_STR],
            "ordered_at"        => ["value" => $now, "type" => PDO::PARAM_STR],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR],
            "updated_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 주문 상태 업데이트
     *
     * @param int $orderId
     * @param string $status
     * @return int Affected rows
     */
    public function updateStatus(int $orderId, string $status): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     `status` = :status,
                    `updated_at` = :updated_at
            WHERE   `order_id` = :order_id
        SQL;

        return $this->db->update($query, [
            "status"        => ["value" => $status, "type" => PDO::PARAM_STR],
            "updated_at"    => ["value" => $now, "type" => PDO::PARAM_STR],
            "order_id"      => ["value" => $orderId, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * 상태별 주문 조회
     *
     * @param string $status
     * @param int $limit
     * @return array
     */
    public function findByStatus(string $status, int $limit = 100): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `status` = :status
            ORDER BY    `ordered_at` DESC
            LIMIT       :limit
        SQL;

        return $this->db->select($query, [
            "status"    => ["value" => $status, "type" => PDO::PARAM_STR],
            "limit"     => ["value" => $limit, "type" => PDO::PARAM_INT]
        ]);
    }
}
