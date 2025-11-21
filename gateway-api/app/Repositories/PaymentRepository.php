<?php

namespace App\Repositories;

use App\Entities\PaymentEntity;
use PDO;

class PaymentRepository extends BaseRepository
{
    protected string $table = "payments";
    protected string $primaryKey = "payment_id";

    /**
     * ID로 결제 정보 조회
     *
     * @param int $paymentId
     * @return PaymentEntity|null
     */
    public function findById(int $paymentId): ?PaymentEntity
    {
        $query = <<<SQL
            SELECT  *
            FROM    `{$this->table}`
            WHERE   `payment_id` = :payment_id
        SQL;

        $row = $this->db->selectOne($query, [
            "payment_id" => ["value" => $paymentId, "type" => PDO::PARAM_INT]
        ]);

        return $row ? new PaymentEntity($row) : null;
    }

    /**
     * 주문 ID로 결제 정보 조회
     *
     * @param int $orderId
     * @return PaymentEntity|null
     */
    public function findByOrderId(int $orderId): ?PaymentEntity
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `order_id` = :order_id
            ORDER BY    `created_at` DESC
            LIMIT       1
        SQL;

        $row = $this->db->selectOne($query, [
            "order_id" => ["value" => $orderId, "type" => PDO::PARAM_INT]
        ]);

