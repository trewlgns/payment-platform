<?php

namespace App\Repositories;

use App\Entities\OrderEntity;
use PDO;

class OrderRepository extends BaseRepository
{
    protected string $table = "orders";
    protected string $primaryKey = "order_id";

    /**
     * ID로 주문 정보 조회
     *
     * @param int $orderId
     * @return OrderEntity|null
     */
    public function findById(int $orderId): ?OrderEntity
    {
        $query = <<<SQL
            SELECT  *
            FROM    `{$this->table}`
            WHERE   `order_id` = :order_id
        SQL;

        $result = $this->db->selectOne($query, [
            "order_id" => ["value" => $orderId, "type" => PDO::PARAM_INT]
        ]);

        return $result ? new OrderEntity($result) : null;
    }

    /**
     * 고객 이메일로 주문 목록 조회
     *
     * @param string $customerEmail
     * @param int $limit
     * @return OrderEntity[]
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

        $results = $this->db->select($query, [
            "customer_email"    => ["value" => $customerEmail, "type" => PDO::PARAM_STR],
            "limit"             => ["value" => $limit, "type" => PDO::PARAM_INT]
        ]);

        return array_map(fn($row) => new OrderEntity($row), $results);
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
     * @return OrderEntity[]
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

        $results = $this->db->select($query, [
            "status"    => ["value" => $status, "type" => PDO::PARAM_STR],
            "limit"     => ["value" => $limit, "type" => PDO::PARAM_INT]
        ]);

        return array_map(fn($row) => new OrderEntity($row), $results);
    }

    /**
     * 필터를 적용한 주문 목록 조회 (페이지네이션 지원)
     *
     * @param array $filters
     * @return OrderEntity[]
     */
    public function findAll(array $filters = []): array
    {
        $status = $filters["status"] ?? null;
        $customerEmail = $filters["customer_email"] ?? null;
        $page = $filters["page"] ?? 1;
        $perPage = $filters["per_page"] ?? 20;
        $offset = ($page - 1) * $perPage;

        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       1=1
        SQL;

        $bindings = [];

        if ($status) {
            $query .= " AND `status` = :status";
            $bindings["status"] = ["value" => $status, "type" => PDO::PARAM_STR];
        }

        if ($customerEmail) {
            $query .= " AND `customer_email` = :customer_email";
            $bindings["customer_email"] = ["value" => $customerEmail, "type" => PDO::PARAM_STR];
        }

        $query .= <<<SQL

            ORDER BY    `ordered_at` DESC
            LIMIT       :limit OFFSET :offset
        SQL;

        $bindings["limit"] = ["value" => $perPage, "type" => PDO::PARAM_INT];
        $bindings["offset"] = ["value" => $offset, "type" => PDO::PARAM_INT];

        $results = $this->db->select($query, $bindings);

        return array_map(fn($row) => new OrderEntity($row), $results);
    }

    /**
     * 필터를 적용한 주문 개수 조회
     *
     * @param array $filters
     * @return int
     */
    public function count(array $filters = []): int
    {
        $status = $filters["status"] ?? null;
        $customerEmail = $filters["customer_email"] ?? null;

        $query = <<<SQL
            SELECT  COUNT(*) as `total`
            FROM    `{$this->table}`
            WHERE   1=1
        SQL;

        $bindings = [];

        if ($status) {
            $query .= " AND `status` = :status";
            $bindings["status"] = ["value" => $status, "type" => PDO::PARAM_STR];
        }

        if ($customerEmail) {
            $query .= " AND `customer_email` = :customer_email";
            $bindings["customer_email"] = ["value" => $customerEmail, "type" => PDO::PARAM_STR];
        }

        $result = $this->db->selectOne($query, $bindings);

        return (int) $result["total"];
    }
}

