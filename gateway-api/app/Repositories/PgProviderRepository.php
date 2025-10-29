<?php

namespace App\Repositories;

use PDO;

class PgProviderRepository extends BaseRepository
{
    protected string $table = "pg_providers";
    protected string $primaryKey = "pg_provider_code";

    /**
     * PG 제공자 코드로 조회
     *
     * @param string $pgProviderCode
     * @return array|null
     */
    public function findByCode(string $pgProviderCode): ?array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `{$this->table}`
            WHERE   `pg_provider_code` = :pg_provider_code
        SQL;

        return $this->db->selectOne($query, [
            "pg_provider_code" => ["value" => $pgProviderCode, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 활성화된 PG 제공자 목록 조회 (우선순위순)
     *
     * @return array
     */
    public function findAllActive(): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `is_active` = :is_active
            ORDER BY    `priority` DESC, `name` ASC
        SQL;

        return $this->db->select($query, [
            "is_active" => ["value" => 1, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * PG 제공자 생성
     *
     * @param array $data
     * @return int Affected rows
     */
    public function create(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `{$this->table}` (
                `pg_provider_code`,
                `name`,
                `is_active`,
                `priority`,
                `created_at`,
                `updated_at`
            ) VALUES (
                :pg_provider_code,
                :name,
                :is_active,
                :priority,
                :created_at,
                :updated_at
            )
        SQL;

        return $this->db->insert($query, [
            "pg_provider_code"  => ["value" => $data["pg_provider_code"], "type" => PDO::PARAM_STR],
            "name"              => ["value" => $data["name"], "type" => PDO::PARAM_STR],
            "is_active"         => ["value" => $data["is_active"] ?? 1, "type" => PDO::PARAM_INT],
            "priority"          => ["value" => $data["priority"] ?? 0, "type" => PDO::PARAM_INT],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR],
            "updated_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * PG 제공자 활성화 상태 업데이트
     *
     * @param string $pgProviderCode
     * @param int $isActive
     * @return int Affected rows
     */
    public function updateActiveStatus(string $pgProviderCode, int $isActive): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     `is_active` = :is_active,
                    `updated_at` = :updated_at
            WHERE   `pg_provider_code` = :pg_provider_code
        SQL;

        return $this->db->update($query, [
            "is_active"         => ["value" => $isActive, "type" => PDO::PARAM_INT],
            "updated_at"        => ["value" => $now, "type" => PDO::PARAM_STR],
            "pg_provider_code"  => ["value" => $pgProviderCode, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * PG 자격증명 조회 (특정 환경)
     *
     * @param string $pgProviderCode
     * @param string $environment
     * @return array|null
     */
    public function findCredential(string $pgProviderCode, string $environment): ?array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `pg_credentials`
            WHERE   `pg_provider_code` = :pg_provider_code
              AND   `environment` = :environment
              AND   `is_active` = :is_active
            LIMIT   1
        SQL;

        return $this->db->selectOne($query, [
            "pg_provider_code"  => ["value" => $pgProviderCode, "type" => PDO::PARAM_STR],
            "environment"       => ["value" => $environment, "type" => PDO::PARAM_STR],
            "is_active"         => ["value" => 1, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * PG 자격증명 생성
     *
     * @param array $data
     * @return int Last Insert ID
     */
    public function createCredential(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `pg_credentials` (
                `pg_provider_code`,
                `merchant_id`,
                `api_key_encrypted`,
                `environment`,
                `is_active`,
                `created_at`,
                `updated_at`
            ) VALUES (
                :pg_provider_code,
                :merchant_id,
                :api_key_encrypted,
                :environment,
                :is_active,
                :created_at,
                :updated_at
            )
        SQL;

        return $this->db->insert($query, [
            "pg_provider_code"  => ["value" => $data["pg_provider_code"], "type" => PDO::PARAM_STR],
            "merchant_id"       => ["value" => $data["merchant_id"], "type" => PDO::PARAM_STR],
            "api_key_encrypted" => ["value" => $data["api_key_encrypted"], "type" => PDO::PARAM_STR],
            "environment"       => ["value" => $data["environment"], "type" => PDO::PARAM_STR],
            "is_active"         => ["value" => $data["is_active"] ?? 1, "type" => PDO::PARAM_INT],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR],
            "updated_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * PG 에러 코드 조회
     *
     * @param string $pgProviderCode
     * @param string $errorCode
     * @return array|null
     */
    public function findErrorCode(string $pgProviderCode, string $errorCode): ?array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `pg_error_codes`
            WHERE   `pg_provider_code` = :pg_provider_code
              AND   `error_code` = :error_code
        SQL;

        return $this->db->selectOne($query, [
            "pg_provider_code"  => ["value" => $pgProviderCode, "type" => PDO::PARAM_STR],
            "error_code"        => ["value" => $errorCode, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * PG 에러 코드 등록 (주로 Seeder에서 사용, 드물게 동적 등록)
     *
     * @param array $data
     * @return int Affected rows
     */
    public function createErrorCode(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `pg_error_codes` (
                `pg_provider_code`,
                `error_code`,
                `error_message`,
                `error_category`,
                `action_type`,
                `created_at`
            ) VALUES (
                :pg_provider_code,
                :error_code,
                :error_message,
                :error_category,
                :action_type,
                :created_at
            )
        SQL;

        return $this->db->insert($query, [
            "pg_provider_code"  => ["value" => $data["pg_provider_code"], "type" => PDO::PARAM_STR],
            "error_code"        => ["value" => $data["error_code"], "type" => PDO::PARAM_STR],
            "error_message"     => ["value" => $data["error_message"], "type" => PDO::PARAM_STR],
            "error_category"    => ["value" => $data["error_category"], "type" => PDO::PARAM_STR],
            "action_type"       => ["value" => $data["action_type"], "type" => PDO::PARAM_STR],
            "created_at"        => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }
}
