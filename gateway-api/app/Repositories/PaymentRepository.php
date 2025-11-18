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
