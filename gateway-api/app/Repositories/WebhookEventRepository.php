<?php

namespace App\Repositories;

use PDO;

class WebhookEventRepository extends BaseRepository
{
    protected string $table = "webhook_events";
    protected string $primaryKey = "webhook_event_id";

    /**
     * Webhook 이벤트 ID로 조회
     *
     * @param int $webhookEventId
     * @return array|null
     */
    public function findById(int $webhookEventId): ?array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `{$this->table}`
            WHERE   `webhook_event_id` = :webhook_event_id
        SQL;

        return $this->db->selectOne($query, [
            "webhook_event_id" => ["value" => $webhookEventId, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * PG별 Webhook 이벤트 조회 (날짜 범위)
     *
     * @param string $pgProviderCode
     * @param string $startDate
     * @param string $endDate
     * @param string|null $status
     * @return array
     */
    public function findByPgAndDateRange(
        string $pgProviderCode,
        string $startDate,
        string $endDate,
        ?string $status = null
    ): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `pg_provider_code` = :pg_provider_code
              AND       `received_at` BETWEEN :start_date AND :end_date
              AND       (:status IS NULL OR `status` = :status)
            ORDER BY    `received_at` DESC
        SQL;

        return $this->db->select($query, [
            "pg_provider_code"  => ["value" => $pgProviderCode, "type" => PDO::PARAM_STR],
            "start_date"        => ["value" => $startDate, "type" => PDO::PARAM_STR],
            "end_date"          => ["value" => $endDate, "type" => PDO::PARAM_STR],
            "status"            => ["value" => $status, "type" => $status === null ? PDO::PARAM_NULL : PDO::PARAM_STR]
        ]);
    }

    /**
     * 이벤트 타입별 조회
     *
     * @param string $eventType
     * @param int $limit
     * @return array
     */
    public function findByEventType(string $eventType, int $limit = 100): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `event_type` = :event_type
            ORDER BY    `received_at` DESC
            LIMIT       :limit
        SQL;

        return $this->db->select($query, [
            "event_type"    => ["value" => $eventType, "type" => PDO::PARAM_STR],
            "limit"         => ["value" => $limit, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * 미처리된 Webhook 이벤트 조회
     *
     * @param int $limit
     * @return array
     */
    public function findPending(int $limit = 50): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `status` = :status
            ORDER BY    `received_at` ASC
            LIMIT       :limit
        SQL;

        return $this->db->select($query, [
            "status"    => ["value" => "received", "type" => PDO::PARAM_STR],
            "limit"     => ["value" => $limit, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * Webhook 이벤트 생성
     *
     * @param array $data
     * @return int Last Insert ID
     */
    public function create(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `{$this->table}` (
                `pg_provider_code`,
                `event_type`,
                `payload`,
                `status`,
                `received_at`,
                `created_at`
            ) VALUES (
                :pg_provider_code,
                :event_type,
                :payload,
                :status,
                :received_at,
                :created_at
            )
        SQL;

        return $this->db->insert($query, [
            "pg_provider_code"  => ["value" => $data["pg_provider_code"], "type" => PDO::PARAM_STR],
            "event_type"        => ["value" => $data["event_type"], "type" => PDO::PARAM_STR],
            "payload"           => ["value" => json_encode($data["payload"]), "type" => PDO::PARAM_STR],
            "status"            => ["value" => $data["status"] ?? "received", "type" => PDO::PARAM_STR],
            "received_at"       => ["value" => $now, "type" => PDO::PARAM_STR],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * Webhook 이벤트 상태 업데이트
     *
     * @param int $webhookEventId
     * @param string $status
     * @param string|null $processedAt
     * @return int Affected rows
     */
    public function updateStatus(int $webhookEventId, string $status, ?string $processedAt = null): int
    {
        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     `status` = :status,
                    `processed_at` = :processed_at
            WHERE   `webhook_event_id` = :webhook_event_id
        SQL;

        return $this->db->update($query, [
            "status"            => ["value" => $status, "type" => PDO::PARAM_STR],
            "processed_at"      => ["value" => $processedAt, "type" => $processedAt === null ? PDO::PARAM_NULL : PDO::PARAM_STR],
            "webhook_event_id"  => ["value" => $webhookEventId, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * API 호출 로그 생성
     *
     * @param array $data
     * @return int Last Insert ID
     */
    public function createApiCallLog(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `api_call_logs` (
                `pg_provider_code`,
                `endpoint`,
                `request_body`,
                `response_body`,
                `status_code`,
                `duration_ms`,
                `created_at`
            ) VALUES (
                :pg_provider_code,
                :endpoint,
                :request_body,
                :response_body,
                :status_code,
                :duration_ms,
                :created_at
            )
        SQL;

        return $this->db->insert($query, [
            "pg_provider_code"  => ["value" => $data["pg_provider_code"], "type" => PDO::PARAM_STR],
            "endpoint"          => ["value" => $data["endpoint"], "type" => PDO::PARAM_STR],
            "request_body"      => ["value" => isset($data["request_body"]) ? json_encode($data["request_body"]) : null, "type" => isset($data["request_body"]) ? PDO::PARAM_STR : PDO::PARAM_NULL],
            "response_body"     => ["value" => isset($data["response_body"]) ? json_encode($data["response_body"]) : null, "type" => isset($data["response_body"]) ? PDO::PARAM_STR : PDO::PARAM_NULL],
            "status_code"       => ["value" => $data["status_code"], "type" => PDO::PARAM_INT],
            "duration_ms"       => ["value" => $data["duration_ms"], "type" => PDO::PARAM_INT],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * API 호출 로그 조회 (날짜 범위)
     *
     * @param string $pgProviderCode
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function findApiCallLogs(string $pgProviderCode, string $startDate, string $endDate): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `api_call_logs`
            WHERE       `pg_provider_code` = :pg_provider_code
              AND       `created_at` BETWEEN :start_date AND :end_date
            ORDER BY    `created_at` DESC
        SQL;

        return $this->db->select($query, [
            "pg_provider_code"  => ["value" => $pgProviderCode, "type" => PDO::PARAM_STR],
            "start_date"        => ["value" => $startDate, "type" => PDO::PARAM_STR],
            "end_date"          => ["value" => $endDate, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * API 호출 실패 로그 조회 (HTTP 4xx, 5xx)
     *
     * @param string $pgProviderCode
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function findFailedApiCalls(string $pgProviderCode, string $startDate, string $endDate): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `api_call_logs`
            WHERE       `pg_provider_code` = :pg_provider_code
              AND       `created_at` BETWEEN :start_date AND :end_date
              AND       `status_code` >= :min_error_code
            ORDER BY    `created_at` DESC
        SQL;

        return $this->db->select($query, [
            "pg_provider_code"  => ["value" => $pgProviderCode, "type" => PDO::PARAM_STR],
            "start_date"        => ["value" => $startDate, "type" => PDO::PARAM_STR],
            "end_date"          => ["value" => $endDate, "type" => PDO::PARAM_STR],
            "min_error_code"    => ["value" => 400, "type" => PDO::PARAM_INT]
        ]);
    }
}
