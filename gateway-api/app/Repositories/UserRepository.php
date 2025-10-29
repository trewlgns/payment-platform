<?php

namespace App\Repositories;

use PDO;

class UserRepository extends BaseRepository
{
    protected string $table = "users";
    protected string $primaryKey = "email";

    /**
     * 이메일로 사용자 조회
     *
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        $query = <<<SQL
            SELECT  *
            FROM    `{$this->table}`
            WHERE   `email` = :email
        SQL;

        return $this->db->selectOne($query, [
            "email" => ["value" => $email, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 활성 사용자 목록 조회
     *
     * @param string $status
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findByStatus(string $status, int $limit = 20, int $offset = 0): array
    {
        $query = <<<SQL
            SELECT      *
            FROM        `{$this->table}`
            WHERE       `status` = :status
            ORDER BY    `created_at` DESC
            LIMIT       :limit OFFSET :offset
        SQL;

        return $this->db->select($query, [
            "status"    => ["value" => $status, "type" => PDO::PARAM_STR],
            "limit"     => ["value" => $limit, "type" => PDO::PARAM_INT],
            "offset"    => ["value" => $offset, "type" => PDO::PARAM_INT]
        ]);
    }

    /**
     * 사용자 생성
     *
     * @param array $data
     * @return int Affected rows (사용자는 email이 PK이므로 insert 성공 시 1 반환)
     */
    public function create(array $data): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            INSERT INTO `{$this->table}` (
                `email`,
                `password_hash`,
                `name`,
                `phone`,
                `status`,
                `created_at`,
                `updated_at`
            ) VALUES (
                :email,
                :password_hash,
                :name,
                :phone,
                :status,
                :created_at,
                :updated_at
            )
        SQL;

        return $this->db->insert($query, [
            "email"         => ["value" => $data["email"], "type" => PDO::PARAM_STR],
            "password_hash" => ["value" => $data["password_hash"], "type" => PDO::PARAM_STR],
            "name"          => ["value" => $data["name"], "type" => PDO::PARAM_STR],
            "phone"         => ["value" => $data["phone"], "type" => PDO::PARAM_STR],
            "status"        => ["value" => $data["status"] ?? "active", "type" => PDO::PARAM_STR],
            "created_at"    => ["value" => $now, "type" => PDO::PARAM_STR],
            "updated_at"    => ["value" => $now, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 사용자 상태 업데이트
     *
     * @param string $email
     * @param string $status
     * @return int Affected rows
     */
    public function updateStatus(string $email, string $status): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     `status` = :status,
                    `updated_at` = :updated_at
            WHERE   `email` = :email
        SQL;

        return $this->db->update($query, [
            "status"        => ["value" => $status, "type" => PDO::PARAM_STR],
            "updated_at"    => ["value" => $now, "type" => PDO::PARAM_STR],
            "email"         => ["value" => $email, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 이메일 인증 완료 처리
     *
     * @param string $email
     * @return int Affected rows
     */
    public function markEmailAsVerified(string $email): int
    {
        $now = date("Y-m-d H:i:s");

        $query = <<<SQL
            UPDATE  `{$this->table}`
            SET     `email_verified_at` = :email_verified_at,
                    `updated_at` = :updated_at
            WHERE   `email` = :email
        SQL;

        return $this->db->update($query, [
            "email_verified_at" => ["value" => $now, "type" => PDO::PARAM_STR],
            "updated_at"        => ["value" => $now, "type" => PDO::PARAM_STR],
            "email"             => ["value" => $email, "type" => PDO::PARAM_STR]
        ]);
    }

    /**
     * 가입 기간으로 사용자 수 조회 (통계용)
     *
     * @param string $startDate
     * @param string $endDate
     * @return int
     */
    public function countByDateRange(string $startDate, string $endDate): int
    {
        $query = <<<SQL
            SELECT  COUNT(*) as count
            FROM    `{$this->table}`
            WHERE   `created_at` BETWEEN :start_date AND :end_date
        SQL;

        $result = $this->db->selectOne($query, [
            "start_date"    => ["value" => $startDate, "type" => PDO::PARAM_STR],
            "end_date"      => ["value" => $endDate, "type" => PDO::PARAM_STR]
        ]);

        return (int) ($result["count"] ?? 0);
    }
}
