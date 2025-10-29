<?php

namespace App\Repositories;

use PDO;

class RefundRepository extends BaseRepository
{
    protected string $table = "refund_requests";
    protected string $primaryKey = "refund_request_id";

    /**
     * 환불 요청 ID로 조회
     *
     * @param int $refundRequestId
     * @return array|null
     */
    public function findById(int $refundRequestId): ?array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `{$this->table}`
            WHERE   `refund_request_id` = :refund_request_id
        SQL;

        return $this->db->selectOne($query, [
            "refund_request_id" => ["value" => $refundRequestId, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * 주문 ID로 환불 요청 조회
     *
     * @param int $orderId
     * @return array
     */
    public function findByOrderId(int $orderId): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `order_id` = :order_id
            ORDER BY    `requested_at` DESC
        SQL;

        return $this->db->select($query, [
            "order_id" => ["value" => $orderId, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * 결제 ID로 환불 요청 조회
     *
     * @param int $paymentId
     * @return array
     */
    public function findByPaymentId(int $paymentId): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `payment_id` = :payment_id
            ORDER BY    `requested_at` DESC
        SQL;

        return $this->db->select($query, [
            "payment_id" => ["value" => $paymentId, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * 상태별 환불 요청 조회 (날짜 범위)
     *
     * @param string $status
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function findByStatusAndDateRange(string $status, string $startDate, string $endDate): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `status` = :status
              AND       `requested_at` BETWEEN :start_date AND :end_date
            ORDER BY    `requested_at` DESC
        SQL;

        return $this->db->select($query, [
            "status"        => ["value" => $status, "type" => PDO::PARAM_STR],
            "start_date"    => ["value" => $startDate, "type" => PDO::PARAM_STR],
            "end_date"      => ["value" => $endDate, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 환불 요청 생성
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
                `payment_id`,
                `requested_amount`,
                `reason`,
                `status`,
                `requested_at`,
                `created_at`,
                `updated_at`
            ) VALUES (
                :order_id,
                :payment_id,
                :requested_amount,
                :reason,
                :status,
                :requested_at,
                :created_at,
                :updated_at
            )
        SQL;

        return $this->db->insert($query, [
            "order_id"          => ["value" => $data["order_id"], "type" => PDO::PARAM_INT],
            "payment_id"        => ["value" => $data["payment_id"], "type" => PDO::PARAM_INT],
            "requested_amount"  => ["value" => $data["requested_amount"], "type" => PDO::PARAM_STR],
            "reason"            => ["value" => $data["reason"] ?? null, "type" => isset($data["reason"]) ? PDO::PARAM_STR : PDO::PARAM_NULL],
            "status"            => ["value" => $data["status"] ?? "requested", "type" => PDO::PARAM_STR],
            "requested_at"      => ["value" => $now, "type" => PDO::PARAM_STR],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR],
            "updated_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 환불 요청 상태 업데이트
     *
     * @param int $refundRequestId
     * @param string $status
     * @param string|null $processedAt
     * @return int Affected rows
     */
    public function updateStatus(int $refundRequestId, string $status, ?string $processedAt = null): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     `status` = :status,
                    `processed_at` = :processed_at,
                    `updated_at` = :updated_at
            WHERE   `refund_request_id` = :refund_request_id
        SQL;

        return $this->db->update($query, [
            "status"            => ["value" => $status, "type" => PDO::PARAM_STR],
            "processed_at"      => ["value" => $processedAt, "type" => $processedAt === null ? PDO::PARAM_NULL : PDO::PARAM_STR],
            "updated_at"        => ["value" => $now, "type" => PDO::PARAM_STR],
            "refund_request_id" => ["value" => $refundRequestId, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * 환불 트랜잭션 생성
     *
     * @param array $data
     * @return int Last Insert ID
     */
    public function createTransaction(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `refund_transactions` (
                `refund_request_id`,
                `pg_transaction_id`,
                `refunded_amount`,
                `status`,
                `response_payload`,
                `created_at`
            ) VALUES (
                :refund_request_id,
                :pg_transaction_id,
                :refunded_amount,
                :status,
                :response_payload,
                :created_at
            )
        SQL;

        return $this->db->insert($query, [
            "refund_request_id" => ["value" => $data["refund_request_id"], "type" => PDO::PARAM_INT],
            "pg_transaction_id" => ["value" => $data["pg_transaction_id"], "type" => PDO::PARAM_STR],
            "refunded_amount"   => ["value" => $data["refunded_amount"], "type" => PDO::PARAM_STR],
            "status"            => ["value" => $data["status"] ?? "requested", "type" => PDO::PARAM_STR],
            "response_payload"  => ["value" => isset($data["response_payload"]) ? json_encode($data["response_payload"]) : null, "type" => isset($data["response_payload"]) ? PDO::PARAM_STR : PDO::PARAM_NULL],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 환불 요청에 대한 트랜잭션 조회
     *
     * @param int $refundRequestId
     * @return array
     */
    public function findTransactions(int $refundRequestId): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `refund_transactions`
            WHERE       `refund_request_id` = :refund_request_id
            ORDER BY    `created_at` DESC
        SQL;

        return $this->db->select($query, [
            "refund_request_id" => ["value" => $refundRequestId, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * PG 트랜잭션 ID로 환불 트랜잭션 조회
     *
     * @param string $pgTransactionId
     * @return array|null
     */
    public function findTransactionByPgId(string $pgTransactionId): ?array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `refund_transactions`
            WHERE   `pg_transaction_id` = :pg_transaction_id
        SQL;

        return $this->db->selectOne($query, [
            "pg_transaction_id" => ["value" => $pgTransactionId, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 환불 트랜잭션 상태 업데이트
     *
     * @param int $refundTransactionId
     * @param string $status
     * @return int Affected rows
     */
    public function updateTransactionStatus(int $refundTransactionId, string $status): int
    {
        $query = <<<SQL
            UPDATE  `refund_transactions`
            SET     `status` = :status
            WHERE   `refund_transaction_id` = :refund_transaction_id
        SQL;

        return $this->db->update($query, [
            "status"                => ["value" => $status, "type" => PDO::PARAM_STR],
            "refund_transaction_id" => ["value" => $refundTransactionId, "type" => PDO::PARAM_INT]
        ]);
    }
}