        return $row ? new PaymentEntity($row) : null;
    }

    /**
     * 멱등성 키로 결제 정보 조회
     *
     * @param string $idempotencyKey
     * @return PaymentEntity|null
     */
    public function findByIdempotencyKey(string $idempotencyKey): ?PaymentEntity
    {
        $query = <<<SQL
            SELECT  *
            FROM    `{$this->table}`
            WHERE   `idempotency_key` = :idempotency_key
        SQL;

        $row = $this->db->selectOne($query, [
            "idempotency_key" => ["value" => $idempotencyKey, "type" => PDO::PARAM_STR]
        ]);

        return $row ? new PaymentEntity($row) : null;
    }

    /**
     * 멱등성 키 존재 여부 확인
     *
     * @param string $idempotencyKey
     * @return bool
     */
    public function existsByIdempotencyKey(string $idempotencyKey): bool
    {
        $query = <<<SQL
            SELECT  COUNT(*) as cnt
            FROM    `{$this->table}`
            WHERE   `idempotency_key` = :idempotency_key
        SQL;

        $row = $this->db->selectOne($query, [
            "idempotency_key" => ["value" => $idempotencyKey, "type" => PDO::PARAM_STR]
        ]);

        return $row && $row["cnt"] > 0;
    }

    /**
     * 필터 조건에 따른 결제 목록 조회 (페이지네이션)
     *
     * @param array $filters
     * @return PaymentEntity[]
     */
    public function findAll(array $filters = []): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       (:status IS NULL OR `status` = :status)
              AND       (:order_id IS NULL OR `order_id` = :order_id)
              AND       (:pg_provider_code IS NULL OR `pg_provider_code` = :pg_provider_code)
            ORDER BY    `created_at` DESC
            LIMIT       :limit OFFSET :offset
        SQL;

        $rows = $this->db->select($query, [
            "status"            => ["value" => $filters["status"] ?? null, "type" => $filters["status"] ?? null ? PDO::PARAM_STR : PDO::PARAM_NULL],
            "order_id"          => ["value" => $filters["order_id"] ?? null, "type" => $filters["order_id"] ?? null ? PDO::PARAM_INT : PDO::PARAM_NULL],
            "pg_provider_code"  => ["value" => $filters["pg_provider_code"] ?? null, "type" => $filters["pg_provider_code"] ?? null ? PDO::PARAM_STR : PDO::PARAM_NULL],
            "limit"             => ["value" => $filters["per_page"] ?? 20, "type" => PDO::PARAM_INT],
            "offset"            => ["value" => (($filters["page"] ?? 1) - 1) * ($filters["per_page"] ?? 20), "type" => PDO::PARAM_INT]
        ]);

        return array_map(fn($row) => new PaymentEntity($row), $rows);
    }

    /**
     * 필터 조건에 따른 결제 개수 조회
     *
     * @param array $filters
     * @return int
     */
    public function count(array $filters = []): int
    {
        $query = <<<SQL
            SELECT      COUNT(*) as cnt
            FROM        `{$this->table}`
            WHERE       (:status IS NULL OR `status` = :status)
              AND       (:order_id IS NULL OR `order_id` = :order_id)
              AND       (:pg_provider_code IS NULL OR `pg_provider_code` = :pg_provider_code)
        SQL;

        $row = $this->db->selectOne($query, [
            "status"            => ["value" => $filters["status"] ?? null, "type" => $filters["status"] ?? null ? PDO::PARAM_STR : PDO::PARAM_NULL],
            "order_id"          => ["value" => $filters["order_id"] ?? null, "type" => $filters["order_id"] ?? null ? PDO::PARAM_INT : PDO::PARAM_NULL],
            "pg_provider_code"  => ["value" => $filters["pg_provider_code"] ?? null, "type" => $filters["pg_provider_code"] ?? null ? PDO::PARAM_STR : PDO::PARAM_NULL]
        ]);

        return (int) ($row["cnt"] ?? 0);
    }

    /**
     * 결제 생성
     *
     * @param array $data
     * @return int Last Insert ID
     */
    public function create(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `{$this->table}` (
                `order_id`,
                `pg_provider_code`,
                `amount`,
                `status`,
                `payment_method`,
                `idempotency_key`,
                `created_at`,
                `updated_at`
            ) VALUES (
                :order_id,
                :pg_provider_code,
                :amount,
                :status,
                :payment_method,
                :idempotency_key,
                :created_at,
                :updated_at
            )
        SQL;

        return $this->db->insert($query, [
            "order_id"          => ["value" => $data["order_id"], "type" => PDO::PARAM_INT],
            "pg_provider_code"  => ["value" => $data["pg_provider_code"], "type" => PDO::PARAM_STR],
            "amount"            => ["value" => $data["amount"], "type" => PDO::PARAM_STR],
            "status"            => ["value" => $data["status"], "type" => PDO::PARAM_STR],
            "payment_method"    => ["value" => $data["payment_method"], "type" => PDO::PARAM_STR],
            "idempotency_key"   => ["value" => $data["idempotency_key"], "type" => PDO::PARAM_STR],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR],
            "updated_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 결제 정보 업데이트 (범용)
     *
     * @param int $paymentId
     * @param array $data
     * @return int Affected rows
     */
    public function update(int $paymentId, array $data): int
    {
        $now = date("Y-m-d H:i:s");

        // 동적으로 SET 절 생성
        $setFields = [];
        $bindings = [];

        if (isset($data["status"])) {
            $setFields[] = "`status` = :status";
            $bindings["status"] = ["value" => $data["status"], "type" => PDO::PARAM_STR];
        }

        if (isset($data["pg_transaction_id"])) {
            $setFields[] = "`pg_transaction_id` = :pg_transaction_id";
            $bindings["pg_transaction_id"] = ["value" => $data["pg_transaction_id"], "type" => PDO::PARAM_STR];
        }

        if (isset($data["paid_at"])) {
            $setFields[] = "`paid_at` = :paid_at";
            $bindings["paid_at"] = ["value" => $data["paid_at"], "type" => PDO::PARAM_STR];
        }

        if (isset($data["card_masked"])) {
            $setFields[] = "`card_masked` = :card_masked";
            $bindings["card_masked"] = ["value" => $data["card_masked"], "type" => $data["card_masked"] === null ? PDO::PARAM_NULL : PDO::PARAM_STR];
        }

        if (isset($data["card_issuer_code"])) {
            $setFields[] = "`card_issuer_code` = :card_issuer_code";
            $bindings["card_issuer_code"] = ["value" => $data["card_issuer_code"], "type" => $data["card_issuer_code"] === null ? PDO::PARAM_NULL : PDO::PARAM_STR];
        }

        if (isset($data["pg_error_code"])) {
            $setFields[] = "`pg_error_code` = :pg_error_code";
            $bindings["pg_error_code"] = ["value" => $data["pg_error_code"], "type" => $data["pg_error_code"] === null ? PDO::PARAM_NULL : PDO::PARAM_STR];
        }

        if (isset($data["pg_error_message"])) {
            $setFields[] = "`pg_error_message` = :pg_error_message";
            $bindings["pg_error_message"] = ["value" => $data["pg_error_message"], "type" => $data["pg_error_message"] === null ? PDO::PARAM_NULL : PDO::PARAM_STR];
        }

        // updated_at은 항상 업데이트
        $setFields[] = "`updated_at` = :updated_at";
        $bindings["updated_at"] = ["value" => $now, "type" => PDO::PARAM_STR];

        $setClause = implode(", ", $setFields);

        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     {$setClause}
            WHERE   `payment_id` = :payment_id
        SQL;

        $bindings["payment_id"] = ["value" => $paymentId, "type" => PDO::PARAM_INT];

        return $this->db->update($query, $bindings);
    }

    /**
     * 결제 상태 업데이트
     *
     * @param int $paymentId
     * @param string $status
     * @param string|null $paidAt
     * @return int Affected rows
     */
    public function updateStatus(int $paymentId, string $status, ?string $paidAt = null): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     `status` = :status,
                    `paid_at` = :paid_at,
                    `updated_at` = :updated_at
            WHERE   `payment_id` = :payment_id
        SQL;

        return $this->db->update($query, [
            "status"        => ["value" => $status, "type" => PDO::PARAM_STR],
            "paid_at"       => ["value" => $paidAt, "type" => $paidAt === null ? PDO::PARAM_NULL : PDO::PARAM_STR],
            "updated_at"    => ["value" => $now, "type" => PDO::PARAM_STR],
            "payment_id"    => ["value" => $paymentId, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * 상태와 날짜 범위로 결제 조회 (Optional 필터)
     *
     * @param string $status
     * @param string $startDate
     * @param string $endDate
     * @param string|null $pgProviderCode
     * @return PaymentEntity[]
     */
    public function findByStatusAndDateRange(
        string $status,
        string $startDate,
        string $endDate,
        ?string $pgProviderCode = null
    ): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `status` = :status
              AND       `paid_at` BETWEEN :start_date AND :end_date
              AND       (:pg_provider_code IS NULL OR `pg_provider_code` = :pg_provider_code)
            ORDER BY    `paid_at` DESC
        SQL;

        $rows = $this->db->select($query, [
            "status"            => ["value" => $status, "type" => PDO::PARAM_STR],
            "start_date"        => ["value" => $startDate, "type" => PDO::PARAM_STR],
            "end_date"          => ["value" => $endDate, "type" => PDO::PARAM_STR],
            "pg_provider_code"  => ["value" => $pgProviderCode, "type" => $pgProviderCode === null ? PDO::PARAM_NULL : PDO::PARAM_STR]
        ]);

        return array_map(fn($row) => new PaymentEntity($row), $rows);
    }
}
